<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="common.css">
</head>

<body>

    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $uid = $_SESSION['uid'];
    $type = $_SESSION['type'];
    include_once ("mysqlConnection.php");
    date_default_timezone_set('Australia/Sydney');
    class Role
    {
        protected $conn;
        protected $uid;
        protected $type;
        public function __construct($conn, $uid, $type)
        {
            $this->conn = $conn;
            $this->uid = $uid;
            $this->type = $type;

        }
        //search parking location can be used for both roles
        public function searchParkingLocation($conn)
        {
            $sql = "";
            $pid = stripslashes(trim($_POST['pid']));
            $location = stripslashes(trim($_POST['location']));
            $desc = stripslashes(trim($_POST['desc']));
            if (strlen($pid) > 0 || strlen($location) > 0 || strlen($desc) > 0) {
                if (strlen($pid) > 0) {
                    $sql = "select * from ParkingLocation where pid like '%{$_POST['pid']}%'";

                } else if (strlen($location) > 0) {
                    $sql = "select * from ParkingLocation where location like '%{$_POST['location']}%'";

                } else if (strlen($desc) > 0) {
                    $sql = "select * from ParkingLocation where description like '%{$_POST['desc']}%'";
                }
                try {
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        echo "<table class='search'>
                        <th>Parking ID</th>
                        <th>Location</th>
                        <th>Description</th>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                            <td>{$row['pid']}</td>
                            <td>{$row['location']}</td>
                            <td>{$row['description']}</td>
                        </tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "please enter valid info <br>";
                    }

                } catch (mysqli_sql_exception $e) {
                    die($e->getCode() . " : " . $e->getMessage());
                }
            } else {
                echo "enter at least one field to search <br>";
            }

        }



    }

    class User extends Role
    {
        protected $conn;
        public function __construct($conn, $uid, $type)
        {
            parent::__construct($conn, $uid, $type);
        }
        public function listForUser($conn)
        {
            $sql = "select ParkingLocation.pid as lpid,location,description, parking_space,count(Parking.pid) as pnum 
                    from ParkingLocation
                    left join Parking on ParkingLocation.pid =Parking.pid and finish_time is null
                    group by lpid,location,description,parking_space
                    having parking_space>pnum
                    ";

            try {
                $result = $conn->query($sql);
                echo "<table class='search'>
                        <th>Parking ID</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Available Spaces</th>"
                ;
                while ($row = $result->fetch_assoc()) {
                    $availableSpace = $row['parking_space'] - $row['pnum'];
                    echo "<tr>
                            <td>{$row['lpid']}</td>
                            <td>{$row['location']}</td>
                            <td>{$row['description']}</td>
                            <td>$availableSpace</td>
                            ";

                    echo "</tr>";

                }

                echo "</table>";
            } catch (mysqli_sql_exception $e) {
                die($e->getCode() . " : " . $e->getMessage());
            }

        }

        public function userCheckIn($conn, $uid)
        {
            $pid = stripslashes(trim($_POST['pid']));
            $startTime = date('Y-m-d H:i:s');

            if (strlen($pid) > 0) {
                //check parking id valid
                $queryParkingLocation = "select * from ParkingLocation";
                try {
                    $locationResult = $conn->query($queryParkingLocation);
                    $validParkingId = false;
                    while ($row = $locationResult->fetch_assoc()) {
                        if ($row['pid'] == $pid) {
                            $validParkingId = true;
                            //check whether user currently parking
                            $queryParkingExisting = "select * from Parking where uid='$uid' and pid='$pid'and finish_time is null";
                            try {
                                $parkingExisting = $conn->query($queryParkingExisting);
                                if ($parkingExisting->num_rows > 0) {
                                    echo "you are currently parking in this location <br>";
                                    return;
                                }
                            } catch (mysqli_sql_exception $e) {
                                die($e->getCode() . " : " . $e->getMessage());
                            }
                            //check capacity
                            $queryParkingCapacity = "select Parking.pid as ppid, parking_space,count(*) as current_num from Parking
                                   join ParkingLocation on Parking.pid=ParkingLocation.pid and finish_time is null
                                   where Parking.pid='$pid'
                                   group by Parking.pid,parking_space";
                            try {
                                $parkingCapacity = $conn->query($queryParkingCapacity);
                                while ($row = $parkingCapacity->fetch_assoc()) {
                                    $capacity = $row['parking_space'] - $row['current_num'];
                                    if ($capacity <= 0) {
                                        echo "parking is full, change to another location <br>";
                                        return;
                                    }

                                }

                            } catch (mysqli_sql_exception $e) {
                                die($e->getCode() . " : " . $e->getMessage());
                            }

                            $costQuery = "select * from ParkingLocation
                             where pid='$pid'";
                            $sql = "insert into Parking (uid,pid,start_time) values
                                     ('$uid','$pid','$startTime')";
                            try {
                                $result = $conn->query($sql);
                                $costResult = $conn->query($costQuery);
                                while ($row = $costResult->fetch_assoc()) {
                                    echo "You have been successfully checked in, parking starts at $startTime, cost is $ {$row['cost_per_hour']} per hour<br>";
                                }

                            } catch (mysqli_sql_exception $e) {
                                die($e->getCode() . " : " . $e->getMessage());
                            }
                            break;
                        }
                    }
                    if ($validParkingId === false) {
                        echo "invalid parking ID, please try again <br>";
                    }

                } catch (mysqli_sql_exception $e) {
                    die($e->getCode() . " : " . $e->getMessage());
                }

            } else {
                echo "please fill out the form <br>";
            }

        }

        public function currentParking($conn, $uid)
        {

            $sql = "select * from Parking
            join ParkingLocation on Parking.pid= ParkingLocation.pid
            where Parking.uid='$uid' and finish_time is null
            ";
            try {
                $result = $conn->query($sql);
                //check whether user have records
                if ($result->num_rows > 0) {
                    echo "<table class='search'>
                        <th>Parking ID</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Action</th>
                        "
                    ;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['pid']}</td>
                            <td>{$row['location']}</td>
                            <td>{$row['description']}</td>
                            <td><a href='userFunction.php?delpid={$row['pid']}'>Check Out</a></td>
                            ";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "You don't have any current parking in Easy Parking <br>";
                }

            } catch (mysqli_sql_exception $e) {
                die($e->getCode() . " : " . $e->getMessage());
            }

        }

        public function userCheckOut($conn, $uid)
        {

            $delpid = $_GET['delpid'];
            $finishTime = date('Y-m-d H:i:s');
            $updateSql = "update Parking
                                set finish_time='$finishTime'
                                where pid='$delpid' and uid='$uid'";
            $sql = "select * from Parking
                  join ParkingLocation on Parking.pid=ParkingLocation.pid
                  where uid='$uid' and Parking.pid='$delpid' and finish_time is not null";

            try {
                $conn->query($updateSql);
                if ($conn->affected_rows > 0) {
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $duration = ceil(((strtotime($row['finish_time']) - strtotime($row['start_time'])) / 3600));
                            if ($duration < 1) {
                                $duration = 1;
                            }
                            $fee = $duration * $row['cost_per_hour'];

                        }
                        echo "check out successful, total fee is $$fee <br>";
                    }

                } else {
                    echo "Check out failed. Please try again.<br>";
                }
            } catch (mysqli_sql_exception $e) {
                die($e->getCode() . " : " . $e->getMessage());
            }

        }


        public function parkingHistory($conn, $uid)
        {
            $sql = "select * from Parking
                  join ParkingLocation on Parking.pid=ParkingLocation.pid
                  where uid='$uid' and finish_time is not null";
            try {
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<table class='search'>
                        <th>Parking ID</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Start Time</th>
                        <th>Finish Time</th>
                        <th>Cost/Hour</th>
                        <th>Duration(Hour)</th>
                        <th>Fee</th>
                        "
                    ;
                    while ($row = $result->fetch_assoc()) {
                        $duration = ceil(((strtotime($row['finish_time']) - strtotime($row['start_time'])) / 3600));
                        if ($duration < 1) {
                            $duration = 1;
                        }
                        $fee = $duration * $row['cost_per_hour'];
                        echo "<tr>
                            <td>{$row['pid']}</td>
                            <td>{$row['location']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['start_time']}</td>
                            <td>{$row['finish_time']}</td>
                            <td>{$row['cost_per_hour']}</td>
                            <td>$duration</td>
                            <td>$fee</td>
                            ";
                        echo "</tr>";
                    }
                    echo "</table>";


                } else {
                    echo "You don't have any parking history in Easy Parking.<br>";
                }
            } catch (mysqli_sql_exception $e) {
                die($e->getCode() . " : " . $e->getMessage());
            }
        }

    }






    class Administrator extends Role
    {
        protected $conn;
        public function __construct($conn, $uid, $type)
        {
            parent::__construct($conn, $uid, $type);
        }
        public function listAll($conn)
        {
            $sql = "select *
                    from ParkingLocation
                    ";

            try {
                $result = $conn->query($sql);
                echo "<table class='search'>
                        <th>Parking ID</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Capability</th>"
                ;
                while ($row = $result->fetch_assoc()) {

                    echo "<tr>
                            <td>{$row['pid']}</td>
                            <td>{$row['location']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['parking_space']}</td>
                            ";

                    echo "</tr>";

                }

                echo "</table>";
            } catch (mysqli_sql_exception $e) {
                die($e->getCode() . " : " . $e->getMessage());
            }

        }
        public function listAvailable($conn)
        {
            $sql = "select ParkingLocation.pid as lpid,location,description, parking_space,count(Parking.pid) as pnum 
                    from ParkingLocation
                    left join Parking on ParkingLocation.pid =Parking.pid and finish_time is null
                    group by lpid,location,description,parking_space
                    having parking_space>pnum
                    ";

            try {
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<table class='search'>
                        <th>Parking ID</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Capacity</th>
                        <th>Available Spaces</th>"
                    ;
                    while ($row = $result->fetch_assoc()) {
                        $availableSpaces = $row['parking_space'] - $row['pnum'];
                        echo "<tr>
                            <td>{$row['lpid']}</td>
                            <td>{$row['location']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['parking_space']}</td>
                            <td>$availableSpaces</td>
                            ";

                        echo "</tr>";

                    }

                    echo "</table>";
                } else {
                    echo "currently no available parking locations <br>";
                }

            } catch (mysqli_sql_exception $e) {
                die($e->getCode() . " : " . $e->getMessage());
            }

        }
        public function listFull($conn)
        {
            $sql = "select ParkingLocation.pid as lpid,location,description, parking_space,count(Parking.pid) as pnum 
                    from ParkingLocation
                    left join Parking on ParkingLocation.pid =Parking.pid and finish_time is null
                    group by lpid,location,description,parking_space
                    having parking_space<=pnum
                    ";

            try {
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<table class='search'>
                        <th>Parking ID</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Capacity</th>
                        <th>Available Spaces</th>"
                    ;
                    while ($row = $result->fetch_assoc()) {
                        $availableSpaces = $row['parking_space'] - $row['pnum'];
                        if ($availableSpaces < 0) {
                            $availableSpaces = 0;
                        }
                        echo "<tr>
                            <td>{$row['lpid']}</td>
                            <td>{$row['location']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['parking_space']}</td>
                            <td>$availableSpaces</td>
                            ";

                        echo "</tr>";

                    }

                    echo "</table>";
                } else {
                    echo "currently no full parking locations <br>";
                }

            } catch (mysqli_sql_exception $e) {
                die($e->getCode() . " : " . $e->getMessage());
            }

        }


        public function displayParkingLocation($conn)
        {
            $selectSql = "select * from ParkingLocation";
            try {
                $result = $conn->query($selectSql);
                echo "<table class='search'>
                     
                        <th>Parking ID</th>
                        <th>Parking Location</th>
                        <th>Parking Description</th>
                        <th>Parking Capacity</th>
                        <th>Cost Per Hour</th>
                        <th>Action</th>
                        "
                ;
                while ($row = $result->fetch_assoc()) {

                    echo "<tr>
                            <td>{$row['pid']}</td>
                            <td>{$row['location']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['parking_space']}</td>
                            <td>{$row['cost_per_hour']}</td>  
                            <td><a href='administratorFunction.php?editPid={$row['pid']}'>Edit</a></td>  
                            ";

                    echo "</tr>";

                }

                echo "</table>";
            } catch (mysqli_sql_exception $e) {
                die($e->getCode() . " : " . $e->getMessage());
            }

        }


        public function updateForm($conn, $editPid)
        {
            $selectSql = "select * from ParkingLocation
                        where pid='$editPid'";
            try {
                $result = $conn->query($selectSql);
                echo "<form action='administratorFunction.php' method='post'><table>";
                while ($row = $result->fetch_assoc()) {
                    echo "
                            <tr><td>Parking ID: <input type='text' name='pid' value='{$row['pid']}' readonly></td></tr>
                            <tr><td>Parking Location: <input type='text' name='location' value='{$row['location']}'></td></tr>
                            <tr><td>Parking Description: <input type='text'  name='description' value='{$row['description']}'></td></tr>
                            <tr><td>Parking Capacity: <input type='number' min=0 step=1 name='capacity' value='{$row['parking_space']}'></td></tr>
                            <tr><td>Cost Per Hour: <input type='number' min=0 step=1 name='cost_per_hour' value='{$row['cost_per_hour']}'></td></tr>       
                            <tr><td> <input type='submit' name='update' value='update'>
                            
                            ";

                }
                echo "</table></form>";

            } catch (mysqli_sql_exception $e) {
                die($e->getCode() . " : " . $e->getMessage());
            }

        }

        public function updateParkingLocation($conn)
        {
            $pid = stripslashes(trim($_POST['pid']));
            $location = stripslashes(trim($_POST['location']));
            $description = stripslashes(trim($_POST['description']));
            $capacity = stripslashes(trim($_POST['capacity']));
            $cost_per_hour = stripslashes(trim($_POST['cost_per_hour']));
            if (strlen($location) > 0 && strlen($description) > 0 && strlen($capacity) > 0 && strlen($cost_per_hour) > 0) {

                $updateSql = "update ParkingLocation set
                                location='$location',
                                description='$description',
                                parking_space='$capacity',
                                cost_per_hour='$cost_per_hour'
                                where pid='$pid'
                            ";

                try {
                    $result = $conn->query($updateSql);
                    if ($conn->affected_rows > 0) {
                        echo "information about parking location $pid has been successfully updated <br>";
                    } else {
                        echo "nothing updated <br>";
                    }

                } catch (mysqli_sql_exception $e) {
                    die($e->getCode() . " : " . $e->getMessage());
                }

            } else {
                echo "you have empty field, please fill out the form <br>";
            }


        }

        public function insertForm($conn)
        {
            echo "<form action='administratorFunction.php' method='post'><table>";

            echo "
                            <tr><td>Parking Location: <input type='text' name='location'></td></tr>
                            <tr><td>Parking Description: <input type='text'  name='description'></td></tr>
                            <tr><td>Parking Capacity: <input type='number' min=0 step=1 name='capacity'></td></tr>
                            <tr><td>Cost Per Hour: <input type='number'  min=0 step=1  name='cost_per_hour'></td></tr>       
                            <tr><td> <input type='submit' name='insert' value='insert'>                 
                            ";
            echo "</table></form>";
        }


        public function insertParkingLocation($conn)
        {
            //get the current maximum parking id
            $maxIdSql = "select max(pid) as max_pid from ParkingLocation";
            try {
                $maxResult = $conn->query($maxIdSql);
                $row = $maxResult->fetch_assoc();
                $maxPid = $row['max_pid'];
                // generate new pid
                if ($maxPid) {
                    $num = (int) substr($maxPid, 2) + 1;
                    $pid = 'PL' . str_pad($num, 3, '0', STR_PAD_LEFT);
                } else {
                    $pid = 'PL001';
                }


            } catch (mysqli_sql_exception $e) {
                die($e->getCode() . " : " . $e->getMessage());
            }
            $location = stripslashes(trim($_POST['location']));
            $description = stripslashes(trim($_POST['description']));
            $capacity = stripslashes(trim($_POST['capacity']));
            $cost_per_hour = stripslashes(trim($_POST['cost_per_hour']));
            if (strlen($location) > 0 && strlen($description) > 0 && strlen($capacity) > 0 && strlen($cost_per_hour) > 0) {
                $insertSql = "insert into ParkingLocation (pid, location, description, parking_space, cost_per_hour) values
                    ('$pid','$location','$description','$capacity','$cost_per_hour')";

                try {
                    $result = $conn->query($insertSql);
                    if ($conn->affected_rows > 0) {
                        echo "$pid has been successfully inserted <br>";
                    } else {
                        echo "nothing inserted <br>";
                    }

                } catch (mysqli_sql_exception $e) {
                    die($e->getCode() . " : " . $e->getMessage());
                }

            } else {
                echo "you have empty field, please fill out the form <br>";
            }

        }

        public function listAllUser($conn)
        {
            $sql = "select *
                    from User
                    where type='user'
                    ";

            try {
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<table class='search'>
                        <th>User ID</th>
                        <th>name</th>
                        <th>surname</th>
                        <th>phone</th>
                        <th>email</th>
                        <th>Action</th>"
                    ;
                    while ($row = $result->fetch_assoc()) {

                        echo "<tr>
                            <td>{$row['uid']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['surname']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['email']}</td>
                            <td><a href='administratorFunction.php?checkinuid={$row['uid']}'>check in</td>
                            ";

                        echo "</tr>";

                    }

                    echo "</table>";
                } else {
                    echo "currently no users in Easy Parking <br>";
                }

            } catch (mysqli_sql_exception $e) {
                die($e->getCode() . " : " . $e->getMessage());
            }




        }

        public function checkInForm($conn, $checkinuid)
        {
            echo ' <form action="administratorFunction.php" method="post">
        <table>
            <tr>
                <td><input type="text" name="checkinpid" placeholder="Enter the Parking ID"></td>
                 <td><input type="hidden" name="checkinuid" value="' . htmlspecialchars($checkinuid, ENT_QUOTES, 'UTF-8') . '"></td>
                <td><input type="submit" name="checkin" value="check in">
                </td>
            </tr>
        </table>
    </form>';
        }
        public function adminCheckIn($conn)
        {

            $checkinpid = stripslashes(trim($_POST['checkinpid']));
            $checkinuid = stripslashes(trim($_POST['checkinuid']));
            $startTime = date('Y-m-d H:i:s');
            if (strlen($checkinpid) > 0) {
                //check parking id valid
                $queryParkingLocation = "select * from ParkingLocation";
                try {
                    $locationResult = $conn->query($queryParkingLocation);
                    $validParkingId = false;
                    while ($row = $locationResult->fetch_assoc()) {
                        if ($row['pid'] == $checkinpid) {
                            $validParkingId = true;
                            //check whether user currently parking
                            $queryParkingExisting = "select * from Parking where uid='$checkinuid' and pid='$checkinpid' and finish_time is null";
                            try {
                                $parkingExisting = $conn->query($queryParkingExisting);
                                if ($parkingExisting->num_rows > 0) {
                                    echo "this user is currently parking in this location <br>";
                                    return;
                                }
                            } catch (mysqli_sql_exception $e) {
                                die($e->getCode() . " : " . $e->getMessage());
                            }

                            //check capacity
                            $queryParkingCapacity = "select Parking.pid as ppid, parking_space,count(*) as current_num from Parking
                                   join ParkingLocation on Parking.pid=ParkingLocation.pid and finish_time is null
                                   where Parking.pid='$checkinpid'
                                   group by Parking.pid,parking_space";
                            try {
                                $parkingCapacity = $conn->query($queryParkingCapacity);
                                while ($row = $parkingCapacity->fetch_assoc()) {
                                    $capacity = $row['parking_space'] - $row['current_num'];
                                    if ($capacity <= 0) {
                                        echo "parking is full, change to another location <br>";
                                        return;
                                    }

                                }

                            } catch (mysqli_sql_exception $e) {
                                die($e->getCode() . " : " . $e->getMessage());
                            }

                            $insertSql = "insert into Parking (uid,pid,start_time) values
         ('$checkinuid','$checkinpid','$startTime')
                     ";
                            try {
                                $result = $conn->query($insertSql);
                                if ($conn->affected_rows > 0) {
                                    echo "You have been successfully checked in user $checkinuid, in parking $checkinpid, parking starts at $startTime<br>";
                                } else {
                                    echo "failed to check in user $checkinuid, in parking $checkinpid, please try again <br>";
                                }



                            } catch (mysqli_sql_exception $e) {
                                die($e->getCode() . " : " . $e->getMessage());
                            }

                            break;
                        }
                    }
                    if ($validParkingId === false) {
                        echo "invalid parking ID, please try again <br>";
                    }

                } catch (mysqli_sql_exception $e) {
                    die($e->getCode() . " : " . $e->getMessage());
                }

            } else {
                echo "please fill out the form <br>";
            }



        }





        public function listCurrentUser($conn)
        {
            $sql = "select * from Parking
                    join User on Parking.uid =User.uid
                    join ParkingLocation on Parking.pid=ParkingLocation.pid
                    where finish_time is null
                    ";

            try {
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<form action='administratorFunction.php' method='post'>
                <table class='search'>
                        <th>User ID</th>
                        <th>name</th>
                        <th>surname</th>
                        <th>phone</th>
                        <th>email</th>
                        <th>Parking ID</th>
                        <th>Parking Location</th>
                        <th>Start Time</th>
                        <th>Cost Per Hour</th>
                        <th>Action</th>
                        "
                    ;
                    while ($row = $result->fetch_assoc()) {

                        echo "<tr>
                            <td>{$row['uid']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['surname']}</td>
                             <td>{$row['phone']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['pid']}</td>
                            <td>{$row['location']}</td>
                            <td>{$row['start_time']}</td>
                            <td>{$row['cost_per_hour']}</td>
                          <td><a href='administratorFunction.php?deluid={$row['uid']}&delpid={$row['pid']}'>check
    out</a></td>

    ";

                        echo "</tr>";

                    }

                    echo "</table>
    </form>";
                } else {
                    echo "no currently checked-in users <br>";
                }
            } catch (mysqli_sql_exception $e) {
                die($e->getCode() . " : " . $e->getMessage());
            }


        }


        public function adminCheckOut($conn, $deluid, $delpid)
        {
            $finishTime = date('Y-m-d H:i:s');
            $updateSql = "update Parking
            set finish_time='$finishTime'
            where pid='$delpid' and uid='$deluid'";
            try {
                $conn->query($updateSql);
                if ($conn->affected_rows > 0) {
                    echo "check out successful, parking finishes at $finishTime <br>";
                } else {
                    echo "Check out failed. Please try again.<br>"
                    ;
                }
            } catch (mysqli_sql_exception $e) {
                die($e->getCode() . " : " . $e->getMessage());
            }

        }






    }


    $user = new User($conn, $uid, $type);
    $administrator = new Administrator($conn, $uid, $type);
    ?>
</body>

</html>