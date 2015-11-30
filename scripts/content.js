function upvote() {
	$.ajax({
		type: "POST",
		url: "ajax/upvote.php",
		data: {contentid: content_id},
		success: function(output){
			$("#score").html(output);
		}});
}

function downvote(){
	$("#score").html("2");
}