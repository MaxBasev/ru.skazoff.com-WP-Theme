document.addEventListener('DOMContentLoaded', function () {
	const toc = document.querySelector('.table-of-contents');
	
	if (!toc) return;
	
	const tocLinks = toc.querySelectorAll('.toc-item a');
	const headings = [];
	
	// Collect all headings with IDs
	tocLinks.forEach(link => {
		const id = link.getAttribute('href').substring(1);
		const heading = document.getElementById(id);
		if (heading) {
			headings.push({
				id: id,
				element: heading,
				link: link.parentElement
			});
		}
	});
	
	// Highlight active section on scroll
	function highlightActiveSection() {
		const scrollPosition = window.scrollY + 100;
		
		let activeHeading = null;
		
		// Find the current active heading
		for (let i = headings.length - 1; i >= 0; i--) {
			if (headings[i].element.offsetTop <= scrollPosition) {
				activeHeading = headings[i];
				break;
			}
		}
		
		// Update active class
		headings.forEach(heading => {
			heading.link.classList.remove('active');
		});
		
		if (activeHeading) {
			activeHeading.link.classList.add('active');
		}
	}
	
	// Debounce function for scroll performance
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
	
	// Listen to scroll events
	window.addEventListener('scroll', debounce(highlightActiveSection, 100));
	
	// Initial highlight
	highlightActiveSection();
	
	// Smooth scroll to anchor (fallback for older browsers)
	tocLinks.forEach(link => {
		link.addEventListener('click', function (e) {
			e.preventDefault();
			const targetId = this.getAttribute('href').substring(1);
			const target = document.getElementById(targetId);
			
			if (target) {
				const offsetTop = target.getBoundingClientRect().top + window.pageYOffset - 20;
				window.scrollTo({
					top: offsetTop,
					behavior: 'smooth'
				});
				
				// Update URL without jumping
				history.pushState(null, null, '#' + targetId);
			}
		});
	});

	// Collapsible behavior: default collapsed
	const toggles = toc.querySelectorAll('.toc-toggle');
	const h2Items = toc.querySelectorAll('.toc-item.toc-h2');

	// Collapse all at start
	h2Items.forEach((li) => {
		const sublist = li.querySelector('.toc-sublist');
		const toggle = li.querySelector('.toc-toggle');
		if (sublist && toggle) {
			sublist.hidden = true;
			toggle.setAttribute('aria-expanded', 'false');
		}
	});

	// Toggle handlers
	toggles.forEach((btn) => {
		btn.addEventListener('click', () => {
			const li = btn.closest('.toc-item.toc-h2');
			const sublist = li?.querySelector('.toc-sublist');
			if (!sublist) return;
			const expanded = btn.getAttribute('aria-expanded') === 'true';
			btn.setAttribute('aria-expanded', String(!expanded));
			sublist.hidden = expanded;
		});
	});
});

