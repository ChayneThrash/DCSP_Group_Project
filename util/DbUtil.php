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

function getUserbyid($connected_db, $userid){
    $query = "select Username from User where UserId = '$userid'";
    $result = $connected_db->query($query);
    $row = $result->fetch_assoc();
    return ($row['Username']);
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


function getComments($connected_db, $id_num){
    $query = "select * from Comment where ParentContentId = $id_num and ParentCommentId is NULL and Removed = 0 order by Votes";
	$result = $connected_db->query($query);
	$comments=array();
	while($row = $result->fetch_assoc()){
		$comment_entry = new Comment($row['CommentId'], $row['ParentContentId'], $row['ParentCommentId'], $row['Comment'], $row['UserId'], $row['Votes'], $row['DateMade'], $row['Removed']);
		$comments[] = $comment_entry;
	}
	return $comments;
	
}

function child_comments($connected_db, $id_num, $comment_id){
    $query = "select * from Comment where ParentContentId = $id_num and ParentCommentId = $comment_id and Removed = 0 order by Votes";
	$result = $connected_db->query($query);
	$comments=array();
	while($row = $result->fetch_assoc()){
		$comment_entry = new Comment($row['CommentId'], $row['ParentContentId'], $row['ParentCommentId'], $row['Comment'], $row['UserId'], $row['Votes'], $row['DateMade'], $row['Removed']);
		$comments[] = $comment_entry;
	}
	return $comments;
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

function addComment($connected_db, $userId, $comment, $contentid){
    $query = "insert into Comment(UserId, Comment, ParentContentId, DateMade) Values({$userId},'$comment', {$contentid}, NOW())";
    $result = $connected_db->query($query);
    return $result;
}

function addChildComment($connected_db, $userId, $comment, $contentid, $parentcommentid){
    $query = "insert into Comment(UserId, Comment, ParentContentId, ParentCommentId, DateMade) Values({$userId},'$comment', {$contentid}, {$parentcommentid}, NOW())";
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
    $query = "call AddProject('$projectName', {$private}, {$userId})";
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

function deleteComment($connected_db, $commentid){
    $query = "Update Comment Set Removed = 1 where CommentId = {$commentid}";
    $result = $connected_db->query($query);
    return $result;
}

function deleteContent($connected_db, $contentid){
    $query = "Update Content Set Removed = 1 where ContentId = {$contentid}";
    $result=$connected_db->query($query);
    return $result;
}

function banUser($connected_db, $userid){
    $query = "Update User Set Banned = 1 where UserId = {$userid}";
    $result = $connected_db->query($query);
    return $result;
}
function isAdmin($connected_db, $userid){
    $query = "select Admin from User where UserId = '$userid'";
	$result = $connected_db->query($query);
	if ($row = $result->fetch_assoc()) {
		return ($row['Admin']) ? true : false;
	} else {
		return false;
	}
}

function checkUserVote($connected_db, $userId, $contentId){
	$query = "Select VoteType From ContentVoting where UserId = {$userId} and ContentId = {$contentId}";
	$result = $connected_db->query($query);
	$row = $result->fetch_assoc();
	return $row['VoteType'];
}

function modContentScore($connected_db, $contentId, $num){
	$query = "Update Content Set Score= (Score + {$num}) Where ContentId = {$contentId}";
	$result = $connected_db->query($query);
	return $result;
}

function contentVoting($connected_db, $userId, $contentId, $type){
	$query = "Update ContentVoting Set VoteType = '{$type}' Where ContentId = {$contentId} and UserId = {$userId}";
	$result = $connected_db->query($query);
	return $result;
}

function getContentScore($connected_db, $contentId){
	$query = "Select Score from Content where ContentId = {$contentId}";
	$result = $connected_db->query($query);
	$row = $result->fetch_assoc();
	return $row['Score'];
}

function insertContentVoting($connected_db, $userId, $contentId, $type){
	$query = "Insert Into ContentVoting (ContentId, UserId, VoteType) values ({$contentId}, {$userId}, '{$type}')";
	$result = $connected_db->query($query);
	return $result;
}

function checkUserCommentVote($connected_db, $userId, $commentId){
	$query = "Select VoteType From CommentVoting where UserId = {$userId} and CommentId = {$commentId}";
	$result = $connected_db->query($query);
	$row = $result->fetch_assoc();
	return $row['VoteType'];
}

function modCommentScore($connected_db, $commentId, $num){
	$query = "Update Comment Set Votes= (Votes + {$num}) Where CommentId = {$commentId}";
	$result = $connected_db->query($query);
	return $result;
}

function commentVoting($connected_db, $userId, $commentId, $type){
	$query = "Update CommentVoting Set VoteType = '{$type}' Where CommentId = {$commentId} and UserId = {$userId}";
	$result = $connected_db->query($query);
	return $result;
}

function getCommentScore($connected_db, $commentId){
	$query = "Select Votes from Comment where CommentId = {$commentId}";
	$result = $connected_db->query($query);
	$row = $result->fetch_assoc();
	return $row['Score'];
}

function insertCommentVoting($connected_db, $userId, $commentId, $type){
	$query = "Insert Into CommentVoting (CommentId, UserId, VoteType) values ({$commentId}, {$userId}, '{$type}')";
	$result = $connected_db->query($query);
	return $result;
}

function getProjectsUserIsAMemberOf($connected_db, $userId) {
    $query = "Select p.ProjectName, p.ProjectId, p.Private from ProjectMembers pm Left Join Project p on pm.ProjectId = p.ProjectId where pm.UserId = {$userId}";
    $result = $connected_db->query($query);
    $projects = array();
    while ($row = $result->fetch_assoc()) {
        $projects[] = new Project($row['ProjectId'], $row['ProjectName'], ($row['Private']) ? true : false);
    }
    return $projects;
}

function userIsMemberOfProject($connected_db, $projectName, $userId) {
    $query = "select pm.UserId from ProjectMembers pm left join Project p on pm.ProjectId = p.ProjectId where p.ProjectName = '$projectName' and pm.UserId = {$userId}";
    $result = $connected_db->query($query);
    return ($result->fetch_assoc()) ? true : false;
}

function isUserProjectAdmin($connected_db, $projectName, $userId) {
    $query = "select pm.Admin from ProjectMembers pm left join Project p on pm.ProjectId = p.ProjectId where p.ProjectName = '$projectName' and pm.UserId = {$userId}";
    $result = $connected_db->query($query);
    if ($row = $result->fetch_assoc()) {
        return ($row['Admin']) ? true : false;
    }
    return false;
}

function isUserBanned($connected_db, $userid){
    $query = "select * from User where UserId = {$userid}";
    $result = $connected_db->query($query);
    if($row = $result->fetch_assoc()){
        return ($row['Banned']) ? true : false;
    }
}

function getMembersOfProject($connected_db, $projectName) {
    $query = "select u.UserName, pm.Admin from User u inner join ProjectMembers pm on u.UserId = pm.UserId Inner Join Project p on p.ProjectId = pm.ProjectId where p.ProjectName = '$projectName'";
    $result = $connected_db->query($query);
    $members = array();
    while ($row = $result->fetch_assoc()) {
        $members[] = new ProjectMember($row['UserName'], ($row['Admin']) ? true : false);
    }
    return $members;
}

function userAlreadyMemberOfProject($connected_db, $projectName, $usernameToAdd) {
    $query = "select * from ProjectMembers pm left join Project p on pm.ProjectId = p.ProjectId left join User u on pm.UserId = u.UserId where p.ProjectName = '$projectName' and u.UserName = '$usernameToAdd'";
    $result = $connected_db->query($query);
    return ($result->fetch_assoc()) ? true : false;
}

function addProjectMember($connected_db, $projectName, $usernameToAdd, $isOwner) {
    $owner = ($isOwner === "true") ? "1" : "0";
    $query = "insert into ProjectMembers(UserId, ProjectId, Admin) select (select UserId from User where UserName = '$usernameToAdd' and Banned <> 1 and Deleted <> 1) as UserId, (select ProjectId from Project where ProjectName = '$projectName') as ProjectId, {$owner}";
    $result = $connected_db->query($query);
    return ($result);
}

?>