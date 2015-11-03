<!DOCTYPE html>
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
    <div class="container">
      <h2>Registration<h2>
        <div class="form-group">
          <label for="Username">username</label>
          <input type="text" class="form-control" id="user" placeholder="username">
	      <p id="usernameError"></p>
        </div>
        <div class="form-group">
          <label for="pwd">Password:</label>
          <input type="password" class="form-control" id="pwd" placeholder="Enter password">
        </div>
        <div class="form-group">
          <label for="pwd-reenter">Re-enter Password</label>
          <input type="password" class="form-control" id="pwd" placeholder="Enter password">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </div>
</body>

</html>
