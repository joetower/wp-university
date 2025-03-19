<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package WP_University
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function wp_university_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'wp_university_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function wp_university_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'wp_university_pingback_header' );


/**
 * Add a button element to menu items with children.
 *
 * @param string $item_output The menu item's starting HTML output.
 * @param WP_Post $item Menu item data object.
 * @param int $depth Depth of menu item. Used for padding.
 * @param array $args An array of wp_nav_menu() arguments.
 * @param int $id Current item ID.
 * @return string
 */
function wp_university_add_submenu_button( $item_output, $item, $depth, $args ) {
	if ( in_array( 'menu-item-has-children', $item->classes ) ) {
		$buttonContent = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M201.4 374.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 306.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z"/></svg>';
		$button = '<button class="menu-item__submenu-toggle" aria-expanded="false" data-menu-depth="' . $depth . '" aria-label="' . $item->title . ' submenu">' . __($buttonContent, 'wp_university' ) . '</button>';
		$item_output = str_replace( '</a>', '</a>' . $button, $item_output );
	}
	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'wp_university_add_submenu_button', 10, 4 );

function wp_university_submenu_class( $classes, $depth ) {
	if ( $depth > 0 ) {
		$classes[] = 'menu-item__submenu';
	}
	return $classes;
}
add_filter( 'nav_menu_submenu_css_class', 'wp_university_submenu_class', 10, 4 );

/**
 * Add custom class to WP Core List Block so we can style theme.
 *
 * Should not be necessary in future version of WP:
 * @see https://github.com/WordPress/gutenberg/issues/12420
 * @see https://github.com/WordPress/gutenberg/pull/42269
 */
add_filter( 'render_block', 'wp_university_add_class_to_list_block', 10, 2 );

 function wp_university_add_class_to_list_block( $block_content, $block ) {

	if ( strpos( $block['blockName'], 'core/' ) === 0 ) {
		$block_content = '<div class="wp-core-block-type">' . $block_content . '</div>';
	}
	return $block_content;
}

// Add a custom meta box to the post editor
// This meta box allows the user to select a layout option for the post/page
// The selected layout option is saved as post meta data
// The layout options are "constrained" and "wide"
// The default layout option is "constrained"
// The layout option is used to determine the layout of the post/page on the front end
// The layout option is saved as post meta data with the key "_wp_university_layout_key"
// The layout option is used to determine the layout of the post/page on the front end
add_action( 'add_meta_boxes', 'wp_university_add_layout_meta_box' );
function wp_university_add_layout_meta_box() {
	add_meta_box(
		'wp_university_layout_meta_box',
		__( 'Layout Options', 'wp-university' ),
		'wp_university_layout_meta_box_callback',
		array( 'post', 'page' ),
		'side',
		'default'
	);
}

function wp_university_layout_meta_box_callback( $post ) {
	wp_nonce_field( 'wp_university_save_layout_meta_box_data', 'wp_university_layout_meta_box_nonce' );

	$value = get_post_meta( $post->ID, '_wp_university_layout_key', true );

	echo '<label for="wp_university_layout_field">';
	_e( 'Select Layout:', 'wp-university' );
	echo '</label> ';
	echo '<select name="wp_university_layout_field" id="wp_university_layout_field">';
	echo '<option value="constrained"' . selected( $value, 'constrained', false ) . '>' . __( 'Constrained', 'wp-university' ) . '</option>';
	echo '<option value="wide"' . selected( $value, 'wide', false ) . '>' . __( 'Wide', 'wp-university' ) . '</option>';
	echo '</select>';

	// Set default value if no value is set
	if ( empty( $value ) ) {
		update_post_meta( $post->ID, '_wp_university_layout_key', 'constrained' );
	}
}

add_action( 'save_post', 'wp_university_save_layout_meta_box_data' );
function wp_university_save_layout_meta_box_data( $post_id ) {
	if ( ! isset( $_POST['wp_university_layout_meta_box_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['wp_university_layout_meta_box_nonce'], 'wp_university_save_layout_meta_box_data' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['wp_university_layout_field'] ) ) {
		return;
	}

	$layout_data = sanitize_text_field( $_POST['wp_university_layout_field'] );
	update_post_meta( $post_id,  '_wp_university_layout_key', $layout_data );
}


// Remove Gutenberg Block Library CSS from loading on the frontend
function big_sea_starter_remove_wp_block_library_css(){
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'wc-blocks-style' ); // Remove WooCommerce block CSS
} 
add_action( 'wp_enqueue_scripts', 'big_sea_starter_remove_wp_block_library_css', 100 );

// Gutenberg: limit allowed blocks in post editor
// The block editor adds too much inline CSS to the front end. We should be selective
// about which core blocks to allow in the post editor. This allowed block list will be updated as needed.
function big_sea_starter_allowed_post_type_blocks( $allowed_block_types, $editor_context ) {
if ( 'post' === $editor_context->post->post_type ) {
	return array(
		'core/paragraph',
		'core/image',
		'core/heading',
		'create-block/cta-block'
	);
}

if ( 'page' === $editor_context->post->post_type ) {
	return array(
		'core/paragraph',
		'core/image',
		'core/heading',
		'create-block/cta-block'
	);
}

return $allowed_block_types;
}

add_filter( 'allowed_block_types_all', 'big_sea_starter_allowed_post_type_blocks', 10, 2 );
