<?PHP

include "../util/DbUtil.php";


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
		$_SESSION['admin'] = checkIfAdmin($db_conn, $username);
	}
}

echo $response;

?>