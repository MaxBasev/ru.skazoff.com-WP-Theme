document.addEventListener('DOMContentLoaded', function () {
	const themeToggle = document.getElementById('theme-toggle');
	const themeToggleMobile = document.getElementById('theme-toggle-mobile');
	const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');

	// Проверяем сохраненную тему или системные настройки
	const currentTheme = localStorage.getItem('theme') ||
		(prefersDarkScheme.matches ? 'dark' : 'light');

	// Устанавливаем начальную тему
	document.documentElement.setAttribute('data-theme', currentTheme);
	updateThemeIcons(currentTheme);

	// Функция переключения темы
	function toggleTheme() {
		const currentTheme = document.documentElement.getAttribute('data-theme');
		const newTheme = currentTheme === 'light' ? 'dark' : 'light';

		document.documentElement.setAttribute('data-theme', newTheme);
		localStorage.setItem('theme', newTheme);
		updateThemeIcons(newTheme);
	}

	// Обработчики клика для обеих кнопок
	themeToggle?.addEventListener('click', toggleTheme);
	themeToggleMobile?.addEventListener('click', toggleTheme);

	// Обновление иконок
	function updateThemeIcons(theme) {
		const icons = document.querySelectorAll('.theme-toggle i, .theme-toggle-mobile i');
		icons.forEach(icon => {
			icon.className = theme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
		});
	}

	// Следим за системными настройками
	prefersDarkScheme.addListener((e) => {
		if (!localStorage.getItem('theme')) {
			const newTheme = e.matches ? 'dark' : 'light';
			document.documentElement.setAttribute('data-theme', newTheme);
			updateThemeIcons(newTheme);
		}
	});
});