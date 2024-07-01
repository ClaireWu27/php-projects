<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>

<body>
    <?php
    //phone validation
    include_once ("detail.php");
    function checkPhone($phone)
    {
        return (preg_match('/^(\+61|0)4\d{8}$/', $phone));
    }
    //TNo validation
    function checkTno($tno)
    {
        return (preg_match('/^\d{4}-[a-zA-Z]{2}$/', $tno));
    }

    $fileName = "./TextDirectory.txt";
    $displayForm = true;
    $tnoExisting = false;
    // define the file to store interests 
    $fileInterest = "./BuyersEOI.txt";
    // if the buyer submit the form
    if (isset($_POST['submit'])) {
        //check empty field
        if (
            !empty(($_POST['bname'])) &&
            !empty(($_POST['bphone'])) &&
            !empty(($_POST['btno'])) &&
            !empty(($_POST['bprice']))
        ) {

            // if all fields are filled out,retrieve data
            $bname = addslashes($_POST['bname']);
            $bphone = addslashes($_POST['bphone']);
            $btno = addslashes($_POST['btno']);
            $bprice = addslashes($_POST['bprice']);
            //if satisfied validation
            if (checkPhone($bphone) && checkTno($btno)) {
                // //check whether the TNo exists
                $lines = file($fileName);
                foreach ($lines as $line) {
                    //split line info
                    $infoArray = explode(';', $line);
                    $tnoTexts = $infoArray[1];
                    $tnoTextsArray = explode(',', $tnoTexts);
                    $tnoContent = $tnoTextsArray[0];
                    $detailTexts = $infoArray[2];
                    $detailTextsArray = explode(',', $detailTexts);
                    $publisher = $detailTextsArray[0];
                    $year = $detailTextsArray[1];
                    $description = $detailTextsArray[2];
                    if ($tnoContent == $btno) {
                        $tnoExisting = true;
                        break;
                    }
                }

            }
            if ($tnoExisting) {
                $buyerInterest = "$bname,$bphone,$btno,$bprice\n";
                // contents successfully recorded into file
                if (file_put_contents($fileInterest, $buyerInterest, FILE_APPEND) > 0) {
                    echo "your interests have been expressed.<br>";
                    $displayForm = false;
                } else {
                    // contents failed recorded into file
                    echo "error.<br>";
                }

            } else {
                echo "TNo not exists.<br>";
            }

            if (!checkPhone($bphone)) {
                echo "phone number is invalid, please try again.<br>";
            }
            if (!checkTno($btno)) {
                echo "TNo is invalid, please try again.<br>";

            }
        } else {
            echo "please fill out the form.<br>";
        }

    }

    ?>
    <!-- display the buyer info form -->
    <form action="buyerInterest.php" method="post">
        <table>
            <tr>
                <td>buyer name:</td>
                <td><input type="text" name="bname"></td>
            </tr>
            <tr>
                <td>telephone number:</td>
                <td><input type="text" name="bphone"></td>
            </tr>
            <tr>
                <td>Tno:</td>
                <td><input type="text" name="btno"></td>
            </tr>
            <tr>
                <td>proposed price:</td>
                <td><input type="text" name="bprice"></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" value="submit"></td>
                <td><input type="reset" name="reset" value="clear"></td>
            </tr>
        </table>
    </form>

</body>

</html>