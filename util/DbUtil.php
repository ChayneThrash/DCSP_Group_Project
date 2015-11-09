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
	$query = "select * from User where Username = '$user' and Password = MD5('$pass')";
	$result = $connected_db->query($query);
	if ($row = $result->fetch_assoc()) {
        return new User($row['UserId'], $row['Username'], $row['Admin']);
    } else {
        return null;
    }
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

function getContent_top10($connected_db){
	$query = "select * from Content limit 10 order by Score desc";
	$result = $connected_db->query($query);
	$content = array();
	while($row = $result->fetch_assoc()){
		$content_entry = new Content($row['ContentID'], $row['Title'], $row['Content'], $row['Score'], $row['UserID'], $row['Language'], $row['ProjectID'], $row['Removed'], $row['DateMade']);
		$content[] = $content_entry;
	}
	return $content;
}

function getProjects($connected_db, $userId) {
    $query = "select p.ProjectId as 'ProjectId', p.ProjectName as 'ProjectName' from Project as p Left Join ProjectMembers as pm on p.ProjectId = pm.ProjectId where (p.Private = 0) or (pm.UserId = $userId)";
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

?>