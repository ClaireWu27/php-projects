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
    include_once ("mysqlConnection.php");
    $facility = $_POST['facilityInstructor'];
    $selectFacilityInstructor = "select *
                        from Instructor
                        where facility='$facility'";

    try {
        $result = $conn->query($selectFacilityInstructor);
        echo "<table>
            <th>Instructor First Name</th>
            <th>Instructor Last Name</th>
            <th>Facility</th>
            ";
        while ($row = $result->fetch_assoc()) {
            echo " 
                <tr>
                <td> {$row['firstname']}</td>
                <td>{$row['lastname']}</td>
                <td>{$row['facility']}</td>
            </tr>";
        }
        echo " </table>";


    } catch (mysqli_sql_exception $e) {
        die($e->getCode() . ":" . $e->getMessage());
    }
    ?>
</body>

</html>