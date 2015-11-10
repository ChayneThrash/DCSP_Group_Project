var titleValid = false;
var contentValid = false;
var projectSelected = false;
var languageSelected = false;

var projectNameValid = false;

$(document).ready(function () {
    $("#title").on("input propertychange paste", validateTitle);
    $("#content").on("input propertychange paste", validateContent);
    $("#projectName").on("input propertychange paste", validateProjectName);
    $("#ProjectDropdown").change(validateProjectDropdown);
    $("#LanguageDropdown").change(validateLanguage);
    $("#ContentSubmissionButton").prop("disabled", true);
    $("#ProjectSubmissionButton").prop("disabled", true);
    $("#ContentSubmissionButton").on("click", addContent);
    $("#ProjectSubmissionButton").on("click", addProject);
});

function validateTitle() {
    var title = $("#title").val();
    if (!isTitleValid(title)) {
        $("#titleError").text("Make sure title is less than or equal to 50 characters long.")
        titleValid = false;
    } else {
        $("#titleError").text("");
        if ((title.length === 0)) { // Can't submit if field is empty.
            titleValid = false;
        } else {
            titleValid = true;
        }
    }
    changeContentSubmitButtonStatus();
}

function isTitleValid(title) {
    return (title.length <= 50);
}

function validateProjectName() {
    var name = $("#projectName").val();
    if (!(name.length <= 50)) {
        $("#projectNameError").text("Make sure name is less than or equal to 50 characters long.")
        projectNameValid = false;
    } else {
        $("#projectNameError").text("");
        if ((name.length === 0)) { // Can't submit if field is empty.
            projectNameValid = false;
        } else {
            projectNameValid = true;
        }
    }
    changeProjectSubmitButtonStatus();
}

function validateContent() {
    var content = $("#content").val();
    contentValid = (content.length != 0);
    changeButtonStatus();
}

function validateProjectDropdown() {
    projectSelected = ($("#ProjectDropdown").val() !== "");
    changeContentSubmitButtonStatus();
}

function validateLanguage() {
    languageSelected = ($("#LanguageDropdown").val() !== "");
    changeContentSubmitButtonStatus();
}

function changeContentSubmitButtonStatus() {
    $("#ContentSubmissionButton").prop("disabled", getButtonStatus());
}

function changeProjectSubmitButtonStatus() {
    $("#ProjectSubmissionButton").prop("disabled", !(projectNameValid));
}

function getButtonStatus() {
    return !(titleValid && contentValid && projectSelected && languageSelected);
}

function addContent() {
    $.ajax({
        method: "POST",
        url: "ajax/addContent.php",
        data:
            {
                title: $("#title").val(), content: $("#content").val(), 
                projectId: $("#ProjectDropdown").val(), language: $("#LanguageDropdown").val()
            },
        success: processContentSubmissionResponse
    });
}


function addProject() {
    $.ajax({
        method: "POST",
        url: "ajax/addProject.php",
        data:
            {
                projectName: $("#projectName").val(), isPrivate: document.getElementById('projectSetting').checked
            },
        success: processProjectSubmissionResponse
    });
}

function processContentSubmissionResponse(response) {
    if (response === "success") {
        window.location.href = "index.php";
    } else {
        $("#submissionErrorMsg").text(response);
        $("#contentSubmissionErrorModal").modal("show");
    }
}

function processProjectSubmissionResponse(response) {
    var responseObj = jQuery.parseJSON(response);
    if (responseObj.status === "success") {
        $('#ProjectDropdown').append($("<option></option>").attr("value", responseObj.projectId).text(responseObj.projectName));
        $("#addProjectModal").modal("toggle");
    } else {
        $("#submissionErrorMsg").text(responseObj.status);
        $("#contentSubmissionErrorModal").modal("show");
    }
}