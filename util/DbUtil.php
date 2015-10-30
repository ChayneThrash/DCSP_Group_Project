<?php

$db_user = 'dcsp05'
$db_pass = 'ab1234'
$db_name = 'dcsp05'
$db_server = 'localhost'

function getConnectedDb() {
	$db_conn = mysqli_connect($hostname,$username,$password,$database);
	return ($db_conn->connect_errno) ? 0 : $db_conn;
}

function userExists($connected_db, $username) {
		$query = "select * from User where username = " . $username;
		$result = $connected_db->query($query);
		return ($result->fetch_assoc()) ? true : false;
}

function checkUsernamePassword($connected_db, $username, $password) {
	$query = "select * from User where Username = " . $username " and Password = MD5(" . $password . ")";
	$result = $connected_db->query($query);
	return ($result->fetch_assoc()) ? true : false;
}

function checkIfAdmin($connected_db, $username) {
	$query = "select Admin from User where Username = " . $username;
	$result = $connected_db->query($query);
	if ($row = $result->fetch_assoc) {
		return ($result['Admin']) ? true : false;
	} else {
		return false;
	}
}

?>