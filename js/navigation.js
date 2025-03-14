/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
( function() {
	const siteNavigation = document.getElementById( 'site-header__navigation' );
	const siteHeader = document.getElementById( 'masthead' );
	const wpAdminBar = document.getElementById( 'wpadminbar' );
	const siteSubMenu = document.querySelectorAll( '.sub-menu' );
	const siteSubMenuButton = document.querySelectorAll('.menu-item__submenu-toggle');

	// Return early if the navigation doesn't exist.
	if ( ! siteNavigation ) {
		return;
	}

	// Set the CSS variable --wp-university-header-height to the height of the site header.
	siteHeaderHeight = siteHeader.offsetHeight;
	// If the admin bar is present, add its height to the site header height.
	if ( wpAdminBar ) {
		siteHeaderHeight += wpAdminBar.offsetHeight;
	}
	// Set the CSS variable --wp-university-header-height to the height of the site header.
	document.documentElement.style.setProperty( '--wp-university-header-height', siteHeaderHeight + 'px' );

	/* Mobile button */
	const button = siteNavigation.getElementsByTagName( 'button' )[ 0 ];

	// Return early if the button doesn't exist.
	if ( 'undefined' === typeof button ) {
		return;
	}

	const menu = siteNavigation.getElementsByTagName( 'ul' )[ 0 ];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	if ( ! menu.classList.contains( 'nav-menu' ) ) {
		menu.classList.add( 'nav-menu' );
	}

	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	button.addEventListener( 'click', function() {
		siteNavigation.classList.toggle( 'toggled' );

		if ( button.getAttribute( 'aria-expanded' ) === 'true' ) {
			button.setAttribute( 'aria-expanded', 'false' );
			button.innerText = 'Open Menu';
		} else {
			button.setAttribute( 'aria-expanded', 'true' );
			button.innerText = 'Close Menu';
		}
	} );

	// Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
	document.addEventListener( 'click', function( event ) {
		const isClickInside = siteNavigation.contains( event.target );

		if ( ! isClickInside ) {
			siteNavigation.classList.remove( 'toggled' );
			button.setAttribute( 'aria-expanded', 'false' );
		}
	} );


	// For all sub-menu ul elements, add aria-hidden and tabindex attributes.
	siteSubMenu.forEach( submenu => {
		submenu.setAttribute( 'aria-hidden', 'true' );
	});
	// For all sub-menu button elements, add aria-expanded attribute.
	siteSubMenuButton.forEach( submenuButton => {
		submenuButton.setAttribute( 'aria-expanded', 'false' );
	});
	// Toggle the sub-menu when the button is clicked.
	siteSubMenuButton.forEach( submenuButton => {
		submenuButton.addEventListener( 'click', function() {
			const submenu = this.nextElementSibling;
			const expanded = this.getAttribute( 'aria-expanded' ) === 'true' || false;

			this.setAttribute( 'aria-expanded', ! expanded );
			submenu.setAttribute( 'aria-hidden', expanded );
		});
	});

	// Close the submenu when tabbing beyond the active submenu.
	document.addEventListener( 'focusin', function( event ) {
		siteSubMenuButton.forEach( submenuButton => {
			const submenu = submenuButton.nextElementSibling;
			if ( ! submenu.contains( event.target ) && submenuButton.getAttribute( 'aria-expanded' ) === 'true' ) {
				submenuButton.setAttribute( 'aria-expanded', 'false' );
				submenu.setAttribute( 'aria-hidden', 'true' );
			}
		});
	});

}() );
