resetPwdSecurityQuestionSelected = !resetPwdNeedsNewSecurityQuestion(); 
resetPwdSecurityQuestionAnswered = false;
resetPwdValidator = new NewPasswordValidator("#resetPwdPwd", "#resetPwdPwdConf");

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $("#resetPwdPwd").on("input propertychange paste", resetPwdValidatePwd);
    $("#resetPwdPwdConf").on("input propertychange paste", resetPwdValidatePwdConf);
    $("#resetPwdSecurityQuestionAnswer").on("input propertychange paste", validateResetPwdSecurityQuestionAnswer);
    $("#ResetPwdSubmissionButton").prop("disabled", true);
    $("#ResetPwdSubmissionButton").on("click", resetPassword);
    if (document.getElementById("ResetPwdSecurityQuestionDropdown") != null) {
        $("#ResetPwdSecurityQuestionDropdown").change(validateResetPwdSecurityQuestion);
    }
});

function resetPwdNeedsNewSecurityQuestion() {
    return !(document.getElementById("ResetPwdSecurityQuestionDropdown") == null);
}

function resetPwdValidatePwd() {
    resetPwdValidator.validateNewPwd();
    resetPwdValidatePwdConf();
}

function resetPwdValidatePwdConf() {
    resetPwdValidator.validateNewPwdConf();
    if (!resetPwdValidator.pwdConfValid) {
        $("#resetPwdError").text("Make sure that both passwords match");
    } else {
        $("#resetPwdError").text("");
    }
    changeButtonStatus();
}

function changeButtonStatus() {
    $("#ResetPwdSubmissionButton").prop("disabled", getButtonStatus());
}

function getButtonStatus() {
    return !(resetPwdValidator.pwdValid && resetPwdValidator.pwdConfValid && resetPwdSecurityQuestionSelected && resetPwdSecurityQuestionAnswered);
}

function validateResetPwdSecurityQuestion() {
    resetPwdSecurityQuestionSelected = ($("#ResetPwdSecurityQuestionDropdown").val() !== "");
    changeButtonStatus();
}

function validateResetPwdSecurityQuestionAnswer() {
    resetPwdSecurityQuestionAnswered = ($("#resetPwdSecurityQuestionAnswer").val().length !== 0);
    changeButtonStatus();
}

function resetPassword() {
    var postData = 
        {
            password: $("#resetPwdPwd").val(), passwordConf: $("#resetPwdPwdConf").val(),
            securityQuestionAnswer: $("#resetPwdSecurityQuestionAnswer").val()
        }
                    
    if (resetPwdNeedsNewSecurityQuestion()) {
        postData.securityQuestion = $("#ResetPwdSecurityQuestionDropdown").val();
    }

    $.ajax({
        method: "POST",
        url: "ajax/resetPassword.php",
        data: postData,
        success: processResetPwdResponse
    });
}

function processResetPwdResponse(response) {
    if (response === "success") {
        window.location.href = "account.php";
    } else {
        alert(response);
    }
}