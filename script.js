const beginBtn = document.querySelector('.begin-btn');

beginBtn.addEventListener('click', redirect);

function redirect() {
	window.location = 'register.php';
}

