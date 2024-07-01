<?php
$serverName = "localhost:3306";
$uname = "root";
$serverPassword = "ClaireWu0207*";
$createDB = "create database if not exists ClaireEasyParking";

try {
    $conn = new mysqli($serverName, $uname, $serverPassword);
    // echo "connected <br>";
    $conn->query($createDB);
    // echo "db created  <br>";
    $conn->select_db("ClaireEasyParking");
    // echo "db selected  <br>";
    include_once ("createTable.php");
    // echo "table created";
} catch (mysqli_sql_exception $e) {
    die($e->getMessage() . " : " . $e->getMessage());
}


?>