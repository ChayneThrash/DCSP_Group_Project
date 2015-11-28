var commentValid = false;
var parentValid=false;


$(document).ready(function () {
    $('#comment').autoGrow();
   
});


function comment() {
    if (validateComment() && parentValid) { //parentValid->validateParent() when working 
        window.alert("if statement executed");
        addComment();
        $('#comment').clear();
    } else if (validateComment() && !parentValid) {
        window.alert("else if statement executed");
        addParentComment();
        $('#comment').clear();
    }else{
        window.alert("Not a valid comment");
    }
}

function validateComment() {
    var comment = $("#comment").val();
    commentValid = (comment.length !== 0);
    return commentValid;
}

function validateParent(){
    var parentid=$('#parent').val();
    parentValid=(parentid !== null);
    return parentValid;
}

function addComment() {
    $.ajax({
        method: "POST",
        url: "ajax/addComment.php",
        data: {
            comment: $("#comment").val(), contentid: $('#contentid').val(), parentid: $('#parent').val()
        },
        success: processCommentSubmissionResponse
    });
}

function addParentComment() {
    $.ajax({
        method: "POST",
        url: "ajax/addComment.php",
        data: {
            comment: $('#comment').val(), contentid: $('#contentid').val()
        },
        success: processCommentSubmissionResponse
    });
}

function processCommentSubmissionResponse(response) {
    if (response === "success") {
        window.location.reload();
        window.alert("Comment successfully added");
    } else {
        $("#submissionErrorMsg").text(response);
        $("#commentSubmissionErrorModal").modal("show");
    }
}
