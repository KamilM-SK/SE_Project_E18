function runResponsiveNavigation() {
	
	var checkDisplay = document.getElementById('sitelink__button');
	
	if (checkDisplay.style.display == 'none' || checkDisplay.style.display == '') {
		checkDisplay.style.display = 'block';
	}
	
	else {
		checkDisplay.style.display = 'none';
	}
}
