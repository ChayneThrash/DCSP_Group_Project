<?php

include "../util/DbUtil.php";

session_start();

$formattableResponse = '{ "status":"%s", "members":%s, "owners":%s }'; //member and owner response does not have quotes since they will be arrays.
$projectName = mysql_real_escape_string($_POST['projectName']);
$response = '{ "status":"%s", "members":%s, "owners":%s }'; //member and owner response does not have quotes since they will be arrays.;

if (!isset($_SESSION['userid'])) {
    $response = sprintf($formattableResponse, "Error, must be logged in to access this.", "[]", "[]");
} elseif (is_null($db_conn = getConnectedDb())) {
    $response = sprintf($formattableResponse, "Error, database could not be connected to.", "[]", "[]");
} elseif (!userIsMemberOfProject($db_conn, $projectName, $_SESSION['userid'])) {
    $response = sprintf($formattableResponse, "Error, you do not have appropriate access to this project.", "[]", "[]");
} else {
    $isUserProjectAdmin = isUserProjectAdmin($db_conn, $projectName, $_SESSION['userid']);
    $memberJArray = array();
    $ownerJArray = array();
    $projectMembers = getMembersOfProject($db_conn, $projectName);
    $numMembers = count($projectMembers);
    if ($numMembers > 0) {
        $memberJArray[] = $projectMembers[0]->userName;
        if ($projectMembers[0]->isAdmin) {
            $ownerJArray[] = $projectMembers[0]->userName;
        }
        for ($i = 1; $i < $numMembers; $i++) {
            $memberJArray[] = $projectMembers[$i]->userName;    
            if ($projectMembers[$i]->isAdmin) {
                $ownerJArray[] = $projectMembers[$i]->userName;
            }
        }
    }

    $response = json_encode(array("status" => "success", "members" => $memberJArray, "owners" => $ownerJArray, "isUserAdmin" => $isUserProjectAdmin, "username" => $_SESSION['username']));
}

echo $response;

?>