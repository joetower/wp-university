<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
	<?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package WP_University
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses wp_university_header_style()
 */
function wp_university_custom_header_setup() {
	add_theme_support(
		'custom-header',
		apply_filters(
			'wp_university_custom_header_args',
			array(
				'default-image'      => '',
				'default-text-color' => '000000',
				'width'              => 1000,
				'height'             => 250,
				'flex-height'        => true,
			)
		)
	);
}
add_action( 'after_setup_theme', 'wp_university_custom_header_setup' );
