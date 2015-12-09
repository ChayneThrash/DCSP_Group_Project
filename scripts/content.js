var i = 0;
function vote(vote, contentid) {
	$.ajax({
		type: "POST",
		url: "ajax/vote.php",
		data: {contentid: contentid, vote: vote},
		success: updateVote
		});
}

function commentvote(vote, commentid) {

    $.ajax({
        type: "POST",
        url: "ajax/commentVote.php",
        data: {commentid: commentid ,commentvote: vote},
        success: updateCommentVote
    });
}

function deletecontent(id) {
    if(window.confirm("Are you sure you want to delete this content?")){
    $.ajax({
        type: "POST",
        url: "ajax/deleteContent.php",
        data: { contentid: id },
        success: contentdeletionresponse
    });
}
}

function banUser(id) {
    i++;
    if (
        window.confirm("Are you sure you want to ban this User?")) {
        $.ajax({
            type: "POST",
            url: "ajax/banUser.php",
            data: { userid: id },
            success: userbanresponse
        });

    }

}
function updateVote(output){
	if(output === "noUser"){
		window.alert("You must be logged in to vote.");
	}
	else if(output === "up"){
		window.alert("You cannot upvote twice on one submission.");
	}
	else if(output === "down"){
		window.alert("You cannot downvote twice on one submission.");
	}
	else{
		$("#score").html("Current Score: " + output);
	}
}

function contentdeletionresponse(response) {
    if (response==="success"){
        window.alert("Content deleted successfully");
        window.location = "index.php";
    }else{
        window.alert(response);
    }
}

function updateCommentVote(output){
    if(output==="noUser"){
        window.alert("You must be logged in to vote.");
    }else if(output==="up"){
        window.alert("You cannot upvote twice.");
    }else if(output==="down"){
        window.alert("You cannot downvote twice.");
    }
}
function userbanresponse(response) {
    if (response === "success") {
        window.alert("User has been banned.");
    } else {
        window.alert(response);
    }
}

