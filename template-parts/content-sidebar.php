<?php
/**
 * Template part for displaying dynamic sidebar
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WP_University
 */

?>

<?php if (is_dynamic_sidebar()) : ?>
	<section class="site-main__blocks"> 
		<div class="site-main__blocks__inner">
			<?php get_sidebar(); ?>
		</div>
	</section>
<?php endif; ?>
