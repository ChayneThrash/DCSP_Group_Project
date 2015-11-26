var commentValid = false;

$(document).ready(function () {

    $('textarea').hide();
    $("textarea").on("click", comment);
});

function comment() {
    $('textarea').toggle();
}

function validateComment() {
    var comment = $("#comment").val();
    commentValid = (comment.length !== 0);
    changeButtonStatus();
}

function addComment() {
    $.ajax({
        method: "POST",
        url: "ajax/addComment.php",
        data:
            {
                title: $("#title").val(), comment: $("#comment").val(),
                projectId: $("#ProjectDropdown").val(), language: $("#LanguageDropdown").val()
            },
        success: processContentSubmissionResponse
    });
}
