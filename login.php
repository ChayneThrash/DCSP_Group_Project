<!DOCTYPE html>
<html>

<?PHP $rememberMe = isset($_COOKIE['rememberMe']);?>

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="stylesheets/login.css">
    <script src="scripts/formValidation.js"></script>
    <script src="scripts/login.js"></script>
    <title>Code Cleanup login page</title>
</head>

<body>

<h1>Code Cleanup!</h1>

<div class="container">
  <h2>Login</h2>
    <div class="form-group">
      <label for="Username">username</label>
      <input type="text" class="form-control" id="user" value=<?PHP echo ($rememberMe) ? $_COOKIE['rememberMe'] : "";?>>
	  <p id="usernameError"></p>
    </div>
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Enter password">
    </div>
    <div class="checkbox">
      <label><input type="checkbox" id="rememberMe" <?PHP echo ($rememberMe) ? "checked" : ""; ?>> Remember me</label>
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
</div>

<!-- Error message pop up for logging in -->
<div id="loginErrorModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Error occurred during login</h4>
      </div>
      <div class="modal-body">
        <p id="loginErrorMsg"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="submit">Close</button>
      </div>
    </div>

  </div>
</div>

</body>

</html>