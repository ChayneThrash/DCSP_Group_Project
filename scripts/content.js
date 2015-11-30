function vote(vote, contentid) {
	$.ajax({
		type: "POST",
		url: "ajax/vote.php",
		data: {contentid: contentid, vote: vote},
		success: updateVote
		});
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