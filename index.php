<!DOCTYPE html>
<?PHP session_start(); 
include "util/DbUtil.php";
$db_conn = getConnectedDb();
$content = array();
if (is_null($db_conn)) {
	$errorMsg = new Content(null,"No database found.",null,null,null,null,null,null,null);
	$content = $errorMsg;
} else {
	$content = getContent_top10($db_conn);
}
?>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="stylesheets/index.css">
<title>Code Cleanup main page</title>
</head>

<body>
    <h1 id="heading">Code Cleanup!</h1>
    <div id='userInfo'>
        <?PHP
        if (isset($_SESSION['username'])) {
            echo "<a id='login' href='account.php'>{$_SESSION['username']}</a>";
        } else {
            echo "<a id='login' href='login.php'>login</a>";
            echo "<a id='register' href='registration.php'>no account? Register!</a>";
        }
        ?>
    </div>
	<div id='Content'>
		<?PHP
		echo "<TABLE id='table'>";
		echo "<TR id='top'><TH>Top Entries<TH><TH></TH><TH></TH></TR>";
		echo "<TR><TH>Score</TH><TH>Title</TH><TH>Content</TH><TH>Language</TH></TR>";
		for($entry = 0; $entry < 10; $entry++){
		echo "<TR ALIGN = 'LEFT'><TD id='score'>{$content[$entry]->score}</TD>
		<TD id='title'><a href='content.php?id={$content[$entry]->id}'>{$content[$entry]->title}</a></TD>
		<TD>{$content[$entry]->content}</TD>
		<TD>{$content[$entry]->language}</TD></TR>";
		}
		echo "</Table>";
		?>
	</div>
	<div id='ContentSubmission'>
    <?PHP 
        if(isset($_SESSION['username'])) { 
            echo "<a href='submitContent.php'>Submit Content</a>";
    } ?>
    </div>
	<div id='NextPage'>
	<?PHP
		if(isset($_GET['page'])){
			$nextPage = $_GET['page'] + 1;
			echo "<a href='index.php?page={$nextPage}'>Next Page</a>";
		}
		else{
			echo "<a href='index.php?page=1'>Next Page</a>";
		}
	?>
	</div>
	<div id='PrevPage'>
	<?PHP
		if(isset($_GET['page']) && $_GET['page'] != 0){
			$prevPage = $_GET['page'] - 1;
			echo "<a href='index.php?page={$prevPage}'>Previous Page</a>";
		}
	?>
	</div>
	<div id='HomePage'>
	<?PHP
		echo "<a href='index.php'>Home Page</a>";
	?>
	</div>
</body>

</html>
