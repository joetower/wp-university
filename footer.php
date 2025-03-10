<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_University
 */

?>

	<footer id="colophon" class="site-footer">
		<div class="site-footer__inner">
			<div class="site-info">
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'wp-university' ) ); ?>">
					WP University
				</a>
			</div><!-- .site-info -->
		</div><!-- .site-footer__inner -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
