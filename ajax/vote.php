<?PHP
session_start();
include "../util/DbUtil.php";
$db_conn = getConnectedDb();
$userId = $_SESSION['userid'];
$contentId = $_POST['contentid'];
$vote = checkUserVote($db_conn, $userId, $contentId);

if(is_null($_SESSION["userid"])){
	$output = "noUser";
}
else if($_POST['vote'] == 0){
	if($vote == 'u'){
		$output = "up";
	}
	else if($vote == 'd'){
		contentVoting($db_conn, $userId, $contentId, 'n');
		modContentScore($db_conn, $contentId, 1);
		$output = getContentScore($db_conn, $contentId);
	}
	else if($vote == 'n'){
		contentVoting($db_conn, $userId, $contentId, 'u');
		modContentScore($db_conn, $contentId, 1);
		$output = getContentScore($db_conn, $contentId);
		
	}
	else{
		insertContentVoting($db_conn, $userId, $contentId, 'u');
		modContentScore($db_conn, $contentId, 1);
		$output = getContentScore($db_conn, $contentId);
	}
}
else if($_POST['vote'] == 1){
	if($vote == 'd'){
		$output = "down";
	}
	else if($vote == 'u'){
		contentVoting($db_conn, $userId, $contentId, 'n');
		modContentScore($db_conn, $contentId, -1);
		$output = getContentScore($db_conn, $contentId);
	}
	else if($vote == 'n'){
		contentVoting($db_conn, $userId, $contentId, 'd');
		modContentScore($db_conn, $contentId, -1);
		$output = getContentScore($db_conn, $contentId);
		
	}
	else{
		insertContentVoting($db_conn, $userId, $contentId, 'd');
		modContentScore($db_conn, $contentId, -1);
		$output = getContentScore($db_conn, $contentId);
	}
}

echo $output;
?>
