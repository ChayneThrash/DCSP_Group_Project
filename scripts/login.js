var userValid = false;
var pwdValid = false;

$(document).ready(function () {
    $("#user").on("input propertychange paste", validateUser);
    $("#pwd").on("input propertychange paste", validatePwd);
	$(":submit").prop("disabled", true);
	$(":submit").on("click", checkLogin);
});

function validateUser() {
    var username = $("#user").val();
    var pwd = $("#pwd").val();
	if (!isUserValid(username)) {
		$("#usernameError").text("Make sure username contains no spaces and is less than 50 characters long.")
		userValid = false;
	} else {
		$("#usernameError").text("");
		if((username.length === 0)){ // Can't submit if field is empty.
		    $(":submit").prop("disabled", true);
		    userValid = false;
		} else {
		    $(":submit").prop("disabled", false);
		    userValid = true;
		}
	}
	changeButtonStatus();
}

function validatePwd() {
    var pwd = $("#pwd").val();
    pwdValid = (pwd.length != 0);
    changeButtonStatus();
}

function changeButtonStatus() {
    $(":submit").prop("disabled", !(userValid && pwdValid));
}

function checkLogin() {
	$.ajax({
		method: "POST",
		url: "ajax/checkLogin.php",
		data: { username: $("#user").val(), password: $("#pwd").val() },
		success: processLoginResponse
	});
}

function processLoginResponse(response) {
	if (response === "success") {
		window.location.href = "index.php";
	} else {
		$("#loginErrorMsg").text(response);
		$("#loginErrorModal").modal("show");
	}
}