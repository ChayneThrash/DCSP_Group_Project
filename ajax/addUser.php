<?PHP

include "../util/DbUtil.php";


$username = $_POST['username'];
$password = $_POST['password'];
$passwordConf = $_POST['passwordConf'];
$securityQuestionId = $_POST['securityQuestionId'];
$securityQuestionAnswer = $_POST['securityQuestionAnswer'];

$response = "test";

if(substr_count($username, ' ') != 0) {
	$response = "Username must not contain a space.";
} elseif($password != $passwordConf) {
    $response = "passwords must match";
} elseif($securityQuestionId == "") {
    $response = "No Question selected";
} elseif($securityQuestionAnswer == "") {
    $response = "No security question answer supplied";
} else {
	$db_conn = getConnectedDb();
	if (is_null($db_conn)) {
		$response = "Error connecting to database. Try again later.";
	} elseif(userExists($db_conn, $username)) {
		$response = "Username already exists.";
	} elseif(!addUser($db_conn, $username, $password, $securityQuestionId, $securityQuestionAnswer)) {
		$response = "Failed to add user for unknown reason.";
	} else {
		$response = "success";
		session_start();
		$_SESSION['username'] = $username;
		$_SESSION['admin'] = false;
	}
}

echo $response;

?>