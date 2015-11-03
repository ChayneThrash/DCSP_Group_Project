var userValid = false;
var pwdValid = false;
var pwdConfValid = false;
var securityQuestionSelected = false;
var securityQuestionAnswered = false;

$(document).ready(function () {
    $("#user").on("input propertychange paste", validateUser);
    $("#pwd").on("input propertychange paste", validatePwd);
    $("#pwdConf").on("input propertychange paste", validatePwdConf);
    $("#securityQuestionDropdown").change(validateSecurityQuestion);
    $("#answer").on("input propertychange paste", validateAnswer);
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

function validateSecurityQuestion() {
    securityQuestionSelected = ($("#securityQuestionDropdown").val() !== "");
    changeButtonStatus();
}

function validateAnswer() {
    securityQuestionAnswered = ($("#answer").val().length !== 0);
    changeButtonStatus();
}

function changeButtonStatus() {
    $(":submit").prop("disabled", getButtonStatus());
}

function getButtonStatus() {
    return !(userValid && pwdValid && pwdConfValid && securityQuestionSelected && securityQuestionAnswered);
}

function checkLogin() {
    $.ajax({
        method: "POST",
        url: "ajax/addUser.php",
        data:
            {
                username: $("#user").val(), password: $("#pwd").val(), passwordConf: $("#pwdConf").val(),
                securityQuestionId: $("#securityQuestionDropdown").val(), securityQuestionAnswer: $("#answer").val()
            },
        success: processRegistrationResponse
    });
}

function processRegistrationResponse(response) {
    if (response === "success") {
        window.location.href = "index.php";
    } else {
        $("#registerErrorMsg").text(response);
        $("#registrationErrorModal").modal("show");
    }
}