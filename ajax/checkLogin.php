<?PHP

include "../util/DbUtil.php";


$username = $_POST['username'];
$password = $_POST['password'];

$response = "test";
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
		$response = "Incorrect password";
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