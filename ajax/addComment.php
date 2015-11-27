<?php

include "../util/DbUtil.php";
session_start();

$comment = mysql_real_escape_string($_POST['comment']);

$response = "test";
$userObj = null;


if (strlen($comment) === 0) {
    $response = "Invalid content";
} else {
	$db_conn = getConnectedDb();
	if (is_null($db_conn)) {
		$response = "Error connecting to database. Try again later.";
	} elseif(!addComment($db_conn, $comment,$_SESSION['userid'])) {
		$response = "Unknown error.";
	} else {
		$response = "success";
	}
}

echo $response;
?>