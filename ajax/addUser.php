<?PHP

include "../util/DbUtil.php";

$usernameCorrectLength = (strlen($_POST['username']) <= 50);

$username = mysql_real_escape_string($_POST['username']);
$password = mysql_real_escape_string($_POST['password']);
$passwordConf = mysql_real_escape_string($_POST['passwordConf']);
$securityQuestionId = $_POST['securityQuestionId'];
$securityQuestionAnswer = mysql_real_escape_string($_POST['securityQuestionAnswer']);

$response = "test";
$userObj = null;

if ((substr_count($username, ' ') != 0) || !$usernameCorrectLength) {
	$response = "Username must not contain a space and must not be longer than 50 characters.";
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
	} elseif (is_null($userObj = getUser($db_conn, $username))) {
        $response = "Unexpected error.";
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