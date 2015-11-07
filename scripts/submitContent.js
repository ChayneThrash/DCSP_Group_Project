var titleValid = false;
var contentValid = false;
var projectSelected = false;
var languageSelected = false;

$(document).ready(function () {
    $("#title").on("input propertychange paste", validateTitle);
    $("#content").on("input propertychange paste", validateContent);
    $("#ProjectDropdown").change(validateProject);
    $("#LanguageDropdown").change(validateLanguage);
    $(":submit").prop("disabled", true);
    $(":submit").on("click", checkLogin);
});

function validateTitle() {
    var title = $("#title").val();
    if (!isTitleValid(title)) {
        $("#titleError").text("Make sure title is less than or equal to 50 characters long.")
        titleValid = false;
    } else {
        $("#titleError").text("");
        if ((title.length === 0)) { // Can't submit if field is empty.
            $(":submit").prop("disabled", true);
            titleValid = false;
        } else {
            $(":submit").prop("disabled", false);
            titleValid = true;
        }
    }
    changeButtonStatus();
}

function isTitleValid(title) {
    return (title.length <= 50);
}

function validateContent() {
    var content = $("#content").val();
    contentValid = (content.length != 0);
    changeButtonStatus();
}

function validateProject() {
    projectSelected = ($("#ProjectDropdown").val() !== "");
    changeButtonStatus();
}

function validateLanguage() {
    languageSelected = ($("#LanguageDropdown").val() !== "");
    changeButtonStatus();
}

function changeButtonStatus() {
    $(":submit").prop("disabled", getButtonStatus());
}

function getButtonStatus() {
    return !(titleValid && contentValid && projectSelected && languageSelected);
}

function checkLogin() {
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

function processContentSubmissionResponse(response) {
    if (response === "success") {
        window.location.href = "index.php";
    } else {
        $("#submissionErrorMsg").text(response);
        $("#contentSubmissionErrorModal").modal("show");
    }
}