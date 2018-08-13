<?php
/**
* The Header for our theme.
*/
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>
  <body <?php body_class(); ?> >
	<div id="wrapper" class="skepage">
		<div id="header_wrap">
			<!-- Head Topbar Section Starts -->
			<div id="header-top" class="clearfix">
				<div class="container">      
					<div class="row-fluid"> 
						<!-- Head Topbar Left Section Starts -->
						<div class="span8 social_icon">
						<!-- Social Links Section -->
								<ul class="clearfix">
									<?php if( get_theme_mod( 'foxeed_lite_pinterest_url', '') != '' ) { ?>
										<li class="pintrest-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod( 'foxeed_lite_pinterest_url') ); ?>"><span class="fa fa-pinterest" title="<?php _e('pinterest', 'foxeed-lite'); ?>"></span></a></li>
									<?php } if( get_theme_mod( 'foxeed_lite_twitter_url', '') != '' ) { ?>
										<li class="twitter-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod( 'foxeed_lite_twitter_url') ); ?>"><span class="fa fa-twitter" title="<?php _e('twitter', 'foxeed-lite'); ?>"></span></a></li>
									<?php } if( get_theme_mod( 'foxeed_lite_tumblr_url', '') != '' ) { ?>
										<li class="tumblr-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod( 'foxeed_lite_tumblr_url') ); ?>"><span class="fa fa-tumblr" title="<?php _e('tumblr', 'foxeed-lite'); ?>"></span></a></li>
									<?php } if( get_theme_mod( 'foxeed_lite_facebook_url', '') != '' ) { ?>
										<li class="facebook-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod( 'foxeed_lite_facebook_url') ); ?>"><span class="fa fa-facebook" title="<?php _e('facebook', 'foxeed-lite'); ?>"></span></a></li>
									<?php } if( get_theme_mod( 'foxeed_lite_skype_url', '') != '' ) { ?>
										<li class="skype-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod( 'foxeed_lite_skype_url') ); ?>"><span class="fa fa-skype" title="<?php _e('skype', 'foxeed-lite'); ?>"></span></a></li>
									<?php } if( get_theme_mod( 'foxeed_lite_instagram_url', '') != '' ) { ?>
										<li class="instagram-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod( 'foxeed_lite_instagram_url') ); ?>"><span class="fa fa-instagram" title="<?php _e('instagram', 'foxeed-lite'); ?>"></span></a></li>
									<?php } if( get_theme_mod( 'foxeed_lite_vk_url', '') != '' ) { ?>
										<li class="vk-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod( 'foxeed_lite_vk_url') ); ?>"><span class="fa fa-vk" title="<?php _e('vk', 'foxeed-lite'); ?>"></span></a></li>
									<?php } if( get_theme_mod( 'foxeed_lite_whatsapp_url', '') != '' ) { ?>
										<li class="whatsapp-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod( 'foxeed_lite_whatsapp_url') ); ?>"><span class="fa fa-whatsapp" title="<?php _e('whatsapp', 'foxeed-lite'); ?>"></span></a></li>
									<?php } if( get_theme_mod( 'foxeed_lite_linkedin_url', '') != '' ) { ?>
										<li class="linkedin-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod( 'foxeed_lite_linkedin_url') ); ?>"><span class="fa fa-linkedin" title="<?php _e('linkedin', 'foxeed-lite'); ?>"></span></a></li>
									<?php } if( get_theme_mod( 'foxeed_lite_googleplus_url', '') != '' ) { ?>
										<li class="googleplus-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod( 'foxeed_lite_googleplus_url') ); ?>"><span class="fa fa-google-plus" title="<?php _e('googleplus', 'foxeed-lite'); ?>"></span></a></li>
									<?php } if( get_theme_mod( 'foxeed_lite_flickr_url', '') != '' ) { ?>
										<li class="flickr-icon"><a target="_blank" href="<?php echo esc_url( get_theme_mod( 'foxeed_lite_flickr_url') ); ?>"><span class="fa fa-flickr" title="<?php _e('flickr', 'foxeed-lite'); ?>"></span></a></li>
									<?php } ?>
								</ul>
						</div>
						<!-- Social Links Section -->
						<!-- Head Topbar Left Section Ends -->

						<!-- Head Topbar Right Section Starts -->
						<div class="span4 language-flags">
							<!-- Language Flag Icons Section Ends -->
							<div class="hsearch" > <?php get_search_form(); ?> </div>
						</div>
						<!-- Head Topbar Right Section Ends -->
					</div>
					
				</div>
			</div>
			<!-- Head Topbar Section Ends -->

			<!-- Head Navigation Section Starts-->
			<div id="header-nav" class="skehead-headernav clearfix">
				<div class="glow">
					<div id="skehead">
						<div class="container">      
							<div class="row-fluid header-inner">      
								<!-- #logo -->
								<div id="logo" class="span4">
									<?php if ( get_theme_mod( 'foxeed_lite_logo_img' ) ) { ?>
										<div class="logo_inner">
											<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo('name'); ?>"><img class="logo" src="<?php echo esc_url( get_theme_mod( 'foxeed_lite_logo_img' ) ); ?>" alt="<?php bloginfo('name') ?>" /></a>
										</div>
									<?php } else{ ?>
									<!-- #description -->
										<div id="site-title" class="logo_desp logo_inner">
											<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo('name') ?>" ><?php bloginfo('name'); ?></a>
											<div id="site-description"><?php bloginfo( 'description' ); ?></div>
										</div>
									<!-- #description -->
									<?php } ?>
								</div>
								<!-- #logo -->
								
								<!-- .top-nav-menu --> 
								<div class="top-nav-menu span8">
										<?php 
											if( has_nav_menu( 'Header' ) ) {
												wp_nav_menu(array( 'container_class' => 'foxeed-menu', 'container_id' => 'skenav', 'menu_id' => 'menu-main','theme_location' => 'Header' )); 
											} else {
										?>
										<div class="foxeed-menu" id="skenav">
											<ul id="menu-main" class="menu">
												<?php wp_list_pages('title_li=&depth=0'); ?>
											</ul>
										</div>
										<?php } ?>
								</div>
								<!-- .top-nav-menu --> 
							</div>
						</div>
					</div>
					<!-- #skehead -->
				</div>
				<!-- glow --> 
			</div>
			<!-- Head Navigation Section Ends-->
			<div class="header-clone"></div>

		</div>
		<!-- #header-wrap -->

	<!-- header image section -->
  	<?php get_template_part( 'includes/front', 'bgimage-section' ); ?>
	
	<div id="main" class="clearfix">