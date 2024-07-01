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
    $cidCapacity = stripslashes($_POST['numofMemberCid']);
    $classCapacity = " select cid,title, count(*) as enrolNum
    from EnrolWithdraw
    join Class on Class.id = EnrolWithdraw.cid
    where EnrolWithdraw.cid = '$cidCapacity'
    group by Class.id,title 
";

    try {
        $result = $conn->query($classCapacity);
        if ($result->num_rows > 0) {
            echo "<table>
            <th>Class ID</th>
            <th>Title</th>
            <th>Number of Enrolment</th>
            ";
            while ($row = $result->fetch_assoc()) {
                echo " 
                <tr>
                <td> {$row['cid']}</td>
                <td>{$row['title']}</td>
                <td>{$row['enrolNum']}</td>
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