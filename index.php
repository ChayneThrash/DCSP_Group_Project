<!DOCTYPE html>
<?PHP session_start(); 
include "util/DbUtil.php";
$db_conn = getConnectedDb();
$content = array();
if (is_null($db_conn)) {
	$errorMsg = new Content(null,"No database found.",null,null,null,null,null,null,null);
	$content = $errorMsg;
} else {
	$content = getContent($db_conn);
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
    <div id='ContentSubmission'>
    <?PHP 
        if(isset($_SESSION['username'])) { 
            echo "<a href='submitContent.php'>SubmitContent</a>";
    } ?>

    </div>
	<div id='Content'>
		<?PHP
		echo "<TABLE BORDER='5' WIDTH='50%' CELLPADDING='7' CELLSPACING='3'>";
		echo "<TR><TH>Top Entries<TH></TR>";
		echo "<TR><TH>Title</TH><TH>Content</TH></TR>";
		echo "<TR ALIGN = 'LEFT'><TD>{$content->title}</TD><TD>{$content->content}</TD></TR>";
		echo "</Table>";
		?>
	</div>
</body>

</html>
