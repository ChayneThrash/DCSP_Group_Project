var commentValid = false;

$(document).ready(function () {
    $('#comment').autoGrow();
   
});

function parentcomment() {
    if (validateComment()) { 
        addParentComment();
        $('#comment').clear();
    } else {
        window.alert("Not a valid comment");
    }
}

function childcomment() {
    if (validateComment()) {
        addChildComment();
        $('#childcomment').clear();
    } else {
        window.alert("Not a valid comment");
    }
}
function validateComment() {
    var comment = $("#comment").val();
    commentValid = (comment.length !== 0);
    return commentValid;
}


function addChildComment() {
    $.ajax({
        method: "POST",
        url: "ajax/addComment.php",
        data: {
            comment: $("#comment").val(), contentid: content_id, parentid: Number($('#parent').val())
        },
        success: processCommentSubmissionResponse
    });
}

function addParentComment() {
    $.ajax({
        method: "POST",
        url: "ajax/addComment.php",
        data: {
            comment: $('#comment').val(), contentid: content_id
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
