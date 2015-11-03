<?php


class SecurityQuestion{

    var $question;
    var $id;

    public function __construct($question, $id) {
        $this->question = $question;
        $this->id = $id;
    }

}

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
	return ($result->fetch_assoc()) ? true : false;
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

?>