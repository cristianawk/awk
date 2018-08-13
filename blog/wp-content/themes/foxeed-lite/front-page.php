<?php

	get_header();
	
	get_template_part( 'includes/front', 'editor-content' );

	if( get_theme_mod('foxeed_lite_home_blog_sec', 'on') == 'on') { ?>
	<div id="front-content-box" >
		<div class="container">
			<div class="row-fluid">
				<div class="foxeed-top">
					<h2 class="top-title"><?php echo wp_kses_post(get_theme_mod('foxeed_lite_blogpage_heading', __('Blog','foxeed-lite'))); ?></h2>
					<span class="top-style"></span>
				</div>
			</div>

			<div class="front-blog-wrap row-fluid">
			<?php $foxeed_blogno = get_theme_mod('foxeed_lite_home_blog_num', '6');

				$foxeed_lite_latest_loop = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => $foxeed_blogno,'ignore_sticky_posts' => true ) );

			?>
				<?php if ( $foxeed_lite_latest_loop->have_posts() ) : ?>

				<!-- pagination here -->

					<!-- the loop -->
					<?php while ( $foxeed_lite_latest_loop->have_posts() ) : $foxeed_lite_latest_loop->the_post(); ?>
						<div class="span4">
							<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
							<?php the_excerpt(); ?>
							<div class="continue"><a href="<?php the_permalink(); ?>"><?php _e('Continue Reading','foxeed-lite');?></a></div>
						</div>
					<?php endwhile; ?>
					<!-- end of the loop -->

					<!-- pagination here -->

					<?php wp_reset_postdata(); ?>

				<?php else : ?>
					<p><?php _e( 'Sorry, no posts matched your criteria.', 'foxeed-lite' ); ?></p>
				<?php endif; ?>
			</div>
	 	</div>
	</div>
	<?php }

	get_template_part( 'includes/front', 'our-services-section' );
 	get_template_part( 'includes/front', 'clients-section' );

	get_footer();

?> 