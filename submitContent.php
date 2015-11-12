<!DOCTYPE html>
<?PHP
include "util/DbUtil.php";
session_start();
if (isset($_SESSION['username'])) { ?>
    <?PHP
    $db_conn = getConnectedDb();
    $projects = array();
    $languages = array();
    if (is_null($db_conn)) {
        $projects[] = new Project(0, 'error');
        $languages[] = 'error connecting to database';
    } else {
        $projects = getProjects($db_conn, $_SESSION['userid']);
        $languages = getLanguages($db_conn);
    }
    ?>
    <html>

    <head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="scripts/submitContent.js"></script>
    <title>Code Cleanup main page</title>
    </head>

    <body>
        <div class="container">
          <h2>Registration<h2>
            <div class="form-group">
              <label for="Title">Title:</label>
              <input type="text" class="form-control" id="title" placeholder="title">
	          <p id="titleError"></p>
            </div>
            <div class="form-group">
              <label for="content">Content:</label>
              <textarea class="form-control" rows="5" id="content" placeholder="Enter content"></textarea>
            </div>
            <select class="form-control" id="ProjectDropdown">
              <option value="" selected disabled>Project</option>
              <option value="null">None</option>
              <?PHP
              foreach($projects as $project) {
                  echo "<option value='{$project->id}'>{$project->name}</option>";
              }
              ?>
            </select>
            <button class="btn btn-link" role="link" type="button" name="op" value="Link 1" data-toggle="modal" data-target="#addProjectModal">Want to submit to a new project? Add here!</button>
            <select class="form-control" id="LanguageDropdown">
                <option value="" selected disabled>Language</option>
                <option value="null">None</option>
                <?PHP 
                foreach($languages as $language) {
                    echo "<option value='{$language}'>{$language}</option>";
                }
                ?>
            </select>
            <button type="submit" class="btn btn-default" id="ContentSubmissionButton">Submit</button>
        </div>

        <!-- Error message pop up for logging in -->
        <div id="contentSubmissionErrorModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Error occurred during submission</h4>
              </div>
              <div class="modal-body">
                <p id="submissionErrorMsg"></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="submit">Close</button>
              </div>
            </div>

          </div>
        </div>

        <!-- Modal for adding project -->
        <div id="addProjectModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Project</h4>
              </div>
              <div class="modal-body">
                <form id="projectForm" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label  class="col-sm-2 control-label" for="projectName">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="projectName" placeholder="name"/>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                      <div class="checkbox">
                        <label><input id="projectSetting" type="checkbox"/>Private?</label>
                      </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-5 col-xs-offset-3">
                            <button type="button" class="btn btn-primary" id="ProjectSubmissionButton">Submit</button>
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

    </body>
<?PHP } else {  
    echo "<h>Error, must be logged in</h>\n";
}
?>
</html>
