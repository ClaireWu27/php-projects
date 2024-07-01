<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="common.css">
    <style>
        .enrolTable th,
        .enrolTable td {
            border: 1px solid black;
        }
    </style>

</head>


<body>

    <?php
    #############view current user enrollment#############
    if (isset($_POST['enrolled'])) {
        try {
            $selectClassInfo = "select *
                      from Class 
                      join EnrolWithdraw on Class.id=EnrolWithdraw.cid
                      where mid='{$_SESSION['mid']}'";
            $result = $conn->query($selectClassInfo);
            if ($result->num_rows > 0) {

                echo "
        <table class='enrolTable'>
            <th>Class ID</th>
            <th>Title</th>
            <th>Type</th>
            <th>Facility</th>
            <th>Action</th>
          
   ";

                while ($row = $result->fetch_assoc()) {

                    echo " 
            <tr>
            <td> {$row['cid']}</td>
            <td>{$row['title']}</td>
            <td>{$row['type']}</td>
            <td>{$row['facility']}</td>  
           <td><a href='Queries.php?cid={$row['cid']}'>Withdraw</a></td>
            </tr>";
                }
                echo " </table>
        </form>";
            } else {
                echo "you haven't enrolled in any class yet";
            }
        } catch (mysqli_sql_exception $e) {
            die($e->getCode() . ":" . $e->getMessage());

        }
    }





    #################### add enrollment###############################
    if (isset($_POST["addEnrol"])) {
        $cid = stripslashes($_POST['cid']);

        // get current user enrolled classes in an array
        $checkEnrol = "select cid from EnrolWithdraw where mid='$mid' and cid = '$cid'";
        $enrolExist = $conn->query($checkEnrol);
        if ($enrolExist->num_rows > 0) {
            echo 'you already enrolled this class <br>';
        } else {
            $checkCapacity = "select Class.id as class_id, 
                        type,
                    count(EnrolWithdraw.cid) as enrolNum
                    from Class
                    left join EnrolWithdraw on Class.id=EnrolWithdraw.cid
                     where Class.id = '$cid'
                    group by Class.id, type
                   ";
            $result = $conn->query($checkCapacity);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($row['type'] == 'group' && $row['enrolNum'] >= 6) {
                        echo 'class is full, group class maximum capacity is 6 <br>';
                    } else if ($row['type'] == 'individual' && $row['enrolNum'] >= 1) {
                        echo 'class is full, individual class maximum capacity is 1 <br>';

                    } else {
                        try {
                            $addEnrol = "insert into EnrolWithdraw(cid,mid)
                                values('$cid','$mid')";
                            $conn->query($addEnrol);
                            echo "class $cid successfully enrolled <br>";
                        } catch (mysqli_sql_exception $e) {
                            die($e->getCode() . ":" . $e->getMessage());

                        }

                    }
                }
            } else {
                echo "Invalid class ID.<br>";
            }





        }
    }



    ?>


</body>

</html>