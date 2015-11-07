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
        $errorMsgProject = 'error';
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
    <link rel="stylesheet" type="text/css" href="stylesheets/index.css">
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
            <select class="form-control" id="LanguageDropdown">
                <option value="" selected disabled>Language</option>
                <option value="null">None</option>
                <?PHP 
                foreach($languages as $language) {
                    echo "<option value='{$language}'>{$language}</option>";
                }
                ?>
            </select>
            <button type="submit" class="btn btn-default">Submit</button>
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
                <p id="submissionErrorMsg"></p>
              <div class="modal-body">
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
