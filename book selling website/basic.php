<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<style>
    th,
    td {
        text-align: center;
    }
</style>

<body>
    <?php
    //file to retrieve data
    $fileName = "./TextDirectory.txt";
    $displayDetail = false;
    //read file line by line and split to basic info, detailed info
    $lines = file($fileName);
    echo "
         <table>
        <tr>
            <th>Tno</th>
            <th>title</th>
            <th>authors</th>
        </tr>";
    foreach ($lines as $line) {
        //split line info
        $infoArray = explode(";", $line);
        $basicInfo = $infoArray[1];
        //retrieve basic info   
        $basicSplit = explode(",", $basicInfo);
        $tno = $basicSplit[0];
        $title = $basicSplit[1];
        $author = $basicSplit[2];
        echo "
                <tr>
                    <td>$tno</td>
                    <td>$title</td>
                    <td>$author</td>
                </tr>";

    }
    echo " </table>";
    ?>
    <!-- display the search box -->
    <form action="basic.php" method="post">
        <table>
            <tr>
                <td>Enter Tno for Detailed Info:</td>
                <td><input type="text" name="search_tno"></td>
            </tr>
            <tr>
                <td><input type="submit" name="detail" value="search"></td>
                <td><input type="reset" name="reset" value="clear"></td>
            </tr>
        </table>
    </form>
    <?php
    if (isset($_POST['detail'])) {
        include ('detail.php');
    }

    ?>
</body>

</html>