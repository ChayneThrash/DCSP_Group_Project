$(document).ready(function() {
	$("#user").on("input propertychange paste", validateUser);
});

function validateUser() {
	var username = $("#user").val();
	if (!isUserValid(username)) {
		$("#usernameError").text("Make sure username contains no spaces and is less than 50 characters long.")
	} else {
		$("#usernameError").text("");
	}
}

function isUserValid(username) {
	return (username.length <= 50) && (username.split(' ').length === 1);
}