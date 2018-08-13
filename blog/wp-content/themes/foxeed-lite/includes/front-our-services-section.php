<?php if( get_theme_mod( 'foxeed_lite_home_service_sec', 'on') == 'on' ) { ?>
<div id="front-our-services">
	<div class="container our-services-container">
		<div class="row-fluid foxeed-top">
			<h2 class="top-title"><?php echo get_theme_mod( 'foxeed_lite_home_service_sec_title', __('Our Services','foxeed-lite')); ?></h2>
			<span class="top-style"></span>
			<div class="top-description"><?php echo get_theme_mod( 'foxeed_lite_home_service_sec_desc', __('Lorem ipsum dolor sit amet, conscteture edipiscing elit.','foxeed-lite')); ?></div>
		</div>
		<div class="row-fluid foxeed-bottom">
			<ul class="mid-box-wrap row-fluid">
				<?php dynamic_sidebar( 'Home Featured Sidebar' ); ?>	
				<div class="clearfix"></div>
			</ul>
		</div>
	</div>
</div>
<?php } ?>