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
    $facility = $_POST['selectFacility'];
    $selectFacility = "select *
                        from Class
                        where facility='$facility'";

    try {
        $result = $conn->query($selectFacility);
        echo "<table>
            <th>Class ID</th>
            <th>Title</th>
            <th>Facility</th>
            <th>Type</th>
            ";
        while ($row = $result->fetch_assoc()) {
            echo " 
                <tr>
                <td> {$row['id']}</td>
                <td>{$row['title']}</td>
                <td>{$row['facility']}</td>
                <td>{$row['type']}</td>
            </tr>";
        }
        echo " </table>";


    } catch (mysqli_sql_exception $e) {
        die($e->getCode() . ":" . $e->getMessage());
    }

    ?>
</body>

</html>