<?php

include "Classes.php";

function getConnectedDb() {
	$db_user = 'dcsp05';
	$db_pass = 'ab1234';
	$db_name = 'dcsp05';
	$db_server = 'localhost';
	
	$mysqli = new mysqli($db_server, $db_user, $db_pass, $db_name);
	
	return ($mysqli->connect_errno) ? null : $mysqli;
}

function userExists($connected_db, $username) {
	$query = "select * from User where Username = '$username'";
	$result = $connected_db->query($query);
	return ($result->fetch_assoc()) ? true : false;
}

function checkUsernamePassword($connected_db, $user, $pass) {
	$query = "select * from User where Username = '$user' and Password = MD5('$pass') and Deleted <> 1 and Banned <> 1";
	$result = $connected_db->query($query);
	if ($row = $result->fetch_assoc()) {
        return new User($row['UserId'], $row['Username'], $row['Admin']);
    } else {
        return null;
    }
}

function checkPassword($connected_db, $userId, $pass) {
    $query = "select * from User Where UserId = {$userId} and Password = MD5('$pass')";
    $result = $connected_db->query($query);
    return ($result->fetch_assoc()) ? true : false;
}

function getUser($connected_db, $user) {
    $query = "select * from User where Username = '$user'";
    $result = $connected_db->query($query);
	if ($row = $result->fetch_assoc()) {
        return new User($row['UserId'], $row['Username'], $row['Admin']);
    } else {
        return null;
    }
}

function checkIfAdmin($connected_db, $username) {
	$query = "select Admin from User where Username = '$username'";
	$result = $connected_db->query($query);
	if ($row = $result->fetch_assoc()) {
		return ($row['Admin']) ? true : false;
	} else {
		return false;
	}
}

function addUser($connected_db, $username, $password, $securityQuestionId, $securityQuestionAnswer) {
    $query = "insert into User(Username, Password, SecurityQuestionId, SecurityQuestionAnswer) Values('$username', MD5('$password'), $securityQuestionId, '$securityQuestionAnswer')";
    $result = $connected_db->query($query);
	return $result;
}

function getSecurityQuestions($connected_db) {
    $query = "select * from SecurityQuestion";
    $result = $connected_db->query($query);
    $questions = array();
    while($row = $result->fetch_assoc()){
       $question = new SecurityQuestion($row['Question'], $row['QuestionId']);
       $questions[] = $question;
    }
    return $questions;
}

function getContent_select10($connected_db, $low, $high){
	$query = "select * from Content order by Score desc limit $low,$high";
	$result = $connected_db->query($query);
	$content = array();
	while($row = $result->fetch_assoc()){
		$content_entry = new Content($row['ContentId'], $row['Title'], $row['Content'], $row['Score'], $row['UserId'], $row['Language'], $row['ProjectId'], $row['Removed'], $row['DateMade']);
		$content[] = $content_entry;
	}
	return $content;
}

function getContent($connected_db, $id_num){
	$query = "select * from Content where ContentId = $id_num";
	$result = $connected_db->query($query);
	$row = $result->fetch_assoc();
	$content_entry = new Content($row['ContentId'], $row['Title'], $row['Content'], $row['Score'], $row['UserId'], $row['Language'], $row['ProjectId'], $row['Removed'], $row['DateMade']);
	return $content_entry;
}

function getProjects($connected_db, $userId = null) {
    $query = "";
    if (is_null($userId)) {
        $query = "select ProjectId, ProjectName from Project where Private = 0";
    } else {
        $query = "select p.ProjectId as 'ProjectId', p.ProjectName as 'ProjectName' from Project as p Left Join ProjectMembers as pm on p.ProjectId = pm.ProjectId where (p.Private = 0) or (pm.UserId = $userId)";
    }
	$result = $connected_db->query($query);
	$projects = array();
	while($row = $result->fetch_assoc()){
		$project_entry = new Project($row['ProjectId'], $row['ProjectName']);
		$projects[] = $project_entry;
	}
	return $projects;
}

function getLanguages($connected_db) {
    $query = "select * from Language";
    $result = $connected_db->query($query);
    $languages = array();
    while ($row = $result->fetch_assoc()) {
        $languages[] = $row['Language'];
    }
    return $languages;
}

function titleExists($connected_db, $title) {
	$query = "select * from Content where Title = '$title'";
	$result = $connected_db->query($query);
	return ($result->fetch_assoc()) ? true : false;
}

function projectAccessibleByUser($connected_db, $userId, $projectId) {
    $query = "select * from Project as p Left Join ProjectMembers as pm on p.ProjectId = pm.ProjectId where (p.ProjectId = {$projectId}) and ((p.Private = 0) or (pm.userId = {$userId}))";
    $result = $connected_db->query($query);
	return ($result->fetch_assoc()) ? true : false;
}

function addContent($connected_db, $userId, $title, $content, $projectId, $language) {
    if ($language != "null") {
        $language = "'$language'";
    }
    $query = "insert into Content(UserId, Title, Content, ProjectId, Language, DateMade) Values({$userId}, '$title', '$content', {$projectId}, {$language}, NOW())";
    $result = $connected_db->query($query);
	return $result;
}

function projectExists($connected_db, $projectName) {
    $query = "select * from Project where ProjectName = '$projectName'";
    $result = $connected_db->query($query);
    return ($result->fetch_assoc()) ? true : false;
}

function addProject($connected_db, $projectName, $isPrivate, $userId) {
    $private = ($isPrivate === "true") ? '1' : '0';
    $query = "call AddProject({$userId}, '$projectName', {$private})";
    $result = $connected_db->query($query);
    return ($result && ($row = $result->fetch_assoc())) ? $row['ProjectId'] : null;
}

function getUserSecurityQuestion($connected_db, $userId) {
    $query = "Select sq.Question From User u Inner Join SecurityQuestion sq on u.SecurityQuestionId = sq.QuestionId where u.UserId = {$userId}";
    $result = $connected_db->query($query);
    return ($row = $result->fetch_assoc()) ? $row['Question'] : null;
}

function securityQuestionAnswerCorrect($connected_db, $securityQuestionAnswer, $userId) {
    $query = "Select SecurityQuestionAnswer from User where UserId = {$userId}";
    $result = $connected_db->query($query);
    return ($row = $result->fetch_assoc()) ? ($securityQuestionAnswer === $row['SecurityQuestionAnswer']) : false;
}

function changePassword($connected_db, $userId, $password) {
    $query = "Update User Set Password = MD5('$password') where UserId = {$userId}";
    $result = $connected_db->query($query);
    return $result;
}

function changeSecurityQuestion($connected_db, $userId, $securityQuestionId) {
    $query = "Update User Set SecurityQuestionId = {$securityQuestionId} where UserId = {$userId}";
    $result = $connected_db->query($query);
    return $result;
}

function changeSecurityQuestionAndAnswer($connected_db, $userId, $securityQuestionId, $securityQuestionAnswer){
    $query = "Update User Set SecurityQuestionId = {$securityQuestionId}, SecurityQuestionAnswer = '$securityQuestionAnswer' where UserId = {$userId}";
    $result = $connected_db->query($query);
    return $result;
}

function markAccountAsDeleted($connected_db, $userId) {
    $query = "Update User Set Deleted = 1 Where UserId = {$userId}";
    $result = $connected_db->query($query);
    return $result;
}

?>