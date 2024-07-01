<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    session_start();
    $_SESSION = array();
    session_destroy();
    include_once ("mysqlConnection.php");
    $conn->close();
    ?>

    <h1>Welcome to Easy Parking</h1>
    <form action="login.php" method="post">
        <table>
            <tr>
                <td>Enter your phone number: </td>
                <td><input type="text" name="phone"></td>
            </tr>

            <tr>
                <td>Enter your password: </td>
                <td><input type="password" name="psw"></td>
            </tr>
            <tr>
                <td><input type="submit" name="login" value="Login"></td>
                <td><input type="reset" name="reset" value="Clear"></td>
            </tr>
            <tr>
                <td>Don't have an account, <a href="signup.php">sign up</a></td>
            </tr>
        </table>
    </form>


</body>

</html>