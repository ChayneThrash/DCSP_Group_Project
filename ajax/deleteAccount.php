<?php

include "../util/DbUtil.php";

session_start();

$response = "";

$db_conn = getConnectedDb();
if (is_null($db_conn)) {
	$response = "Error connecting to database. Try again later.";
} elseif (!markAccountAsDeleted($db_conn, $_SESSION['userid'])) {
    $response = "Account unable to be deleted for uknown reason";
} else {
    $response = "success";
    session_unset();
}

echo $response;

?>