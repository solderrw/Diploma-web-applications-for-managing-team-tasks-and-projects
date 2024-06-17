const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

allSideMenu.forEach(item=> {
	const li = item.parentElement;

	item.addEventListener('click', function () {
		allSideMenu.forEach(i=> {
			i.parentElement.classList.remove('active');
		})
		li.classList.add('active');
	})
});




// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
})







const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');







if(window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {

	searchForm.classList.remove('show');
}


window.addEventListener('resize', function () {
	if(this.innerWidth > 576) {
		
		searchForm.classList.remove('show');
	}
})




// Получаем ссылку на переключатель
const switchMode = document.getElementById('switch-mode');

// Получаем ссылку на body, чтобы добавлять класс для изменения темы
const body = document.body;

// Функция для изменения темы
function toggleTheme() {
  if (switchMode.checked) {
    // Если переключатель включен (темная тема), добавляем класс 'dark'
    body.classList.add('dark');
  } else {
    // Если переключатель выключен (светлая тема), удаляем класс 'dark'
    body.classList.remove('dark');
  }
}

// Устанавливаем обработчик события 'change' на переключатель
switchMode.addEventListener('change', toggleTheme);

// При загрузке страницы также проверяем состояние переключателя и применяем тему
toggleTheme();
