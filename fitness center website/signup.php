<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="common.css">
</head>

<body>
    <form action="signup.php" method="post">
        <table>
            <tr>
                <td>first name:</td>
                <td><input type="text" name="firstname"></td>
            </tr>
            <tr>
                <td>last name:</td>
                <td><input type="text" name="lastname"></td>
            </tr>
            <tr>
                <td>set password:</td>
                <td><input type="password" name="psw1"></td>
            </tr>
            <tr>
                <td>confirm password:</td>
                <td><input type="password" name="psw2"></td>
            </tr>
            <tr>
                <td><input type="submit" name="signUp" value="Sign Up"></td>
                <td><input type="reset" name="reset" value="Clear"></td>
            </tr>
        </table>
    </form>

    <?php
    include ("mysqlConnection.php");

    if (isset($_POST['signUp'])) {
        //check empty
        if (strlen(trim($_POST['firstname'])) && strlen(trim($_POST['lastname'])) && strlen(trim($_POST['psw1'])) && strlen(trim($_POST['psw2']))) {
            //get info
            $firstname = stripslashes($_POST['firstname']);
            $lastname = stripslashes($_POST['lastname']);
            $psw1 = stripslashes($_POST['psw1']);
            $psw2 = stripslashes($_POST['psw2']);
            //check password match
            if ($psw1 === $psw2) {
                // insert member
    
                $memberId = generateUniqueId('M');
                $psw = md5($psw1);
                $insertMemeber = "insert into Member (id,firstname,lastname,password)
                                  values ('$memberId','$firstname', '$lastname','$psw')";
                try {
                    $conn->query($insertMemeber);
                    // echo "inserted";
                    echo "You are a member of us now! <a href='./index.php'>Log in</a>";
                } catch (mysqli_sql_exception $e) {
                    die($e->getCode() . ":" . $e->getMessage());
                }
            } else {
                echo "password does not match, please try again<br>";
            }

        } else {
            echo "please fill out the form<br>";
        }
    }
    ?>
</body>

</html>