<?php

class User {
    var $id;
    var $username;
    var $isAdmin;

    public function __construct($userId, $username, $isAdmin) {
        $this->id = $userId;
        $this->username = $username;
        $this->isAdmin = $isAdmin;
    }
}

class SecurityQuestion {

    var $question;
    var $id;

    public function __construct($question, $id) {
        $this->question = $question;
        $this->id = $id;
    }

}

class Content {
	var $id;
	var $title;
	var $content;
	var $score;
	var $userID;
	var $langauge;
	var $projectID;
	var $removed;
	var $date_made;
	
	public function __construct($id, $title, $content, $score, $userID, $language, $projectID, $removed, $date_made){
		$this->id = $id;
		$this->title = $title;
		$this->content = $content;
		$this->score = $score;
		$this->userID = $userID;
		$this->langauge = $language;
		$this->projectID = $projectID;
		$this->removed = $removed;
		$this->date_made = $date_made;
	}
}

class Project {
    var $name;
    var $id;  
    var $isPrivate;

    public function __construct($id, $name, $isPrivate = false) {
        $this->name = $name;
        $this->id = $id;
        $this->isPrivate = $isPrivate;
    }

}

class Comment {
}

class Reply {
}

?>