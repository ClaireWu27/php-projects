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
    include_once ("roleClass.php");
    include_once ("mysqlConnection.php");
    ?>
    <a href="index.php" class="logout">Logout</a>
    <h1> User Functions</h1>
    <p style="color:gray">Note:Parking time within 1 hour count as 1 hour, over 1 hour but less than 2 hours count as 2
        hours, and so
        on.</p>
    <hr>

    <h2>Available Parking Locations Section</h2>
    <form action="userFunction.php" method="post">
        <table>
            <tr>
                <td><input type="submit" name="listForUser" value="List Available Parking Locations"></td>
            </tr>
        </table>
    </form>
    <?php
    if (isset($_POST['listForUser'])) {
        $user->listForUser($conn);
    }
    ?>

    <hr>

    <h2>Search Section</h2>
    <form action="userFunction.php" method="post">
        <table>
            <tr>
                <td><input type="text" name="pid" placeholder="Enter Parking ID"></td>
                <td><input type="text" name="location" placeholder="Enter Parking Location">
                </td>
                <td><input type="text" name="desc" placeholder="Enter Parking Description"></td>
                <td><input type="submit" name="search" value="search">
            </tr>
        </table>
    </form>


    <?php
    if (isset($_POST['search'])) {
        $pid = stripslashes($_POST['pid']);
        $location = stripslashes($_POST['location']);
        $description = stripslashes($_POST['desc']);
        $user->searchParkingLocation($conn);
    }
    ?>
    <hr>

    <h2>Check-in & Check-out Section</h2>
    <form action="userFunction.php" method="post">
        <table>
            <tr>
                <td><input type="text" name="pid" placeholder="Enter the Parking ID"></td>
                <td><input type="submit" name="checkin" value="check in">
                </td>
            </tr>
        </table>
    </form>
    <?php
    if (isset($_POST['checkin'])) {
        $user->userCheckIn($conn, $uid);
    }
    ?>
    <form action="userFunction.php" method="post">
        <table>
            <tr>
                <td><input type="submit" name="currentParking" value="My Current Parking / Check-out">
                </td>
            </tr>
        </table>
    </form>

    <?php
    if (isset($_POST['currentParking'])) {
        $user->currentParking($conn, $uid);
    }
    if (isset($_GET['delpid'])) {
        $user->userCheckOut($conn, $uid);
    }

    ?>
    <hr>

    <h2>History Section</h2>
    <form action="userFunction.php" method="post">
        <table>
            <tr>
                <td><input type="submit" name="history" value="My Parking History">
                </td>
            </tr>
        </table>
    </form>
    <?php
    if (isset($_POST['history'])) {
        $user->parkingHistory($conn, $uid);
    }

    ?>


</body>

</html>