<?php

include "../util/DbUtil.php";


$username = mysql_real_escape_string($_POST['username']);
$password = mysql_real_escape_string($_POST['password']);

$oneWeekMs = 3600*24*7;

if ($_POST['rememberMe'] === "true") {
	setcookie("rememberMe", $username, time() + $oneWeekMs, "/");
} else {
	setcookie("rememberMe", "", time() + $oneWeekMs, "/");
}

$response = "";
$userObj = null;


if(substr_count($username, ' ') != 0) {
	$response = "Username must not contain a space.";
} else {
	$db_conn = getConnectedDb();
	if (is_null($db_conn)) {
		$response = "Error connecting to database. Try again later.";
	} elseif(!userExists($db_conn, $username)) {
		$response = "Username does not exist.";
	} elseif(is_null($userObj = checkUsernamePassword($db_conn, $username, $password))) {
		$response = "Invalid username or password";
	} else {
		$response = "success";
		session_start();
		$_SESSION['username'] = $userObj->username;
		$_SESSION['admin'] = $userObj->isAdmin;
        $_SESSION['userid'] = $userObj->id;
	}
}

echo $response;
?>