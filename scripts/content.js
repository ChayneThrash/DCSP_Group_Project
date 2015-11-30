function upvote() {
	$.ajax({
		type: "POST",
		url: "ajax/upvote.php",
		data: {contentid: content_id},
		success: updateVote
		});
}

function downvote(){
	$("#score").html("2");
}

function updateVote(output){
	if(output === "noUser"){
		window.alert("You must be logged in to vote.");
	}
	else if(output === "up"){
		window.alert("You cannot upvote twice on one submission.")
	}
	else{
		$("#score").html(output);
	}
}