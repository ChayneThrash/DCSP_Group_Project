<!DOCTYPE html>
<?PHP
include "util/DbUtil.php";
$db_conn = getConnectedDb();
$securityQuestions = array();
if (is_null($db_conn)) {
    $errorMsg = new SecurityQuestion('error', 1);
    $securityQuestions[] = $errorMsg;
} else {
    $securityQuestions = getSecurityQuestions($db_conn);
}
?>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="stylesheets/index.css">
<script src="scripts/formValidation.js"></script>
<script src="scripts/registration.js"></script>
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
          <input type="password" class="form-control" id="pwdConf" placeholder="Re-enter password">
          <p id="pwdError"></p>
        </div>
        <select class="form-control">
          <option value="" selected disabled>Security Question</option>
          <?PHP 
          foreach($securityQuestions as $question) {
              echo "<option value='{$question->id}'>{$question->question}</option>";
          }
          ?>
        </select>
        <div class="form-group">
          <label for="securityQuestionAnswer">Security Question Answer</label>
          <input type="text" class="form-control" id="answer" placeholder="Security question answer">    
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </div>

    <!-- Error message pop up for logging in -->
    <div id="registrationErrorModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Error occurred during login</h4>
          </div>
            <p id="registerErrorMsg"></p>
          <div class="modal-body">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="submit">Close</button>
          </div>
        </div>

      </div>
    </div>

</body>

</html>
