var commentValid = false;


function parentcomment() {
    if (validateComment()) { 
        addParentComment();
        $('#comment').clear();
    } else {
        window.alert("Not a valid comment");
    }
}
function addTextArea(i, id) {
    var div = document.getElementById(i);
    if(div.innerHTML==""){
        div.innerHTML += "<textarea class='reply' id='childcomment"+i+"' placeholder='Reply'> </textarea>";
        div.innerHTML += "\n<br />";
        div.innerHTML += "<button style='float:right' onclick='childcomment("+id+","+i+")'>submit</button>";
    } else{
        div.innerHTML = "";
    }
}
function childcomment(id, i) {
    if (validateChildComment(i)) {
        addChildComment(id,i);
        $("#childcomment"+i).clear();
    } else {
        window.alert("Not a valid comment");
    }
}
function validateChildComment(i) {
    var comment = $("#childcomment"+i).val();
    commentValid = (comment.length !== 0);
    return commentValid;
}

function validateComment() {
    var comment = $("#comment").val();
    commentValid = (comment.length !== 0);
    return commentValid;
}

function deletecomment(id){
    if (window.confirm("Are you sure?")) {
        $.ajax({
            method: "POST",
            url: "ajax/deleteComment.php",
            data: {
                commentid: id,
            },
            success: processDeleteCommentResponse
        });
    }
}


function addChildComment(id,i) {
    $.ajax({
        method: "POST",
        url: "ajax/addComment.php",
        data: {
            comment: $("#childcomment"+i).val(), contentid: content_id, parentid: id,
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
        window.alert("Comment submitted successfully.");
        window.location.reload();
    } else {
        $("#submissionErrorMsg").text(response);
        $("#commentSubmissionErrorModal").modal("show");
    }
}

function processDeleteCommentResponse(response){
    if (response==="success"){
        window.alert("Comment deleted successfully.");
        window.location.reload();
    }else{
        window.alert(response);
    }
}