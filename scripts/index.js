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

function search_language() {
	var lang = document.getElementById("LanguageDropdown").value;
	window.location.href = "index.php?language=" + lang;
}

function search_project() {
	var project = document.getElementById("ProjectDropdown").value;
	window.location.href = "index.php?project=" + project;
}