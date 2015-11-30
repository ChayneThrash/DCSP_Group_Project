<?php

include "../util/DbUtil.php";
session_start();

$comment = mysql_real_escape_string($_POST['comment']);
$contentid = $_POST['contentid'];
if(isset($_POST['parentid'])){
    $parentid = $_POST['parentid'];
}else{
    $parentid = null;
}

$response = "test";
$userObj = null;


if (strlen($comment) === 0) {
    $response = "Invalid content";
} elseif($contentid === "" || $contentid ==0){
    $response = "No content detected";
}
else {
	$db_conn = getConnectedDb();
	if (is_null($db_conn)) {
		$response = "Error connecting to database. Try again later.";
	} elseif($parentid==null){
            if(!addComment($db_conn, $_SESSION['userid'], $comment, $contentid)) {
		        $response = "Unknown error adding parent comment.";
                }else{
                    $response = "success";
                }
    } elseif($parentid != null){
            if(!addChildComment($db_conn, $_SESSION['userid'], $comment, $contentid, $parentid)){
                $response = "Unknown error adding child comment.";
            }else{
                $response = "success";
            }
	} 
}

echo $response;
?>