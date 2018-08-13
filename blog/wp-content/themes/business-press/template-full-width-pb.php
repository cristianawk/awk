<?php
/**
 * Template Name: Full Width for Page Builder
 *
 */
?>

<?php get_header( 'pb' ); ?>

	<?php
	while( have_posts() ) : the_post();
		
		get_template_part( 'template-parts/content-page', 'full-width-pb' );

		comments_template();
		
	endwhile;
	?>

<?php get_footer( 'pb' ); ?>