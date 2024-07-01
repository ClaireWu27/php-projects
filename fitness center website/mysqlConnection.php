<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $serverName = "localhost:3306";
    $uname = "root";
    $password = "ClaireWu0207*";
    $createDB = "create database if not exists ClaireFitnessCenter";

    try {
        $conn = new mysqli($serverName, $uname, $password);
        $conn->query($createDB);
        // echo "db created";
        $conn->select_db("ClaireFitnessCenter");
        include ("generateIdFunction.php");
        include ("createTable.php");
        // echo "db selected";
    } catch (mysqli_sql_exception $e) {
        die($e->getCode() . ":" . $e->getMessage());
    }

    ?>


</body>

</html>