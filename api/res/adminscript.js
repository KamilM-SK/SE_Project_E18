function runResponsiveNavigation() {
	
	var checkDisplay = document.getElementById('sitelink__button');
	
	if (checkDisplay.style.display == 'none' || checkDisplay.style.display == '') {
		checkDisplay.style.display = 'block';
	}
	
	else {
		checkDisplay.style.display = 'none';
	}
}

function checkPasswordMatch() {
    var password = $("#txtNewPassword").val();
    var confirmPassword = $("#txtConfirmPassword").val();

    if (password != confirmPassword) {
		$("#divCheckPasswordMatch").html("Passwords do not match!");
		$("#sender").prop('disabled', true);
	}
    else {
		$("#divCheckPasswordMatch").html("Passwords match.");
		$("#sender").prop('disabled', false);
	}
}

$(document).ready(function () {
   $("#txtConfirmPassword").keyup(checkPasswordMatch);
});