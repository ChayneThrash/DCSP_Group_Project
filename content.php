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
<link href="lib/syntaxHighlighting/prism.css" rel="stylesheet" />
<script src="scripts/content.js"></script>
<title><?PHP echo "{$content->title}"; ?></title>

</head>
<body>

<div class="container-fluid">
	<h1>Code Cleanup!</h1>
	<div id='title'>
	<?PHP
	echo "<p class='lead'>{$content->title}</p>";
	echo "<p id='contentid'>{$content->id}</p>";
	?>
	</div>
	<div>
	<?PHP
	echo "<pre><code class='language-{$content->language}'>{$content->content}</code></pre>";
	?>
	</div>
	<div>
	<h4>Score</h4>
	<?PHP
	echo "<p id='score'>{$content->score}</p>";
	?>

	<button type='button' onclick='upvote()'>
	<span class='glyphicon glyphicon-chevron-up'></span>
	</button>
	<button type='button' onclick='downvote()'>
	<span class='glyphicon glyphicon-chevron-down'></span>
	</button>
    <button type="button" onclick="comment()" class="btn btn-info btn-sm">Comment</button>
	</div>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
        </div>
	    
        </div>

<script src="lib/syntaxHighlighting/prism.js"></script>
</body>
</html>