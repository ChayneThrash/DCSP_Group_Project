<?php

include "../util/DbUtil.php";

session_start();

$projectName = $_POST['projectName'];
$isPrivate = $_POST['isPrivate'];

//Response will be json.
$formattableResponse = '{ "status":"%s", "projectId":%d, "projectName":"%s" }';
$response = "";
$projectId = null;

if(strlen($projectName) > 50) {
	$response = sprintf($formattableResponse, "Project Name too long.", 0, "");
} else {
	$db_conn = getConnectedDb();
	if (is_null($db_conn)) {
		$response = sprintf($formattableResponse, "Error connecting to database", 0, "");
	} elseif(projectExists($db_conn, $projectName)) {
		$response = sprintf($formattableResponse, "Project already exists", 0, "");
	} elseif(is_null($projectId = addProject($db_conn, $projectName, $isPrivate, $_SESSION['userid']))) {
		$response = sprintf($formattableResponse, "Unknown error occurred.", 0, "");
	} else {
		$response = sprintf($formattableResponse, "success", $projectId, $projectName);
	}
}

echo $response;
?>