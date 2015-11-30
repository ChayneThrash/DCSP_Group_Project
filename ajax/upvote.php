<?PHP
session_start();
include "../util/DbUtil.php";
$db_conn = getConnectedDb();

if(is_null($_SESSION["userid"])){
	echo "You must be logged in to vote.";
}
else{
	$userId = $_SESSION['userid'];
	
	$contentId = $_POST['contentid'];
	$vote = checkUserVote($db_conn, $userId, $contentId);

	if($vote == 'u'){
		echo "You cannot upvote twice";
	}
	else if($vote == 'n'){
		upvoteContentVoting($db_conn, $userId, $contentId);
		addContentScore($db_conn, $contentId);
		$score = getContentScore($db_conn, $contentId);
		echo $score;
	}
	else{
		insertContentVotingUp($db_conn, $userId, $contentId);
		addContentScore($db_conn, $contentId);
		$score = getContentScore($db_conn, $contentId);
		echo $score;
	}
}
?>
