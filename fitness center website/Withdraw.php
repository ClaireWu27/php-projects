<?php
$mid = $_SESSION['mid'];
$cid = stripslashes($_GET['cid']);
$delelteEnrol = "delete from EnrolWithdraw
                  where mid='$mid' and cid='$cid'";
try {
    $conn->query($delelteEnrol);
    echo "$cid is removed from your enrollment";
} catch (mysqli_sql_exception $e) {
    die($e->getCode() . ":" . $e->getMessage());
}
?>