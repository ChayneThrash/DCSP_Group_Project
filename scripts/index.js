$(document).ready(function () {
    $("#logout").on("click", logout);
});

function logout() {
    $.ajax({
        method: "POST",
        url: "ajax/logout.php",
        success: processLogoutResponse
    });
}

function processLogoutResponse() {
    window.location.href = "index.php";
}