<?php 
if ( get_theme_mod('foxeed_lite_home_client_sec', 'on') == 'on' ) {
$imagepath =  get_template_directory_uri() . '/images/';
?>
<!-- full-client-box -->
<div id="full-client-box">
	<div class="container">
		<div class="row-fluid">
			<div class="row-fluid foxeed-top">
				<h2 class="top-title"><?php echo esc_attr( get_theme_mod('foxeed_lite_home_client_sec_title', __('TRUSTED CLIENTS WE GOT', 'foxeed-lite') ) ); ?></h2>
				<span class="top-style"></span>
				<div class="top-description"><?php echo do_shortcode( wp_kses_post( get_theme_mod('foxeed_lite_home_client_sec_desc', __('Lorem ipsum dolor sit amet, constectuter la elet de', 'foxeed-lite') ) ) ); ?></div>
			</div>
			<div class="row-fluid foxeed-bottom">
				<ul id="client-logos" class="clearfix">
					<li class="item span3 client1">
						<img alt="<?php _e('Client Logo', 'foxeed-lite'); ?>" src="<?php echo esc_url( get_theme_mod('foxeed_lite_home_client1_img', $imagepath.'client-logo.png') ); ?>" />
					</li>
					<li class="item span3 client2">
						<img alt="<?php _e('Client Logo', 'foxeed-lite'); ?>" src="<?php echo esc_url( get_theme_mod('foxeed_lite_home_client2_img', $imagepath.'client-logo.png') ); ?>" />
					</li>
					<li class="item span3 client3">
						<img alt="<?php _e('Client Logo', 'foxeed-lite'); ?>" src="<?php echo esc_url( get_theme_mod('foxeed_lite_home_client3_img', $imagepath.'client-logo.png') ); ?>" />
					</li>
					<li class="item span3 client4">
						<img alt="<?php _e('Client Logo', 'foxeed-lite'); ?>" src="<?php echo esc_url( get_theme_mod('foxeed_lite_home_client4_img', $imagepath.'client-logo.png') ); ?>" />
					</li>
				</ul>
			</div><!-- /our-clients -->
		</div>
	</div>
</div>
<?php } ?>