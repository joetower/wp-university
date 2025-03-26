/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
SiteNav = ( () => {
	const siteNavigation = document.getElementById('site-header__navigation');
	const siteHeader = document.getElementById('masthead');
	const siteHeaderInner = document.querySelector('.site-header__inner');
	const wpAdminBar = document.getElementById('wpadminbar');
	const siteSubMenu = document.querySelectorAll('.sub-menu');
	const siteSubMenuButton = document.querySelectorAll('.menu-item__submenu-toggle');

	/* Mobile button */
	const button = siteNavigation.getElementsByTagName('button')[0];
	const menu = siteNavigation.getElementsByTagName('ul')[0];

	// Return early if the navigation doesn't exist.
	if (!siteNavigation) {
		return;
	}

	// Set the CSS variable --wp-university-header-height to the height of the site header.
	let siteHeaderHeight = siteHeader.offsetHeight;
	// If the admin bar is present, add its height to the site header height.
	if (wpAdminBar) {
		siteHeaderHeight += wpAdminBar.offsetHeight;
	}
	// Set the CSS variable --wp-university-header-height to the height of the site header.
	document.documentElement.style.setProperty('--wp-university-header-height', `${siteHeaderHeight}px`);

	// Adjust submenu position if it extends beyond the right edge of the site-header element.

	// This function will be debounced to avoid excessive calls.
	// It will wait for 100 milliseconds after the last call
	const debounce = (func, wait) => {
		let timeout;
		return (...args) => {
			clearTimeout(timeout);
			timeout = setTimeout(() => func.apply(this, args), wait);
		};
	};

	applySubmenuPosition = debounce(() => {
		siteSubMenu.forEach(submenu => {
				let rect = submenu.getBoundingClientRect();
				let headerRect = siteHeaderInner.getBoundingClientRect();

				// Traverse up the DOM tree to ensure the position is relative to the site header
				let parent = submenu.offsetParent;
				while (parent && parent !== siteHeaderInner) {
						const parentRect = parent.getBoundingClientRect();
						rect = {
								top: rect.top + parentRect.top,
								right: rect.right + parentRect.right,
								bottom: rect.bottom + parentRect.bottom,
								left: rect.left + parentRect.left,
								width: rect.width,
								height: rect.height
						};
						parent = parent.offsetParent;
				}

				if (rect.right > headerRect.right) {
						submenu.setAttribute('data-position', 'right');
				}
				if (rect.left < headerRect.left) {
						submenu.setAttribute('data-position', 'left');
				}
		});
	}, 100);

	// Set data property
	applySubmenuPosition();

	// Return early if the button doesn't exist.
	if (typeof button === 'undefined') {
		return;
	}

	// Hide menu toggle button if menu is empty and return early.
	if (typeof menu === 'undefined') {
		button.style.display = 'none';
		return;
	}

	if (!menu.classList.contains('nav-menu')) {
		menu.classList.add('nav-menu');
	}

	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	button.addEventListener('click', () => {
		siteNavigation.classList.toggle('toggled');

		if (button.getAttribute('aria-expanded') === 'true') {
			button.setAttribute('aria-expanded', 'false');
			button.innerText = 'Open Menu';
		} else {
			button.setAttribute('aria-expanded', 'true');
			button.innerText = 'Close Menu';
		}
	});

	// For all sub-menu ul elements, add aria-hidden and tabindex attributes.
	siteSubMenu.forEach(submenu => {
		submenu.setAttribute('aria-hidden', 'true');
	});

	// Toggle the sub-menu when the button is clicked.
	siteSubMenuButton.forEach(submenuButton => {
		// For all sub-menu button elements, add aria-expanded attribute.
		submenuButton.setAttribute('aria-expanded', 'false');

		submenuButton.addEventListener('click', function(event) {
			event.stopPropagation();
			const parentLi = this.closest('.menu-item-has-children');
			const submenu = parentLi.querySelector('.menu-item__submenu');
			const expanded = this.getAttribute('aria-expanded') === 'true' || false;

			console.log(this);
			if (expanded) {
				this.setAttribute('aria-expanded', 'false');
				submenu.setAttribute('aria-hidden', 'true');
			}
			else {
				this.setAttribute('aria-expanded', 'true');
				submenu.setAttribute('aria-hidden', 'false');
			}
		});
	});

	// Close any open submenu when tabbing past the active ul.
	document.addEventListener('keydown', (event) => {
		if (event.key === 'Tab') {
			const activeElement = document.activeElement;
			siteSubMenuButton.forEach(submenuButton => {
				const submenu = submenuButton.nextElementSibling;
				if (submenuButton.getAttribute('aria-expanded') === 'true' && !submenu.contains(activeElement) && activeElement !== submenuButton) {
					submenuButton.setAttribute('aria-expanded', 'false');
					submenu.setAttribute('aria-hidden', 'true');
				}
			});
		}
	
		// Close the submenu when the escape key is pressed.
		if (event.key === 'Escape') {
			console.log('Escape key pressed');
			siteSubMenuButton.forEach(submenuButton => {
				const submenu = submenuButton.nextElementSibling;
				if (submenuButton.getAttribute('aria-expanded') === 'true') {
					submenuButton.setAttribute('aria-expanded', 'false');
					submenu.setAttribute('aria-hidden', 'true');
					submenuButton.focus();
				}
			});
		}
	});

})();
