<!DOCTYPE html>
<?PHP
include "util/DbUtil.php";
session_start();
if (isset($_SESSION['username'])) { ?>
    <?PHP
    $securityQuestionMessage = "A system error occurred which removed your previous security question. Your answer has remained the same.";
    $db_conn = getConnectedDb();
    $securityQuestion = "error connecting to database";
    if (!is_null($db_conn)) {
        $securityQuestion = getUserSecurityQuestion($db_conn, $_SESSION['userid']);
    }
    ?>
    <html>

    <head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="scripts/NewPasswordValidator.js"></script>
    <link rel="stylesheet" href="stylesheets/account.css">
    <title>Code Cleanup main page</title>
    </head>

    <body>
        <div class="container">
            <h2>Hi <?PHP echo "{$_SESSION['username']}.";?> Manage your account here!</h2>
            <button class="btn btn-link" role="link" type="button" name="op" value="Link 1" data-toggle="modal" data-target="#ResetPasswordModal">Reset Password</button>
            <button class="btn btn-link" role="link" type="button" name="op" value="Link 1" data-toggle="modal" data-target="#ChangePasswordModal">Change Password</button>
            <button class="btn btn-link" role="link" type="button" name="op" value="Link 1" id="deleteAccount" >Delete Account</button>
        </div>

                <!-- Modal for adding project -->
        <div id="ResetPasswordModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Reset Password</h4>
              </div>
              <div class="modal-body">
                <form id="projectForm" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label  class="col-sm-4 control-label" for="SecurityQuestion"><?PHP echo (is_null($securityQuestion)) ? "Please select a new question" : "SecurityQuestion:"; ?></label>
                        <div class="col-sm-8">
                        <?PHP 
                        if (is_null($securityQuestion)) { ?>
                            <select class='form-control-static' id='ResetPwdSecurityQuestionDropdown'>
                                <option value="" selected disabled>Security Question</option>
                                <?PHP 
                                $questions = getSecurityQuestions($db_conn);
                                foreach($questions as $question) {
                                    echo "<option value='{$question->id}'>{$question->question}</option>";
                                }
                                ?>
                            </select>
                            <a href="#" data-toggle="tooltip" title="<?PHP echo $securityQuestionMessage ?>"><span class="glyphicon glyphicon-question-sign"></span></a>
                        <?PHP } else { ?>
                            <p class="form-control-static"><?PHP echo $securityQuestion; ?></p>
                        <?PHP } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-4 control-label" for="password">Security Question Answer</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="resetPwdSecurityQuestionAnswer" placeholder="answer"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-4 control-label" for="password">New Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="resetPwdPwd" placeholder="password"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-4 control-label" for="password">Confirm Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="resetPwdPwdConf" placeholder="password"/>
                        </div>
                    </div>
                    <p class="col-sm-4"></p>
                    <p class="col-sm-8" id="resetPwdError"></p>
                    <div class="form-group">
                        <div class="col-xs-5 col-xs-offset-3">
                            <button type="button" class="btn btn-primary" id="ResetPwdSubmissionButton">Submit</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="submit">Close</button>
              </div>
            </div>

          </div>
        </div>

                        <!-- Modal for changing password-->
        <div id="ChangePasswordModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change Password</h4>
              </div>
              <div class="modal-body">
                <form id="projectForm" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label  class="col-sm-4 control-label" for="password">Current Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="changePwdCurrentPwd" placeholder="current password"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-4 control-label" for="password">New Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="changePwdPwd" placeholder="password"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-4 control-label" for="password">Confirm Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="changePwdPwdConf" placeholder="password"/>
                        </div>
                    </div>
                    <p class="col-sm-4"></p>
                    <p class="col-sm-8" id="changePwdError"></p>
                    <div class="form-group">
                        <div class="col-xs-5 col-xs-offset-3">
                            <button type="button" class="btn btn-primary" id="ChangePwdSubmissionButton">Submit</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="submit">Close</button>
              </div>
            </div>

          </div>
        </div>
        <script src="scripts/account.js"></script> <!--This must be here! There is a check to see if an ID exists that fails if it is executed before the id is read.-->
    </body>
<?PHP } else {  
    echo "<h>Error, must be logged in</h>\n";
}
?>
</html>
