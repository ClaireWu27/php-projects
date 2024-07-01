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
    $facility = stripslashes($_POST['findFacility']);
    $facilityEnrolNum = " select facility, count(EnrolWithdraw.cid) as enrolNum
    from EnrolWithdraw
    join Class on Class.id=EnrolWithdraw.cid
    group by facility
    having facility='$facility'
";

    try {
        $result = $conn->query($facilityEnrolNum);
        if ($result->num_rows > 0) {
            echo "<table>
            <th>Facility</th>
            <th>Total Enrolment</th>
            ";
            while ($row = $result->fetch_assoc()) {
                echo " 
                <tr>
                <td> {$row['facility']}</td>
                <td>{$row['enrolNum']}</td>
             
            </tr>";
            }
            echo " </table>";
        } else {
            echo "no student in this facility <br>";
        }

    } catch (mysqli_sql_exception $e) {
        die($e->getCode() . ":" . $e->getMessage());
    }




    ?>
</body>

</html>