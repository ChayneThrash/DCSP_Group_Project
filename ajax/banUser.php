<?php
include "../util/DbUtil.php";

session_start();
$userid=$_POST['userid'];
$response = "";

$db_conn = getConnectedDb();

if (is_null($db_conn)) {
	$response = "Error connecting to database. Try again later.";
}elseif(isUserBanned($db_conn, $userid)){
    $response = "User already banned.";
}elseif (!banUser($db_conn,$userid)){
    $response = "Error banning user";
}else{
    $response = "Success";
}

echo $response;
?>