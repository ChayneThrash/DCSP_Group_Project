function upvote() {
	$.ajax({
		method: "POST",
		url: "ajax/upvote.php",
		data: { userid: $("#userid").val(), contentid: $("#contentid").val()},
		success: function(result){
			$("#score").html(result);
		}});
}