<?php

include "../util/DbUtil.php";

session_start();

$projectName = $_POST['projectName'];
$usernameToAdd = $_POST['username'];
$isOwner = $_POST['isOwner'];

$response = "";

$db_conn = getConnectedDb();

if (!isset($_SESSION['userid'])) {
    $response = "Error. Not logged in.";
} elseif (is_null($db_conn)) {
    $response = "Error connecting to the database. Please try again later.";
} elseif(!isUserProjectAdmin($db_conn, $projectName, $_SESSION['userid'])) {
    $response = "You must be a project owner to access this feature.";
} elseif(userAlreadyMemberOfProject($db_conn, $projectName, $usernameToAdd)) {
    $response = "User is already of a member of the project";
} elseif(!addProjectMember($db_conn, $projectName, $usernameToAdd)) {
    $response = "Failed to add for unknown reason.";
} else {
    $response = "success";
}

echo $response;

?>