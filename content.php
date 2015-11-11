<!DOCTYPE html>
<html>
<?PHP
session_start(); 
include "util/DbUtil.php";
$db_conn = getConnectedDb();
if (is_null($db_conn)) {
	$errorMsg = new Content(null,"No database found.",null,null,null,null,null,null,null);
	$content = $errorMsg;
} else {
	$content = getContent($db_conn, $_GET["id"]);
}
?>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="stylesheets/content.css">
<title><?PHP echo "{$content->title}"; ?></title>
</head>
<body>

<script>
function upvote(){
	document.getElementById("score").innerHTML = 1;
}
</script>

	<h1>Code Cleanup!</h1>
	<div id='title'>
	<?PHP
	echo "<p class='lead'>{$content->title}</p>";
	?>
	</div>
	<div>
	<?PHP
	echo "<pre class='pre-scrollable'>{$content->content}</pre>";
	?>
	</div>
	<div>
	<h4>Score</h4>
	<?PHP
	echo "<p id='score'>{$content->score}</p>";
	?>
	<button type="button" onclick="upvote()" class="btn btn-info btn-sm">Upvote</button>
	<button type="button" id="downvote" class="btn btn-info btn-sm">Downvote</button>
	</div>
</body>
</html>