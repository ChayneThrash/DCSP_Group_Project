<?PHP

include "../util/DbUtil.php";

session_start();

$currentPassword = mysql_real_escape_string($_POST['currentPassword']);
$password = mysql_real_escape_string($_POST['newPassword']);
$passwordConf = mysql_real_escape_string($_POST['newPasswordConf']);

$response = "test";

if ($password === "") {
	$response = "password cannot be blank";
} elseif($password != $passwordConf) {
    $response = "passwords must match";
} else {
	$db_conn = getConnectedDb();
	if (is_null($db_conn)) {
		$response = "Error connecting to database. Try again later.";
	} elseif (!checkPassword($db_conn, $_SESSION['userid'], $currentPassword)){
        $response = "incorrect password";
    } elseif(!changePassword($db_conn, $_SESSION['userid'], $password)) {
		$response = "Failed to change password for unknown reason.";
	} else {
	    $response = "success";
    }
}

echo $response;

?>