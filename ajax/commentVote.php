<?PHP
session_start();
include "../util/DbUtil.php";
$db_conn = getConnectedDb();
$userId = $_SESSION['userid'];
$commentId = $_POST['commentid'];
$vote = checkUserCommentVote($db_conn, $userId, $commentId);

if(is_null($_SESSION["userid"])){
	$output = "noUser";
}
else if($_POST['commentvote'] == 0){
	if($vote == 'u'){
		$output = "up";
	}
	else if($vote == 'd'){
		commentVoting($db_conn, $userId, $commentId, 'n');
		modCommentScore($db_conn, $commentId, 1);
		$output = getCommentScore($db_conn, $commentId);
	}
	else if($vote == 'n'){
		commentVoting($db_conn, $userId, $commentId, 'u');
		modCommentScore($db_conn, $commentId, 1);
		$output = getCommentScore($db_conn, $commentId);
		
	}
	else{
		insertCommentVoting($db_conn, $userId, $commentId, 'u');
		modCommentScore($db_conn, $commentId, 1);
		$output = getCommentScore($db_conn, $commentId);
	}
}
else if($_POST['commentvote'] == 1){
	if($vote == 'd'){
		$output = "down";
	}
	else if($vote == 'u'){
		commentVoting($db_conn, $userId, $commentId, 'n');
		modCommentScore($db_conn, $commentId, -1);
		$output = getCommentScore($db_conn, $commentId);
	}
	else if($vote == 'n'){
		commentVoting($db_conn, $userId, $commentId, 'd');
		modCommentScore($db_conn, $commentId, -1);
		$output = getCommentScore($db_conn, $commentId);
		
	}
	else{
		insertCommentVoting($db_conn, $userId, $commentId, 'd');
		modCommentScore($db_conn, $commentId, -1);
		$output = getCommentScore($db_conn, $commentId);
	}
}

echo $output;
?>
