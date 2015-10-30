<?php

include("util/DbUtil.php")

$username = $_POST['username'];
$password = $_POST['password'];

$response = "";

if(count(explode(' ', $username)) != 1) {
	$response = "Username must not contain a space."
} else {
	$db_conn = getConnectedDb();
	if (isNull($db_conn)) {
		$response = "Error connecting to database. Try again later.";
	} elseif(!userExists($db_conn, $username)) {
		$response = "Username does not exist."
	} elseif(!checkUsernamePassword($db_conn, $username, $password)) {
		$response = "Incorrect password";
	} else {
		$response = "success";
	}
}

echo $response;
?>