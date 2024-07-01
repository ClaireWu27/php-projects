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
    $cidClassmate = stripslashes($_POST['cidClassmate']);


    $findClassmate = "
    select *
    from EnrolWithdraw
    join Class on Class.id = EnrolWithdraw.cid
    join Member on Member.id = EnrolWithdraw.mid
    where EnrolWithdraw.cid = '$cidClassmate'
";

    try {
        $result = $conn->query($findClassmate);
        if ($result->num_rows > 0) {
            echo "<table>
            <th>Class ID</th>
            <th>Title</th>
            <th>Name</th>
            ";
            while ($row = $result->fetch_assoc()) {
                echo " 
                <tr>
                <td> {$row['cid']}</td>
                <td>{$row['title']}</td>
                <td>{$row['firstname']} {$row['lastname']}</td>
            </tr>";
            }
            echo " </table>";
        } else {
            echo "no students in this class";
        }



    } catch (mysqli_sql_exception $e) {
        die($e->getCode() . ":" . $e->getMessage());
    }
    ?>
</body>

</html>