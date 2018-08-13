<?php
/**
 * Template Name: Full Width No Sidebar
 *
 */
?>

<?php get_header(); ?>
<div class="col-md-12">
	<div class="left-content" >
	<?php
	while( have_posts() ) : the_post();
		
		get_template_part( 'template-parts/content-page', 'full-width' );

		comments_template();
		
	endwhile;
	?>
	
	</div>
</div>
<?php get_footer(); ?>