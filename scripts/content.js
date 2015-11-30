function upvote(id) {

	$.ajax({
		type: "POST",
		url: "ajax/upvote.php",
		data: {contentid: $("#contentid").text()},
		success: function(output){
			$("#score").html(output);
		}});
}

function downvote(id){
	$("#score").html("2");
}