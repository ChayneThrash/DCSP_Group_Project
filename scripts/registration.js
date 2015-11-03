var userValid = false;
var pwdValid = false;
var pwdConfValid = false;

$(document).ready(function () {
    $("#user").on("input propertychange paste", validateUser);
    $("#pwd").on("input propertychange paste", validatePwd);
    $("#pwdConf").on("input propertychange paste", validatePwdConf);
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
        if ((username.length === 0)) { // Can't submit if field is empty.
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
    validatePwdConf();
    changeButtonStatus();
}

function validatePwdConf() {
    var pwdConf = $("#pwdConf").val();
    var pwd = $("#pwd").val();
    pwdConfValid = (pwdConf === pwd);
    if (!pwdConfValid) {
        $("#pwdError").text("Make sure that both passwords match");
    } else {
        $("#pwdError").text("");
    }
    changeButtonStatus();
}

function changeButtonStatus() {
    $(":submit").prop("disabled", !(userValid && pwdValid && pwdConfValid));
}

function checkLogin() {
    $.ajax({
        method: "POST",
        url: "ajax/addUser.php",
        data: { username: $("#user").val(), password: $("#pwd").val() },
        success: processLoginResponse
    });
}

function processLoginResponse(response) {
    if (response === "success") {
        window.location.href = "index.php";
    } else {
        $("#registerErrorMsg").text(response);
        $("#registrationErrorModal").modal("show");
    }
}