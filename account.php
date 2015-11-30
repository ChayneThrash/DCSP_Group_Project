<!DOCTYPE html>
<?PHP
include "util/DbUtil.php";
session_start();
if (isset($_SESSION['username'])) { ?>
    <?PHP
    $securityQuestionMessage = "A system error occurred which removed your previous security question. Your answer has remained the same.";
    $db_conn = getConnectedDb();
    $securityQuestion = "error connecting to database";
    $questions = array();
    $questions[] =  new SecurityQuestion("error connecting to database", "");
    $projeccts = array();
    $projects[] = new Project("", "error connecting to database");
    if (!is_null($db_conn)) {
        $securityQuestion = getUserSecurityQuestion($db_conn, $_SESSION['userid']);
        $questions = getSecurityQuestions($db_conn);
        $projects = getProjectsUserIsAMemberOf($db_conn, $_SESSION['userid']);
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
    <title>Code Cleanup Account</title>
    </head>

    <body>
        <div class="container">
            <h2>Hi <?PHP echo "{$_SESSION['username']}.";?> Manage your account here!</h2>

            <ul class="nav nav-pills">
                <li><a data-toggle="pill" href="#ResetPwdTab">Reset Password</a></li>
                <li><a data-toggle="pill" href="#ChangePwdTab">Change Password</a></li>
                <li><a data-toggle="pill" href="#ChangeSecurityQuestionTab">Change Security Question</a></li>
                <?PHP
                if (!empty($projects)) {
                ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Manage Project<span class="caret"></span></a>
                        <ul class="dropdown-menu" id='ManageProjectList'>
                            <?PHP
                            foreach ($projects as $project) { ?>
                                <li><a data-private=<?PHP echo ($project->isPrivate) ? "'1'" : "'0'"; ?> href="#ManageProjectTab" data-toggle='pill'><?PHP echo $project->name; ?></a></li>
                            <?PHP } ?>
                        </ul>
                    </li>
                <?PHP } ?>
                <li><a href="#" id="deleteAccount" >Delete Account</a><li>
            </ul>

            <div class="tab-content">
                <div id="ResetPwdTab" class="tab-pane fade in active">
                    <form id="projectForm" method="post" class="form-horizontal">
                        <div class="form-group">
                            <label for="SecurityQuestion"><?PHP echo (is_null($securityQuestion)) ? "Please select a new question" : "SecurityQuestion:"; ?></label>
                            <div>
                            <?PHP 
                            if (is_null($securityQuestion)) { ?>
                                <select class='form-control-static' id='ResetPwdSecurityQuestionDropdown'>
                                    <option value="" selected disabled>Security Question</option>
                                    <?PHP 
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
                            <label for="password">Security Question Answer</label>
                            <div >
                                <input type="text" class="form-control" id="resetPwdSecurityQuestionAnswer" placeholder="answer"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <div >
                                <input type="password" class="form-control" id="resetPwdPwd" placeholder="password"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Confirm Password</label>
                            <div>
                                <input type="password" class="form-control" id="resetPwdPwdConf" placeholder="password"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" id="ResetPwdSubmissionButton">Submit</button>
                        </div>
                    </form>
                </div>

                <div id="ChangePwdTab" class="tab-pane fade">

                    <form id="projectForm" method="post" class="form-horizontal">
                        <div class="form-group">
                            <label for="password">Current Password</label>
                            <div >
                                <input type="password" class="form-control" id="changePwdCurrentPwd" placeholder="current password"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <div>
                                <input type="password" class="form-control" id="changePwdPwd" placeholder="password"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Confirm Password</label>
                            <div>
                                <input type="password" class="form-control" id="changePwdPwdConf" placeholder="password"/>
                            </div>
                        </div>
                        <p id="changePwdError"></p>

                        <div class="form-group">
                            <button type="button" class="btn btn-primary" id="ChangePwdSubmissionButton">Submit</button>
                        </div>
                    </form>
                </div>
                <div id="ChangeSecurityQuestionTab" class="tab-pane fade">
                    <form id="projectForm" method="post" class="form-horizontal">
                        <div class="form-group">
                            <label for="SecurityQuestion"><?PHP echo (is_null($securityQuestion)) ? "Please select a new question" : "SecurityQuestion:"; ?></label>
                            <div>
                                <select class='form-control-static' id='newSecurityQuestionDropdown'>
                                    <option value="" selected disabled>Choose Security Question</option>
                                    <?PHP 
                                    foreach($questions as $question) {
                                        echo "<option value='{$question->id}'>{$question->question}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="securityQuestion">New Answer</label>
                            <div >
                                <input type="text" class="form-control" id="newSecurityQuestionAnswer" placeholder="answer"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" id="ChangeSecurityQuestionSubmissionButton">Submit</button>
                        </div>
                  </form>
              </div>

              <div id='ManageProjectTab' class="tab-pane fade">
                <div class="panel panel-info">
                  <div class='panel-heading' id='ProjectNameHeading'></div>
                  <div class='panel-body'>
                      <div class='row'>
                          <div class='col-sm-4' id='membersColumn'>
                              <label for="members">Members</label>
                              <select multiple class='form-control' id="members">
                              </select>
                              <button id='addUserToProjectButton' title="Add user to project" data-toggle="tooltip" type="button" class="btn btn-default projectOwnerButtons">
                                  <span class="glyphicon glyphicon-plus"></span> 
                              </button>
                              <button id='removeUserfromProjectButton' title="Remove user from project" data-toggle="tooltip" type="button" class="btn btn-default projectOwnerButtons memberSelectButton">
                                  <span class="glyphicon glyphicon-minus"></span> 
                              </button>
                              <button id='makeMembersOwners' type="button" class="btn btn-default projectOwnerButtons memberSelectButton">Make Owners</button>
                          </div>
                          <div class='col-sm-4' id='ownersColumn'>
                              <label for="owners">Owners</label>
                              <select multiple class='form-control' id="owners">
                              </select>
                              <button id='addOwnerToProjectButton' title="Add new owner to project" data-toggle="tooltip" type="button" class="btn btn-default projectOwnerButtons">
                                  <span class="glyphicon glyphicon-plus"></span> 
                              </button>
                              <button id='removeMemberOwnership' title="Remove ownership" data-toggle="tooltip" type="button" class="btn btn-default projectOwnerButtons ownerSelectButton">
                                  <span class="glyphicon glyphicon-minus"></span> 
                              </button>
                          </div>
                      </div>
                      <div class='row'>
                          <div class='col-sm-1'>
                              <button type="button" class="btn btn-link">Leave project</button>
                          </div>
                          <div class='col-sm-1 projectOwnerButtons'>
                              <button id='makePublicButton' type="button" class="btn btn-link">Make Project Public</button>
                              <button id='makePrivateButton' type="button" class="btn btn-link">Make Project Private</button>
                          </div>
                      </div>
                  </div>
                </div>
              </div>

            </div>
        </div>

        <div id="addUserToProjectModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add User</h4>
              </div>
              <div class="modal-body">
                <form id="addUserToProjectForm" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label  class="col-sm-2 control-label" for="username">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="userToAddToProject" placeholder="name"/>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                      <div class="checkbox">
                        <label><input id="ownerCheckbox" type="checkbox"/>Owner</label>
                      </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-5 col-xs-offset-3">
                            <button type="button" class="btn btn-primary" id="addUserToProjectSubmissionButton">Submit</button>
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
