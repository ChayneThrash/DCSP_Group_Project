﻿var resetPwdSecurityQuestionSelected = !resetPwdNeedsNewSecurityQuestion(); 
var resetPwdSecurityQuestionAnswered = false;
var resetPwdValidator = new NewPasswordValidator("#resetPwdPwd", "#resetPwdPwdConf");

var changePwdCurrentPwdValid = false;
var changePwdValidator = new NewPasswordValidator("#changePwdPwd", "#changePwdPwdConf");

var changeSecurityQuestionSelected = false;
var changeSecurityQuestionAnswerValid = false;

var memberListSelected = false;
var ownerListSelected = false;

var userToAddFormNotEmpty = false;

var projectSelected = "";

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

    $("#changePwdCurrentPwd").on("input propertychange paste", changePwdValidateCurrentPwd);
    $("#changePwdPwd").on("input propertychange paste", changePwdValidatePwd);
    $("#changePwdPwdConf").on("input propertychange paste", changePwdValidatePwdConf);
    $("#ChangePwdSubmissionButton").prop("disabled", true);
    $("#ChangePwdSubmissionButton").on("click", changePassword);

    $("#deleteAccount").on("click", deleteAccount);

    $("#newSecurityQuestionAnswer").on("input propertychange paste", validateChangeSecurityQuestionAnswer);
    $("#newSecurityQuestionDropdown").change(validateChangeSecurityQuestion);
    $("#ChangeSecurityQuestionSubmissionButton").prop("disabled", true);
    $("#ChangeSecurityQuestionSubmissionButton").on("click", changeSecurityQuestion);

    $('#ManageProjectList li a').on('click', function() {
        $("#ProjectNameHeading").text("Project: " + $(this).html());
        $("#membersColumn").removeClass();
        $("#ownersColumn").removeClass();
        if ($(this).attr("data-private") === "0") {
            $("#makePublicButton").hide();
            $("#makePrivateButton").show();
            $("#membersColumn").hide();
            $("#ownersColumn").addClass("col-sm-8");
        } else {
            $("#makePrivateButton").hide();
            $("#makePublicButton").show();
            $("#membersColumn").show();
            $("#membersColumn").addClass("col-sm-6");
            $("#ownersColumn").addClass("col-sm-6");
        }
        $("#members").empty();
        $("#owners").empty();
        getMembers($(this).html());
        projectSelected = $(this).html();
    });

    $(".memberSelectButton").prop('disabled', true);
    $(".ownerSelectButton").prop('disabled', true);

    $("#addUserToProjectButton").on('click', function () {
        $("#addUserToProjectModal").modal('show');
        $("#ownerCheckbox").prop('checked', false);
    });

    $("#addOwnerToProjectButton").on('click', function () {
        $("#addUserToProjectModal").modal('show');
        $("#ownerCheckbox").prop('checked', true);
    });

    $("#userToAddToProject").on("input propertychange paste", validateNewProjectMemberUsername);
    $("#addUserToProjectSubmissionButton").on('click', addUserToProject);

    $("#members").on('change', validateMembersSelect);
    $("#owners").on('change', validateOwnersSelect);

    $('[data-toggle="tooltip"]').tooltip();
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
    changeResetPwdButtonStatus();
}

function changePwdValidatePwd() {
    changePwdValidator.validateNewPwd();
    changePwdValidatePwdConf();
}

function changePwdValidatePwdConf() {
    changePwdValidator.validateNewPwdConf();
    if (!changePwdValidator.pwdConfValid) {
        $("#changePwdError").text("Make sure that both passwords match");
    } else {
        $("#changePwdError").text("");
    }
    changeChangePwdButtonStatus();
}

function changePwdValidateCurrentPwd() {
    changePwdCurrentPwdValid = ($("#changePwdCurrentPwd").length != 0);
    changeChangePwdButtonStatus();
}

function changeResetPwdButtonStatus() {
    $("#ResetPwdSubmissionButton").prop("disabled", isResetPwdButtonDisabled());
}

function isResetPwdButtonDisabled() {
    return !(resetPwdValidator.pwdValid && resetPwdValidator.pwdConfValid && resetPwdSecurityQuestionSelected && resetPwdSecurityQuestionAnswered);
}

function changeChangePwdButtonStatus() {
    $("#ChangePwdSubmissionButton").prop("disabled", isChangePwdButtonDisabled());
}

function isChangePwdButtonDisabled() {
    return !(changePwdValidator.pwdValid && changePwdValidator.pwdConfValid && changePwdCurrentPwdValid);
}

function changeChangeSecurityQuestionButtonStatus() {
    $("#ChangeSecurityQuestionSubmissionButton").prop("disabled", isChangeSecurityQuestionButtonDisabled());
}

function isChangeSecurityQuestionButtonDisabled() {
    return !(changeSecurityQuestionSelected && changeSecurityQuestionAnswerValid);
}

function validateResetPwdSecurityQuestion() {
    resetPwdSecurityQuestionSelected = ($("#ResetPwdSecurityQuestionDropdown").val() !== "");
    changeResetPwdButtonStatus();
}

function validateResetPwdSecurityQuestionAnswer() {
    resetPwdSecurityQuestionAnswered = ($("#resetPwdSecurityQuestionAnswer").val().length !== 0);
    changeResetPwdButtonStatus();
}

function validateChangeSecurityQuestion() {
    changeSecurityQuestionSelected = ($("#newSecurityQuestionDropdown").val() !== "");
    changeChangeSecurityQuestionButtonStatus();
}

function validateChangeSecurityQuestionAnswer() {
    changeSecurityQuestionAnswerValid = ($("#newSecurityQuestionAnswer").val().length !== 0);
    changeChangeSecurityQuestionButtonStatus();
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

function changePassword() {
    $.ajax({
        method: "POST",
        url: "ajax/changePassword.php",
        data: 
            {
                currentPassword: $("#changePwdCurrentPwd").val(), newPassword: $("#changePwdPwd").val(),
                newPasswordConf: $("#changePwdPwdConf").val()
            },
        success: processResetPwdResponse
    });
}

function processChangePwdResponse(response) {
    if (response === "success") {
        window.location.href = "account.php";
    } else {
        alert(response);
    }
}

function deleteAccount() {
    if (window.confirm("Are you sure?")) {
        $.ajax({
            method: "POST",
            url: "ajax/deleteAccount.php",
            success: processDeleteAccountResponse
        });
    }
}

function processDeleteAccountResponse(response) {
    if (response === "success") {
        window.location.href = "index.php";
    } else {
        alert(response);
    }
}

function changeSecurityQuestion() {
    $.ajax({
        method: "POST",
        url: "ajax/changeSecurityQuestion.php",
        data: 
            {
                securityQuestion: $("#newSecurityQuestionDropdown").val(), answer: $("#newSecurityQuestionAnswer").val()
            },
        success: processChangeSecurityQuestionResponse
    });
}

function processChangeSecurityQuestionResponse(response) {
    if (response === "success") {
        window.location.href = "index.php";
    } else {
        alert(response);
    }
}

function getMembers(project_name) {
    $.ajax({
        method: "POST",
        url: "ajax/getProjectMembers.php",
        data:
            {
                projectName: project_name
            },
        success: processChangeSecurityQuestionResponse
    });
}

function processChangeSecurityQuestionResponse(response) {
    var responseObj = jQuery.parseJSON(response);
    $(".projectOwnerButtons").hide();
    if (responseObj.status === "success") {
        $.each(responseObj.members, function (i, item) {
            $('#members').append($("<option></option>").attr("disabled", (item === responseObj.username)).text(item));

        });
        $.each(responseObj.owners, function (i, item) {
            $('#owners').append($("<option></option>").attr("disabled", (item === responseObj.username)).text(item));
        });
        if (responseObj.isUserAdmin === true) {
            $(".projectOwnerButtons").show();
        }
    } else {
        alert(responseObj.status);
    }
}

function validateMembersSelect() {
    memberListSelected = ($("#members option:selected").length > 0);
    changeMemberButtonsStats();
}

function validateOwnersSelect() {
    ownerListSelected = ($("#owners option:selected").length > 0);
    changeOwnerButtonsStatus();
}

function changeMemberButtonsStats() {
    $(".memberSelectButton").prop('disabled', !memberListSelected);
}

function changeOwnerButtonsStatus() {
    $(".ownerSelectButton").prop('disabled', !ownerListSelected);
}

function validateNewProjectMemberUsername() {
    userToAddFormNotEmpty = ($("#userToAddToProject").val().length != 0);
    changeAddUserToProjectButtonStatus();
}

function changeAddUserToProjectButtonStatus() {
    $("#addUserToProjectSubmissionButton").prop('disabled', !userToAddFormNotEmpty);
}

function addUserToProject() {
    $.ajax({
        method: "POST",
        url: "ajax/addUserToProject.php",
        data:
            {
                projectName: projectSelected, username: $('#userToAddToProject').val(), isOwner: document.getElementById('ownerCheckbox').checked
            },
        success: processAddUserToProjectResponse
    });
}

function processAddUserToProjectResponse(response) {
    if (response !== "success") {
        alert(response);
    }
    window.location.href = "account.php";
}