document.addEventListener('DOMContentLoaded', function () {
	const themeToggle = document.getElementById('theme-toggle');
	const themeToggleMobile = document.getElementById('theme-toggle-mobile');
	const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');

	// Check saved theme or system preferences
	const currentTheme = localStorage.getItem('theme') ||
		(prefersDarkScheme.matches ? 'dark' : 'light');

	// Set initial theme
	document.documentElement.setAttribute('data-theme', currentTheme);
	updateThemeIcons(currentTheme);

	// Theme toggle function
	function toggleTheme() {
		const currentTheme = document.documentElement.getAttribute('data-theme');
		const newTheme = currentTheme === 'light' ? 'dark' : 'light';

		document.documentElement.setAttribute('data-theme', newTheme);
		localStorage.setItem('theme', newTheme);
		updateThemeIcons(newTheme);
	}

	// Click handlers for both buttons
	themeToggle?.addEventListener('click', toggleTheme);
	themeToggleMobile?.addEventListener('click', toggleTheme);

	// Update icons
	function updateThemeIcons(theme) {
		const icons = document.querySelectorAll('.theme-toggle i, .theme-toggle-mobile i');
		icons.forEach(icon => {
			icon.className = theme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
		});
	}

	// Watch for system preferences changes
	prefersDarkScheme.addListener((e) => {
		if (!localStorage.getItem('theme')) {
			const newTheme = e.matches ? 'dark' : 'light';
			document.documentElement.setAttribute('data-theme', newTheme);
			updateThemeIcons(newTheme);
		}
	});
});