<?php
include "../util/DbUtil.php";

session_start();
$commentid=$_POST['commentid'];
$response = "";

$db_conn = getConnectedDb();
if (is_null($db_conn)) {
	$response = "Error connecting to database. Try again later.";
} elseif (!deletecomment($db_conn, $commentid)) {
    $response = "Comment unable to be deleted for unknown reason.";
} else {
    $response = "success";
}

echo $response;
?>