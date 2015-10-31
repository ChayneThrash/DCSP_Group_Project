<?PHP

//include("util/DbUtil.php")


function getConnectedDb() {
	$db_user = 'dcsp05';
	$db_pass = 'ab1234';
	$db_name = 'dcsp05';
	$db_server = 'localhost';
	
	$mysqli = new mysqli($db_server, 'ct446', 'cmt8101994', 'ct446');
	
	return ($mysqli->connect_errno) ? null : $db_conn;
}

function userExists($connected_db, $username) {
	$query = "select * from Users";
	$result = $connected_db->query($query);
	return ($result) ? "success" : "failed";
	//if ($row = $result->fetch_assoc()) {
	//	return "user found";
	//	return true;
	//} //else {
	//	return "user not found";
	//}
	//return ($result->fetch_assoc()) ? true : false;
}

function checkUsernamePassword($connected_db, $user, $pass) {
	$query = "select * from User where Username = '$user' and Password = MD5('$pass')";
	$result = $connected_db->query($query);
	return ($result->fetch_assoc()) ? true : false;
}

function checkIfAdmin($connected_db, $username) {
	$query = "select Admin from User where Username = '$username'";
	$result = $connected_db->query($query);
	if ($row = $result->fetch_assoc) {
		return ($result['Admin']) ? true : false;
	} else {
		return false;
	}
}

$username = $_POST['username'];
$password = $_POST['password'];

$response = "test";



if(substr_count($username, ' ') != 0) {
	$response = "Username must not contain a space.";
} else {
	$db_conn = getConnectedDb();
	if (is_null($db_conn)) {
		$response = "Error connecting to database. Try again later.";
	} elseif(!userExists($db_conn, $username)) {
		$response = "Username does not exist.";
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