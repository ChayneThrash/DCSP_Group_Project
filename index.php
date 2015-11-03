<!DOCTYPE html>
<?PHP session_start(); ?>
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
    <a id="login"
		<?PHP echo (isset($_SESSION['username'])) ? " href='account.php'>{$_SESSION['username']}" : " href='login.php'>login";?>
	</a>
</body>

</html>
