<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="common.css" />
</head>
<?php
session_start();
$_SESSION = array();
session_destroy();
include_once("mysqlConnection.php");
?>

<body>
    <h1>Welcome to Fitness Center</h1>
    <form action="login.php" method="post">
        <table>
            <tr>
                <td>First name:</td>
                <td><input type="text" name="fname" /></td>
            </tr>
            <tr>
                <td>Last name:</td>
                <td><input type="text" name="lname" /></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="psw" /></td>
            </tr>
            <tr>
                <td><input type="submit" name="logIn" value="Log in" /></td>
                <td><input type="reset" name="reset" value="Clear" /></td>
            </tr>
        </table>
    </form>
    Don't have an account? <a href="signup.php">Sign up</a>


</body>

</html>