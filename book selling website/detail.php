<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    th,
    td {
        text-align: center;
    }
    </style>
</head>

<body>
    <?php
    include_once ('basic.php');
    if (isset($_POST['detail'])) {
        //check whether the TNo is empty
        if (!empty($_POST['search_tno'])) {
            $tno = addslashes($_POST['search_tno']);
            //check whether the TNo exists
            $lines = file($fileName);
            $tnoExisting = false;
            foreach ($lines as $line) {
                //split line info
                $infoArray = explode(';', $line);
                $tnoTexts = $infoArray[1];
                $tnoTextsArray = explode(',', $tnoTexts);
                //retrieve basic info
                $tnoContent = $tnoTextsArray[0];
                $titleContent = $tnoTextsArray[1];
                $authorContent = $tnoTextsArray[2];
                //retrieve detail info
                $detailTexts = $infoArray[2];
                $detailTextsArray = explode(',', $detailTexts);
                $publisher = $detailTextsArray[0];
                $year = $detailTextsArray[1];
                $description = $detailTextsArray[2];
                if ($tnoContent == $tno) {
                    $tnoExisting = true;
                    echo "<table>
                    <tr>
                    <th>TNo</th>
                    <th>title</th>
                    <th>author</th>
                    <th>publisher</th>
                    <th>publishing year</th>
                    <th>description</th>
                    </tr>
                    <tr>
                    <td>$tnoContent</td>
                    <td>$titleContent</td>
                    <td>$authorContent</td>
                    <td>$publisher</td>
                    <td>$year</td>
                    <td>$description</td>
                    <td> <form action='detail.php' method='post'><input type='submit' name='interest' value='express interest'>
    </form></td>
                    </tr>
                    </table>";
                    break;

                }

            }
            if ($tnoExisting == false) {
                echo "can not find the TNo.";
            }
        } else {
            echo "please enter TNo";
        }
    }
    if (isset($_POST['interest'])) {
        include ('buyerInterest.php');
    }

    ?>

</body>

</html>