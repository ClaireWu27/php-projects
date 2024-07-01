<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once ("mysqlConnection.php");
if (isset($_POST["login"])) {
    $phone = stripslashes(trim($_POST["phone"]));
    $psw = stripslashes(trim($_POST["psw"]));
    $checkEmpty = strlen($phone) === 0 || strlen($psw) === 0;
    //check empty
    if ($checkEmpty === false) {
        //check whether user info exists
        $selectUserInfo = "select* from User where phone={$phone}";
        try {
            $result = $conn->query($selectUserInfo);
            if ($result->num_rows > 0) {
                //check password
                $psw = md5($psw);
                while ($row = $result->fetch_assoc()) {
                    if ($row['password'] === $psw) {
                        //save user id to session
                        $_SESSION['uid'] = $row['uid'];
                        //save user type
                        $_SESSION['type'] = $row['type'];

                        if ($row['type'] == 'user') {
                            include_once ("userFunction.php");
                        } else {
                            include_once ("administratorFunction.php");
                        }
                    } else {
                        echo "password does not match, <a href='index.php'>try again</a> <br>";
                    }
                }

            } else {
                echo "we cannot find your member info,  <a href='index.php'>try again</a> <br><br>";
            }

        } catch (mysqli_sql_exception $e) {
            die($e->getCode() . " : " . $e->getMessage());
        }
    } else {
        echo "please fill out the form,  <a href='index.php'>try again</a><br>";
    }
}
$conn->close();
?>