<!DOCTYPE html>
<html>
<?PHP
session_start(); 
include "util/DbUtil.php";
$db_conn = getConnectedDb();

$comments=array();
if (is_null($db_conn)) {
	$errorMsg = new Content(null,"No database found.",null,null,null,null,null,null,null);
	$content = $errorMsg;
} else {
	$content = getContent($db_conn, $_GET["id"]);
    $comments = getComments($db_conn, $_GET["id"]);
    $user =getUserbyid($db_conn, $content->userID);
}
?>

<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--<meta content="text/html; charset=utf-8" http-equiv="Content-Type">-->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="stylesheets/content.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link href="lib/syntaxHighlighting/prism.css" rel="stylesheet" />
<script type="text/javascript">
    var content_id = <?php echo $content->id; ?>;
</script>
<script src="scripts/content.js"></script>
<script src="scripts/submitComment.js"></script>
<script src="scripts/jquery.autogrowtextarea.js"></script>


<title><?PHP echo "{$content->title}"; ?></title>

</head>
<body>
<div class='container-fluid'>
<div class="panel panel-default">
	<div class='panel-heading'><h1>Code Cleanup!</h1>
        <div id='userInfo'>
        <?PHP
        if (isset($_SESSION['username'])) {
            echo "<a id='account' href='account.php'>{$_SESSION['username']}</a>";
            echo "<button id='logout' class='btn btn-link' role='link' type='button' name='op' value='Link 1'>Logout</button>";
        } else {
            echo "<a id='login' href='login.php'>Login</a>";
            echo "<a id='register' href='registration.php'>No account? Register!</a>";
        }
        ?>
    </div>
    </div>
    <div class='panel-body'>
	<div id='title'>

	<?PHP
	echo "<p class='lead'><b>{$content->title}</b></p>";
	echo "<span>Submitted by: {$user}</span> <br></br>";
    echo "<span id='contentid'> {$content->id}</span>";
	?>

	</div>
    
	<div class="row">
        
        <div class='col-xm-1 col-md-1'>
         
        <div class="btn-group-vertical btn-group-sm">
        <button type='button' class='btn btn-default' onclick='upvote()'>
	        <span class='glyphicon glyphicon-chevron-up'></span>
	    </button>
        <?php
        echo "<span id='score'>{$content->score}</span>";
        ?>
        <button type='button' class='btn btn-default' onclick='downvote()'>
	        <span class='glyphicon glyphicon-chevron-down'></span>
	    </button>
        </div>
        </div>

        <div class="col-xm-11 col-md-11">
	    <?PHP
	    echo "<pre class='pre-scrollable'><code class='language-{$content->language}'>{$content->content}</code></pre>";
	    ?>
	    </div>
    </div> 
	</div>
    </div>

    <div class='row'>
     <textarea class='form-control noresize' id='comment' rows='3' placeholder='Comment'></textarea>
     <button type='button' style="float: right" class='btn btn-primary' onclick='parentcomment()'>Comment</button>
    </div>
     <?php
     
     foreach($comments as $comment){
        //$childcomments=array();
        //$childcomments=child_comments($db_conn, $comment->CommentId);
        $user=getUserbyid($db_conn,$comment->userID);
         echo
            
            "
            <div class='row'>
                <div class='col-xs-2 col-md-2'>
                </div>
                <div class='col-xs-10 col-md-10 panel panel-default'>
                    <span>Submitted by: {$user}</span>
                    <pre id='parent' class='pre-scrollable'>{$comment->comment}</pre>
			    </div>
            </div>";
            foreach($child_comments as $comment){
                echo
                "<div class='row'>
                    <div class='col-xs-8 col-xs-offset-4'>
                    <pre class='pre-scrollable'>{$comment->comment}</pre>
                    </div>";
         }
    }
?>
<!-- Error message pop up for logging in -->
        <div id="commentSubmissionErrorModal" class="modal fade" role="dialog">
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
<script src="lib/syntaxHighlighting/prism.js"></script>
<div style="clear: both;"></div>
</div>
</body>
</html>