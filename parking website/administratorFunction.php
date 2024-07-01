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
    <h1>Administrator Functions</h1>
    <hr>

    <h2>Display Parking Location Info Section</h2>
    <form action="administratorFunction.php" method="post">
        <table>
            <tr>
                <td><input type="submit" name="listAll" value="List All Parking Locations"></td>
                <td><input type="submit" name="listAvailable" value="List Available Parking Locations"></td>
                <td><input type="submit" name="listFull" value="List Full Parking Locations"></td>
            </tr>
        </table>
    </form>
    <?php
    if (isset($_POST['listAll'])) {
        $administrator->listAll($conn);
    }
    if (isset($_POST['listAvailable'])) {
        $administrator->listAvailable($conn);
    }
    if (isset($_POST['listFull'])) {
        $administrator->listFull($conn);
    }

    ?>


    <hr>

    <h2>Search Section</h2>
    <form action="administratorFunction.php" method="post">
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
        $administrator->searchParkingLocation($conn);
    }
    ?>
    <hr>
    <h2>Insert/Edit Parking Location Section</h2>
    <form action="administratorFunction.php" method="post">
        <table>
            <tr>
                <td><input type="submit" name="listToEdit" value="List Parking Locations to Edit">
                <td><input type="submit" name="insertForm" value="Insert Parking Locations">
            </tr>
        </table>
    </form>
    <?php
    if (isset($_POST['listToEdit'])) {
        $administrator->displayParkingLocation($conn);
    }

    if (isset($_GET['editPid'])) {
        $administrator->updateForm($conn, $_GET['editPid']);
    }

    if (isset($_POST['update'])) {
        $administrator->updateParkingLocation($conn);
    }
    if (isset($_POST['insertForm'])) {
        $administrator->insertForm($conn);
    }
    if (isset($_POST['insert'])) {
        $administrator->insertParkingLocation($conn);
    }

    ?>
    <hr>
    <h2>Check-in/Check-out User Section</h2>
    <form action="administratorFunction.php" method="post">
        <table>
            <tr>
                <td><input type="submit" name="listAllUser" value="List All Users to Check-in"></td>
                <td><input type="submit" name="listCurrentUser" value="List Currently Checked-in Users to Check-out">
                </td>
            </tr>
        </table>
    </form>

    <?php
    if (isset($_POST['listAllUser'])) {
        $administrator->listAllUser($conn);
    }
    if (isset($_GET['checkinuid'])) {
        $administrator->checkInForm($conn, $_GET['checkinuid']);
    }
    if (isset($_POST['checkin'])) {
        $administrator->adminCheckIn($conn);
    }

    if (isset($_POST['listCurrentUser'])) {
        $administrator->listCurrentUser($conn);
    }
    if (isset($_GET['deluid']) && isset($_GET['delpid'])) {
        $administrator->adminCheckOut($conn, $_GET['deluid'], $_GET['delpid']);
    }


    ?>
</body>
</body>

</html>