const beginBtn = document.querySelector('.begin-btn');

beginBtn.addEventListener('click', redirect);

function redirect() {
	window.location = 'register.php';
}

// const addNote = document.querySelector('#addNote');
// const noteCreate = document.querySelector('.noteCreate');

// addNote.addEventListener('click', function () {
// 	noteCreate.classList.remove('hidden');
// });
