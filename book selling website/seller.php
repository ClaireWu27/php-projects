<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>

<body>
    <?php
    //define the file to store selling info
    $fileName = "./TextDirectory.txt";
    //phone validation
    function checkPhone($phone)
    {
        return (preg_match('/^(\+61|0)4\d{8}$/', $phone));
    }
    //email validation
    function checkEmail($email)
    {
        return (preg_match('/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)*(\.[a-zA-Z]{2,})$/', $email));
    }
    //TNo validation
    function checkTno($tno)
    {
        return (preg_match('/^\d{4}-[a-zA-Z]{2}$/', $tno));
    }
    //if seller submit
    if (isset($_POST['submit'])) {
        //check empty field and validation
        if (
            !empty($_POST['sname']) &&
            !empty($_POST['sname']) &&
            !empty($_POST['phone']) &&
            !empty($_POST['email']) &&
            !empty($_POST['tno']) &&
            !empty($_POST['title']) &&
            !empty($_POST['author']) &&
            !empty($_POST['publisher']) &&
            !empty($_POST['year']) &&
            !empty($_POST['description'])
        ) {//if all the fields are filled out, retrieve data
            $sname = addslashes($_POST['sname']);
            $phone = addslashes($_POST['phone']);
            $email = addslashes($_POST['email']);
            $tno = addslashes($_POST['tno']);
            $title = addslashes($_POST['title']);
            $author = addslashes($_POST['author']);
            $publisher = addslashes($_POST['publisher']);
            $year = addslashes($_POST['year']);
            $description = addslashes($_POST['description']);
            // Replace any '，；' characters with '-' characters
            $sname = str_replace(",", "-", $sname);
            $phone = str_replace(",", "-", $phone);
            $email = str_replace(",", "-", $email);
            $title = str_replace(",", "-", $title);
            $author = str_replace(",", "-", $author);
            $publisher = str_replace(",", "-", $publisher);
            $year = str_replace(",", "-", $year);
            $description = str_replace(",", "-", $description);
            $sname = str_replace(";", "-", $sname);
            $phone = str_replace(";", "-", $phone);
            $email = str_replace(";", "-", $email);
            $title = str_replace(";", "-", $title);
            $author = str_replace(";", "-", $author);
            $publisher = str_replace(";", "-", $publisher);
            $year = str_replace(";", "-", $year);
            $description = str_replace(";", "-", $description);
            $sname = str_replace("\n", "-", $sname);
            $phone = str_replace("\n", "-", $phone);
            $email = str_replace("\n", "-", $email);
            $title = str_replace("\n", "-", $title);
            $author = str_replace("\n", "-", $author);
            $publisher = str_replace("\n", "-", $publisher);
            $year = str_replace("\n", "-", $year);
            $description = str_replace("\n", "-", $description);

            // if satisfy the validation
            if (
                checkPhone($_POST['phone']) &&
                checkEmail($_POST['email']) &&
                checkTno($_POST['tno'])
            ) {
                //check whether the Tno exists
                //read line by line
                $lines = file($fileName);
                $tnoExisting = false;
                foreach ($lines as $line) {
                    //split line info
                    $infoArray = explode(';', $line);
                    $tnoTexts = $infoArray[1];
                    $tnoTextsArray = explode(',', $tnoTexts);
                    $tnoContent = $tnoTextsArray[0];
                    if ($tnoContent == $tno) {
                        $tnoExisting = true;
                        echo "the textbook already existed,please try again.";
                        break;
                    }
                }
                if ($tnoExisting == false) {
                    $sellInfo = "$sname,$phone,$email;$tno,$title,$author;$publisher,$year,$description\n";
                    // contents successfully recorded into file
                    if (file_put_contents($fileName, $sellInfo, FILE_APPEND) > 0) {
                        echo "the selling info has been recorded.</a>";
                    }// contents failed recorded into file
                    else {
                        echo "error";
                    }
                }
            } else {
                if (!checkPhone($phone)) {
                    echo "phone number is invalid, please try again.<br>";
                }
                if (!checkEmail($email)) {
                    echo "email is invalid, please try again.<br>";
                }
                if (!checkTno($tno)) {
                    echo "TNo is invalid, please try again.<br>";
                }
            }
        }  // there is empty field
        else {
            echo "please fill out the form.";
        }
    }


    ?>
    <!-- display the selling info form -->
    <h1>Selling Information</h1>
    <form action="seller.php" method="post">
        <table>
            <tr>
                <td>name:</td>
                <td><input type="text" name="sname"></td>
            </tr>
            <tr>
                <td>phone:</td>
                <td><input type="text" name="phone"></td>
            </tr>
            <tr>
                <td>email:</td>
                <td><input type="text" name="email"></td>
            </tr>
            <tr>
                <td>TNo:</td>
                <td><input type="text" name="tno"></td>
            </tr>
            <tr>
                <td>title:</td>
                <td><input type="text" name="title"></td>
            </tr>
            <tr>
                <td>author:</td>
                <td><input type="text" name="author"></td>
            </tr>
            <tr>
                <td>publisher:</td>
                <td><input type="text" name="publisher"></td>
            </tr>
            <tr>
                <td>publishing year:</td>
                <td><input type="text" name="year"></td>
            </tr>
            <tr>
                <td>description:</td>
                <td><textarea name="description" cols="30" rows="5"></textarea></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" value="submit"></td>
                <td><input type="reset" name="reset" value="clear"></td>
            </tr>
        </table>
    </form>


</body>

</html>