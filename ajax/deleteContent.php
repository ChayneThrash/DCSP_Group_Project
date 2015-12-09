<?php
include "../util/DbUtil.php";

session_start();
$contentid=$_POST['contentid'];
$response = "";

$db_conn = getConnectedDb();
if (is_null($db_conn)) {
	$response = "Error connecting to database. Try again later.";
} elseif (!deleteContent($db_conn, $contentid)) {
    $response = "Content unable to be deleted for unknown reason.";
} else {
    $response = "success";
}

echo $response;
?>