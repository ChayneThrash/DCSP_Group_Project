<?php

include("util/DbUtil.php")


$username = $_POST['username'];
$password = $_POST['password'];

$oneWeekMs = 3600*24*7;

if ($_POST['rememberMe'] === "true") {
	setcookie("rememberMe", $username, time() + $oneWeekMs);
} else {
	setcookie("rememberMe", "", time() + $oneWeekMs);
}

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
		session_start();
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		$_SESSION['admin'] = checkIfAdmin($db_conn, $username);
	}
}

echo $response;
?>