<?php 
/**
 * The template for displaying Error 404 page.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>
<div class="main-wrapper-item">

	<div class="page-content">
	<div class="container" id="error-404">
		<div class="row-fluid">
			<div id="content" class="span12">
				<div class="post">
					<div class="skepost ske-404-page">
						<div class="error-txt"><?php _e( 'Oops !!!', 'foxeed-lite' ); ?></div>
						<div class="error-txt-first">
							<img src="<?php echo get_template_directory_uri().'/images/imgo.jpg'; ?>" alt="<?php _e( '404 Image', 'foxeed-lite' ); ?>" />
						</div>
						<p class="fourzerofourtxt"><?php _e('It looks like nothing was found at this location.','foxeed-lite'); ?></p>
						<?php get_search_form(); ?>
					</div>
					<!-- skepost --> 
				</div>
				<!-- post -->
			</div>
			<!-- content --> 
		</div>
	</div>
	</div>
</div>	
<?php get_footer(); ?>