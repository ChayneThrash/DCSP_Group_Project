<?PHP

include "../util/DbUtil.php";
session_start();

$title = mysql_real_escape_string($_POST['title']);
$content = mysql_real_escape_string($_POST['content']);
$projectId = $_POST['projectId'];
$language = mysql_real_escape_string($_POST['language']);


$response = "test";
$userObj = null;

if (strlen(title) === 0 || strlen(title) > 50) {
	$response = "Title not valid";
} elseif (strlen($content) === 0) {
    $response = "Invalid content";
} elseif ($projectId === "") {
    $response = "No project selected";
} elseif ($language === "") {
    $response = "No language selected";
} else {
	$db_conn = getConnectedDb();
	if (is_null($db_conn)) {
		$response = "Error connecting to database. Try again later.";
	} elseif(titleExists($db_conn, $title)) {
		$response = "Title already exists.";
	} elseif ($projectId !== 'null' && !projectAccessibleByUser($db_conn, $_SESSION['userid'], $projectId)){
        $response = "Project is not accessible.";
    } elseif(!addContent($db_conn, $_SESSION['userid'], $title, $content, $projectId, $language)) {
		$response = "Unknown error.";
	} else {
		$response = "success";
	}
}

echo $response;

?>