<!DOCTYPE html>
<?PHP session_start(); 
include "util/DbUtil.php";
$db_conn = getConnectedDb();
$content = array();
$languages = array();
$projects = array();
if (is_null($db_conn)) {
	$errorMsg = new Content(null,"No database found.",null,null,null,null,null,null,null);
	$content = $errorMsg;
} else {
	$content = getContent_select10($db_conn, (($_GET['page'] * 10)), (($_GET['page'] + 1)*10));
	$languages = getLanguages($db_conn);
    $projects = getProjects($db_conn);
}
?>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="stylesheets/index.css">
<script src="scripts/index.js"></script>
<script src="scripts/content.js"></script>
<title>Code Cleanup main page</title>
</head>

<body>
    <h1 id="heading">Code Cleanup!</h1>
    <div id='userInfo'>
        <?PHP
        if (isset($_SESSION['username'])) {
            echo "<a id='account' href='account.php'>{$_SESSION['username']}</a>";
            echo "<button id='logout' class='btn btn-link' role='link' type='button' name='op' value='Link 1'>Logout</button>";
        } else {
            echo "<a id='login' href='login.php'>login</a>";
            echo "<a id='register' href='registration.php'>no account? Register!</a>";
        }
        ?>
    </div>
	
<div class="container-fluid">
	<?PHP
	for($entry = 0; $entry < 10; $entry++){
	echo 

	"<div class='panel panel-default'>
        <div class='panel-body'>
            <div class='row'>
		        <div class='col-sm-1 text-left'>
		        <button type='button' onclick='vote(0,{$content[$entry]->id}); history.go(0);'>
				<span class='glyphicon glyphicon-chevron-up'></span>
				</button>
		        </div>
		        <div class='col-sm-11 text-left'>
		        <nobr><a href='content.php?id={$content[$entry]->id}'>{$content[$entry]->title}</a></nobr>
		        </div>
	        </div>
	        <div class='row'>
		        <div class='col-sm-1 text-left'>
		        <button type='button' onclick='vote(1, {$content[$entry]->id}); history.go(0)'>
				<span class='glyphicon glyphicon-chevron-down'></span>
				</button>
		        </div>
		        <div class='col-sm-2 text-left'>
		        <nobr>Current Score: {$content[$entry]->score}</nobr>
		        </div>
		        <div class='col-sm-4 text-left'>
		        <nobr>Date Made: {$content[$entry]->date_made}</nobr>
		        </div>
		        <div class='col-sm-5 text-left'>
		        <nobr>Language: {$content[$entry]->language}</nobr>
		        </div>
	        </div>
        </div>
    </div>
	<p></p>
	";}
	?>
	
	
	<div class="row">
	<div class="col-sm-3">
	<?PHP 
        if(isset($_SESSION['username'])) { 
            echo "<a href='submitContent.php'>Submit Content</a>";
    } ?>
	</div>
	<div class="col-sm-3">
	<?PHP
		if(isset($_GET['page']) && $_GET['page'] != 0){
			$prevPage = $_GET['page'] - 1;
			echo "<a href='index.php?page={$prevPage}'>Previous Page</a>";
		}
	?>
	</div>
	<div class="col-sm-3">
	<?PHP
		echo "<a href='index.php'>Home Page</a>";
	?>
	</div>
	<div class="col-sm-3">
	<?PHP
		if(isset($_GET['page'])){
			$nextPage = $_GET['page'] + 1;
			echo "<a href='index.php?page={$nextPage}'>Next Page</a>";
		}
		else{
			echo "<a href='index.php?page=1'>Next Page</a>";
		}
	?>
	</div>
	</div>
	<p></p>
	
	<div class="row">
	<div class="col-sm-2">
	<nobr>Search By:</nobr>
	</div>
	<div class="col-sm-3">
		<select class="form-control" id="LanguageDropdown">
            <option value="" selected disabled>Language</option>
            <option value="null">None</option>
            <?PHP 
            foreach($languages as $language) {
                echo "<option value='{$language}'>{$language}</option>";
            }
            ?>
        </select>
	</div>
	<div class="col-sm-3">
		<select class="form-control" id="ProjectDropdown">
            <option value="" selected disabled>Project</option>
            <option value="null">None</option>
            <?PHP 
            foreach($projects as $project) {
                echo "<option value='{$project->id}'>{$project->name}</option>";
            }
            ?>
        </select>
	</div>
	<div class="col-sm-2">
	<button type="button" class="btn btn-primary" id="SearchSubmissionButton">Search</button>
	</div>
	</div>
</div>
<p></p>
<p></p>

</body>

</html>
