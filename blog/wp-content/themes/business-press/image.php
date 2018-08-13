<?php get_header(); ?>

<div class="col-md-8">
	<div class="left-content" >
		
		<?php if( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div <?php post_class('clearfix'); ?>>
			<div class="content-first">
			
				<div class="content-second">
					<h1 class="the-title entry-title" id="post-<?php the_ID(); ?>" ><?php the_title(); ?></h1>
				</div>
				
				<div class="content-third">
					<?php business_press_entry_meta(); ?>

					<div class="entry-content">
					
						<div class="entry-attachment">
						
						<?php echo wp_get_attachment_image( get_the_ID(), 'full', "", array() );  ?>
						
						<?php if ( has_excerpt() ) : ?>
							<div class="entry-caption">
								<?php the_excerpt(); ?>
							</div>
						<?php endif; ?>
						</div><!-- .entry-attachment -->

					
					<?php the_content(); ?>
					
					<?php
					wp_link_pages( array(
							'before'           => '<p class="pagelinks">' . esc_attr__( 'Pages:', 'business-press' ),
							'after'            => '</p>',
							'link_before'      => '<span class="pagelinksa">',
							'link_after'       => '</span>',
						)
					);
					?>
					
					</div>
				</div>

				<div class="content-first">
					<nav>
						<ul class="pager">
								
							<li class="previous">
								<?php next_image_link( false, esc_attr__( '&larr; Next Image', 'business-press' ) ); ?>
							</li>
								
							<li class="next">
								<?php previous_image_link( false, esc_attr__( 'Previous Image &rarr;', 'business-press' ) ); ?>
							</li>
								
						</ul>
					</nav>	
				</div>
				
			</div>
		</div>
		<?php endwhile; ?>
		<?php endif;  ?> 

	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>