$(document).ready(function() {
	$("#user").on("input propertychange paste", validateUser);
	$(":submit").prop("disabled", true);
	$("#login").submit(checkLogin);
});

function validateUser() {
	var username = $("#user").val();
	if (!isUserValid(username)) {
		$("#usernameError").text("Make sure username contains no spaces and is less than 50 characters long.")
		$(":submit").prop("disabled", true);
	} else {
		$("#usernameError").text("");
		if(username.length == 0){ // Can't submit if field is empty.
			$(":submit").prop("disabled", true);
		} else {
			$(":submit").prop("disabled", false);
		}
	}
}

function isUserValid(username) {
	return (username.length <= 50) && (username.split(' ').length === 1);
}

function checkLogin() {
	$.ajax({
		method: "POST",
		url: "ajax/checkLogin.php",
		data: { username: $("#user").val(), password: $("#pwd").val() }
	}).done(processLoginResponse);
}

function processLoginResponse(response) {
	if (response === "success") {
		window.location.href = "index.php";
	} else {
		$("#loginErrorMsg").text(response);
		$("#loginErrorModal").modal("show");;
	}
}