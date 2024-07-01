<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Create an account</h1>
    <form action="signup.php" method="post">
        <table>
            <tr>
                <td>Enter your first name: </td>
                <td><input type="text" name="fname"></td>
            </tr>
            <tr>
                <td>Enter your surname: </td>
                <td><input type="text" name="sname"></td>
            </tr>
            <tr>
                <td>Enter your phone number: </td>
                <td><input type="text" name="phone"></td>
            </tr>
            <tr>
                <td>Enter your email: </td>
                <td><input type="text" name="email"></td>
            </tr>
            <tr>
                <td>Set your password: </td>
                <td><input type="password" name="psw1"></td>
            </tr>
            <tr>
                <td>Confirm your password: </td>
                <td><input type="password" name="psw2"></td>
            </tr>
            <tr>
                <td>Register type: </td>
                <td><select name="type" id="">
                        <option value="user">user</option>
                        <option value="administrator">administrator</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><input type="submit" name="signup" value="Sign Up"></td>
                <td><input type="reset" name="reset" value="Clear"></td>
            </tr>



        </table>
    </form>
    <?php
    include_once ("mysqlConnection.php");
    if (isset($_POST["signup"])) {
        //get the info 
        $fname = stripslashes(trim($_POST["fname"]));
        $sname = stripslashes(trim($_POST["sname"]));
        $phone = stripslashes(trim($_POST["phone"]));
        $email = stripslashes(trim($_POST["email"]));
        $psw1 = stripslashes(trim($_POST["psw1"]));
        $psw2 = stripslashes(trim($_POST["psw2"]));
        $type = stripslashes(trim($_POST["type"]));
        $checkEmpty = strlen($fname) === 0 || strlen($sname) === 0 || strlen($phone) === 0 || strlen($email) === 0 || strlen($psw1) === 0 || strlen($psw2) === 0 || strlen($type) === 0;
        $checkPhone = preg_match("/^04[0-9]{8}$/", $phone);
        $checkEmail = preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)*(\.[a-zA-Z]{2,})$/", $email);
        $checkPsw = strcmp($psw1, $psw2);

        //check empty field
        if ($checkEmpty) {
            echo "please fill out the form <br>";
        } else {
            //validate phone
            if (!$checkPhone) {
                echo "invalid phone number <br>";
                //validate email
            }
            if (!$checkEmail) {
                echo "invalid email <br>";
            }
            //validate password
            if ($checkPsw != 0) {
                echo "password does not match <br>";
            }


            if ($checkPhone && $checkEmail && $checkPsw === 0) {
                // check phone & email existing
                $query = "select phone, email from User where phone='$phone' or email='$email'";
                try {
                    $result = $conn->query($query);
                    if ($result->num_rows > 0) {
                        echo "you already have an account in Easy Parking <br>";
                        return;
                    } else {
                        $psw1 = md5($psw1);
                        $insertUser = "insert into User (name,surname,phone,email,password,type) values
                ('$fname','$sname','$phone','$email','$psw1','$type')";

                        $conn->query($insertUser);
                        echo " <tr>
                        <td>You are a member now! <a href='index.php'>Login</a></td>
                    </tr>";

                    }
                } catch (mysqli_sql_exception $e) {
                    die($e->getCode() . " : " . $e->getMessage());
                }



            }

        }




    }

    $conn->close();
    ?>

</body>

</html>