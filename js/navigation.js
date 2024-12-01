document.addEventListener('DOMContentLoaded', function () {
	const elements = {
		menuToggle: document.querySelector('.menu-toggle'),
		mainNav: document.querySelector('.main-navigation'),
		searchToggle: document.querySelector('.search-toggle'),
		searchOverlay: document.querySelector('.search-overlay'),
		searchClose: document.querySelector('.search-close'),
		searchField: document.querySelector('.search-field'),
		scrollToTop: document.getElementById('scroll-to-top')
	};

	// Toggle Menu
	function toggleMenu() {
		elements.mainNav.classList.toggle('active');
		elements.menuToggle.classList.toggle('active');
		elements.menuToggle.setAttribute(
			'aria-expanded',
			elements.menuToggle.getAttribute('aria-expanded') === 'true' ? 'false' : 'true'
		);
	}

	// Toggle Search
	function toggleSearch(show) {
		elements.searchOverlay.classList[show ? 'add' : 'remove']('active');
		document.body.style.overflow = show ? 'hidden' : '';
		if (show) elements.searchField.focus();
	}

	// Scroll Handlers
	function handleScroll() {
		const scrolled = window.pageYOffset > 300;
		elements.scrollToTop.classList[scrolled ? 'add' : 'remove']('visible');
	}

	// Debounce function
	function debounce(func, wait) {
		let timeout;
		return function executedFunction(...args) {
			const later = () => {
				clearTimeout(timeout);
				func(...args);
			};
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
		};
	}

	// Event Listeners
	elements.menuToggle.addEventListener('click', toggleMenu);
	elements.searchToggle?.addEventListener('click', () => toggleSearch(true));
	elements.searchClose?.addEventListener('click', () => toggleSearch(false));
	window.addEventListener('scroll', debounce(handleScroll, 150));
	elements.scrollToTop.addEventListener('click', () => {
		window.scrollTo({ top: 0, behavior: 'smooth' });
	});

	// Close search on escape
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape' && elements.searchOverlay.classList.contains('active')) {
			toggleSearch(false);
		}
	});
}); 