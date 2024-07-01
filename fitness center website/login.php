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
    session_start();
    include_once ("mysqlConnection.php");

    ###############verify user log in info ########################################
    // check empty
    if (!empty($_POST["fname"]) && !empty($_POST["lname"]) && !empty($_POST["psw"])) {
        //get the user info
        $fname = stripslashes($_POST["fname"]);
        $lname = stripslashes($_POST["lname"]);
        $psw = md5(stripslashes($_POST["psw"]));
        // retrieve data from Member table to validate info
        $selectUserInfo = "select * from Member where firstname='$fname' and lastname='$lname'";
        try {
            $userInfo = $conn->query($selectUserInfo);
            // ensure userInfo contains data
            if ($userInfo && $userInfo->num_rows > 0) {
                $user = $userInfo->fetch_assoc();
                // validate user name and password
                if ($fname == $user['firstname'] && $lname == $user['lastname'] && $psw == $user['password']) {
                    // if user successfully log in then store the user log in info
                    $_SESSION['mid'] = $user['id'];
                    $_SESSION['firstname'] = $user['firstname'];

                    echo "Welcome {$_SESSION['firstname']}! <br>";
                    echo "click <a href='Queries.php'>here</a> to see what we got :)";

                } else {
                    echo "password and username are not match, <a href='./index.php'>try again</a>";
                }
            } else {
                echo "we cannot find your membership info, <a href='./index.php'>try again</a>";
            }
        } catch (mysqli_sql_exception $e) {
            die($e->getCode() . ":" . $e->getMessage());
        }

    } else {
        echo "please fill out the form, <a href='./index.php'>try again</a><br>";
    }



    ?>


    </form>

</body>

</html>