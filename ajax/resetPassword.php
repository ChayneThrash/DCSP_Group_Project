<?PHP

include "../util/DbUtil.php";

session_start();

$password = mysql_real_escape_string($_POST['password']);
$passwordConf = mysql_real_escape_string($_POST['passwordConf']);
$securityQuestionAnswer = mysql_real_escape_string($_POST['securityQuestionAnswer']);

$response = "test";

if (isset($_POST['securityQuestion']) && $_POST['securityQuestion'] === "") {
	$response = "Must choose security question";
} elseif($password != $passwordConf) {
    $response = "passwords must match";
} elseif($securityQuestionAnswer == "") {
    $response = "No security question answer supplied";
} else {
	$db_conn = getConnectedDb();
	if (is_null($db_conn)) {
		$response = "Error connecting to database. Try again later.";
	} elseif(!securityQuestionAnswerCorrect($db_conn, $securityQuestionAnswer, $_SESSION['userid'])) {
		$response = "Supplied answer is incorrect.";
	} elseif(!changePassword($db_conn, $_SESSION['userid'], $password)) {
		$response = "Failed to change password for unknown reason.";
	} else {
	    $response = "success";
    }
}

echo $response;

?>