<?PHP
session_start();
include "../util/DbUtil.php";
$db_conn = getConnectedDb();

if(is_null($_SESSION["userid"])){
	$output = "noUser";
}
else{
	$userId = $_SESSION['userid'];
	
	$contentId = $_POST['contentid'];
	$vote = checkUserVote($db_conn, $userId, $contentId);

	if($vote == 'u'){
		$output = "up";
	}
	else if($vote == 'n'){
		upvoteContentVoting($db_conn, $userId, $contentId);
		addContentScore($db_conn, $contentId);
		$score = getContentScore($db_conn, $contentId);
		$output = $score;
	}
	else{
		insertContentVotingUp($db_conn, $userId, $contentId);
		addContentScore($db_conn, $contentId);
		$score = getContentScore($db_conn, $contentId);
		$output = $score;
	}
}

echo $output;
?>
