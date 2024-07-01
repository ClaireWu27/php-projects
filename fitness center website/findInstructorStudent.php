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
    $instructorId = stripslashes($_POST['instructorId']);
    $insStudent = " select EnrolWithdraw.mid, Member.firstname as mfn,Member.lastname as mln
    from EnrolWithdraw
    join Member on EnrolWithdraw.mid = Member.id
    join Class on Class.id=EnrolWithdraw.cid
    where Class.iid='$instructorId'
";

    try {
        $result = $conn->query($insStudent);
        if ($result->num_rows > 0) {
            echo "<table>
            <th>Member ID</th>
            <th>Name</th>
            ";
            while ($row = $result->fetch_assoc()) {
                echo " 
                <tr>
                <td> {$row['mid']}</td>
                <td>{$row['mfn']} {$row['mln']}</td>
             
            </tr>";
            }
            echo " </table>";
        } else {
            echo "this instructor has no student";
        }



    } catch (mysqli_sql_exception $e) {
        die($e->getCode() . ":" . $e->getMessage());
    }
    ?>
</body>

</html>