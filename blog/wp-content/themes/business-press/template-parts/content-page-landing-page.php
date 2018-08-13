<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/CreativeWork">
			
	<div class="entry-content" itemprop="text">
		<?php the_content(); ?>
	</div>
					
	<?php
	wp_link_pages( array(
		'before'           => '<p class="pagelinks">' . esc_attr__( 'Pages:', 'business-press' ),
		'after'            => '</p>',
		'link_before'      => '<span class="pagelinksa">',
		'link_after'       => '</span>',
	) );
	?>
		
</div>
