<?PHP

include "../util/DbUtil.php";

session_start();

$securityQuestionId = $_POST['securityQuestion'];
$securityQuestionAnswer = mysql_real_escape_string($_POST['answer']);

$response = "test";

if ($securityQuestionId === "") {
	$response = "Must choose security question";
} elseif($securityQuestionAnswer === "") {
    $response = "No security question answer supplied";
} else {
	$db_conn = getConnectedDb();
	if (is_null($db_conn)) {
		$response = "Error connecting to database. Try again later.";
	} elseif(!changeSecurityQuestionAndAnswer($db_conn, $_SESSION['userid'], $securityQuestionId, $securityQuestionAnswer)) {
        $response = "Security question change failed for unknown reason.";
    } else {
	    $response = "success";
    }
}

echo $response;

?>