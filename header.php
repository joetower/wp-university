<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_University
 */

$post_id = get_the_ID(); // or the specific post ID you are working with
$layout_data = get_post_meta( $post_id, '_wp_university_layout_key', true );
?>

<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'wp-university' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-header__inner">
			<div class="site-header__site-branding">
				<?php
				// the_custom_logo();
				if ( is_front_page() && is_home() ) :
					?>
					<h1 class="site-header__title">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<?php include get_theme_file_path( '/inc/logo-svg.php' ); ?>
						</a>
					</h1>
					<?php
				else :
					?>
					<p class="site-header__title">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<?php include get_theme_file_path( '/inc/logo-svg.php' ); ?>
						</a>
					</p>
					<?php
				endif;
				$wp_university_description = get_bloginfo( 'description', 'display' );
				if ( $wp_university_description || is_customize_preview() ) :
					?>
					<p class="site-header__description"><?php echo $wp_university_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<?php endif; ?>
			</div><!-- .site-branding -->

			<nav id="site-header__navigation" class="main-navigation">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Open Menu', 'wp-university' ); ?></button>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
					)
				);
				?>
			</nav><!-- #site-navigation -->
		</div><!-- .site-header__inner -->
	</header><!-- #masthead -->

	<!-- .site-main -->

	<section class="site-main__wrapper" wp-layout="<?php print_r($layout_data); ?>">
		<div class="site-main__wrapper__inner">
			<main id="primary" class="site-main">
