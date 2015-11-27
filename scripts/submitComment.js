var commentValid = false;

$(document).ready(function () {
    $('#comment').autoGrow();

   
});


function comment() {
    if (validateComment()) {
        addComment();
    }
}

function validateComment() {
    var comment = $("#comment").val();
    commentValid = (comment.length != 0);
    return commentValid;
}

function addComment() {
    $.ajax({
        method: "POST",
        url: "ajax/addComment.php",
        data:
            {
                comment: $("#comment").val(),
            },
        success: processCommentSubmissionResponse
    });
}

function processCommentSubmissionResponse(response) {
    if (response === "success") {
        window.location.reload();
    } else {
        $("#submissionErrorMsg").text(response);
        $("#commentSubmissionErrorModal").modal("show");
    }
}
