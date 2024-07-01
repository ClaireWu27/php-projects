<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="common.css">
    <style>
        .logout {
            position: sticky;
            top: 10px;
            right: 10px;
            color: red;
            margin-left: 1000px;
            text-decoration: none;
            z-index: 1000;
            display: inline-block;
        }
    </style>
</head>

<body>
    <a class='logout' href='index.php'>Log out</a>
    <?php
    session_start();
    include_once ("mysqlConnection.php");
    $mid = $_SESSION['mid'];

    ######################Full Class################################ -
    echo ' <h1>Full Class List</h1>
    <form action="Queries.php" method="post">
        <table>
            <tr>
                <td><input type="submit" name="viewFullClass" value="View Full Class List"></td>
            </tr>
        </table>
    </form>';

    if (isset($_POST["viewFullClass"])) {
        include ("fullClass.php");
    }


    ######################Enrolment Management################################ 
    echo '<hr>
    <h1>Enrolment Management</h1>
    <form action="Queries.php" method="post">
        <table>
            <tr>
                <td><input type="text" name="cid" placeholder="Enter Class ID"></td>
                <td><input type="submit" name="addEnrol" value="Add Enrollment"></td>
            </tr>
            <tr>
                <td><input type="submit" name="enrolled" value="My Enrolled Classes"></td>
            </tr>
        </table>
    </form>';

    if (isset($_POST["addEnrol"])) {
        include ("Enrolment.php");
    }


    if (isset($_POST["enrolled"])) {
        include ("Enrolment.php");

    }
    if (isset($_GET['cid'])) {
        include ("Withdraw.php");
    }





    #########################Query section################################### 
    echo ' <hr>
    <h1>Find Info</h1>
    <form action="Queries.php" method="post">
        <table>

            <tr>
                <td>a. The ID and title of classes offered by a specific facility</td>
            </tr>
            <tr>

                <td><select name="selectFacility">
                        <option value="FCNorth">FCNorth</option>
                        <option value="FCSouth">FCSouth</option>
                    </select></td>
                <td><input type="submit" name="findFacilityClass" value="Find Facility Class"></td>
            <tr>
            <tr>
                <td>b.The name of the members enrolled in a specific class</td>
            </tr>
            <td><input type="text" name="cidClassmate" placeholder="Enter Class ID"></td>
            <td><input type="submit" name="findClassmate" value="Find Classmate"></td>
            <tr>
                <td>c. The name of the instructors in a specific facility</td>
            </tr>
            <tr>
                <td><select name="facilityInstructor">
                        <option value="FCNorth">FCNorth</option>
                        <option value="FCSouth">FCSouth</option>
                    </select></td>
                <td><input type="submit" name="findFacilityInstructor" value="Find Facility Instructor"></td>
            </tr>
            <tr>
                <td>d.The number of members in each class</td>
            </tr>
            <tr>
                <td><input type="text" name="numofMemberCid" placeholder="Enter Class ID"></td>
                <td><input type="submit" name="findClassCapability" value="Find Class Capability"></td>
            <tr>
            <tr>
                <td>e.The memberID and name of members who have classes with a specific instructor </td>
            </tr>
            <tr>
                <td><input type="text" name="instructorId" placeholder="Enter Instructor ID"></td>
                <td><input type="submit" name="findInstructorStudent" value="Find Instructor Student"></td>
            <tr>
            <tr>
            <tr>
                <td>f. The number of members who have enrolled in the classes offered by each facility
                </td>
            </tr>
            <td><select name="findFacility">
                    <option value="FCNorth">FCNorth</option>
                    <option value="FCSouth">FCSouth</option>
                </select></td>
            <td><input type="submit" name="findFacilityCapability" value="Find Facility Capability"></td>
            <tr>
        </table>';


    if (isset($_POST["findFacilityClass"]) && $_POST["selectFacility"]) {
        include ("selectFacility.php");

    }
    if (isset($_POST["findFacilityInstructor"]) && $_POST["facilityInstructor"]) {
        include ("facilityInstructor.php");

    }
    if (isset($_POST["findClassmate"]) && $_POST["cidClassmate"]) {
        include ("findClassmate.php");
    }
    if (isset($_POST["findClassCapability"]) && $_POST["numofMemberCid"]) {
        include ("findClassCapability.php");
    }
    if (isset($_POST["findInstructorStudent"]) && $_POST["instructorId"]) {
        include ("findInstructorStudent.php");
    }
    if (isset($_POST["findFacilityCapability"]) && $_POST["findFacility"]) {
        include ("facilityEnrolNum.php");
    }
    ?>

</body>

</html>