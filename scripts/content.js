function upvote() {
	$.ajax({
		method: "POST",
		url: "ajax/upvote.php",
		data: { userid: document.getElementById("userid").val(), contentid: document.getElementById("contentid").val()},
		success: function(result){
			document.getElementById("score").html(result);
		}});
}

function downvote(){
	$("#score").html("2");
}