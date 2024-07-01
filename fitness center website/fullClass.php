<?php
######################full class list################################
$selectClassInfo = "select Class.id as class_id, 
                        title,
                        type,
                        Class.facility as cf,
                        Instructor.id as insid,
                    Instructor.firstname as ifn,
                    Instructor.lastname as iln,
                    count(EnrolWithdraw.cid) as enrolNum
                      from Class
                    left join Instructor on Class.iid=Instructor.id
                    left join EnrolWithdraw on Class.id=EnrolWithdraw.cid
                     group by Class.id, title, type, cf, ifn";
$userEnrolQuery = "select cid from EnrolWithdraw where mid='$mid'";
$userEnrolledClasses = [];
try {
    $result = $conn->query($userEnrolQuery);
    while ($row = $result->fetch_assoc()) {
        $userEnrolledClasses[] = $row['cid'];
    }

} catch (mysqli_sql_exception $e) {
    die($e->getCode() . ":" . $e->getMessage());
}


try {


    $result = $conn->query($selectClassInfo);
    echo " <h1> Full Class List </h1>
        <table>
            <th>Class ID</th>
            <th>Title</th>
            <th>Type</th>
            <th>Facility</th>
            <th>Instructor ID</th>
            <th>Instructor Name</th>
            <th>Status</th>
          
   ";

    while ($row = $result->fetch_assoc()) {

        echo " 
            <tr>
            <td> {$row['class_id']}</td>
            <td>{$row['title']}</td>
            <td>{$row['type']}</td>
            <td>{$row['cf']}</td>
            <td>{$row['insid']}</td>
            <td>{$row['ifn']} {$row['iln']}</td>";

        if (in_array($row['class_id'], $userEnrolledClasses)) {
            $status = 'Enrolled';
        } else if ($row['type'] == 'group' && $row['enrolNum'] >= 6) {
            $status = 'Full';

        } else if ($row['type'] == 'individual' && $row['enrolNum'] >= 1) {
            $status = 'Full';
        } else {
            $status = 'Available';
        }

        echo "<td>$status</td>
            </tr>";
    }
    echo " </table>
        </form>";

} catch (mysqli_sql_exception $e) {
    die($e->getCode() . ":" . $e->getMessage());
}

?>