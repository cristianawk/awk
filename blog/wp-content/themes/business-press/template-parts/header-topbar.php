<?php
if( get_theme_mod( 'business_press_display_top_bar', '1' ) == 1 )
{
?>
<div id="topbar" class="container-fluid">
	<div class="container">
		<div class="row pdt10">
		
			<div class="col-md-6">
				<?php
				if( get_theme_mod( 'business_press_tpbr_left_view', '1' ) != 3 )
				{
					if( get_theme_mod( 'business_press_tpbr_left_view', '1' ) == 1 )
					{
					?>
						<div class="topbar_ctmzr">
						<?php
						echo wp_kses_post( get_theme_mod( 'business_press_top_bar_left_content', '<p><span class="fa fa-phone"></span> ' . esc_attr__( 'Call:', 'business-press' ) . ' <a href="tel:0123456789">0123456789</a> | <span class="fa fa-at"></span> ' . esc_attr__( 'Email:', 'business-press' ) . ' <a href="mailto:info@example.com">info@example.com</a></p>' ) );
						?>
						</div>
					<?php
					}
					else
					{
					?>
						<p>
						
						<?php
						if( get_theme_mod( 'business_press_tpbr_lft_phne', esc_attr__( '0123456789', 'business-press' ) ) )
						{
						?>
							<span class="fa fa-phone"></span><?php esc_attr_e( ' Call: ', 'business-press' ) ?><a href="tel:<?php echo esc_attr( get_theme_mod( 'business_press_tpbr_lft_phne', esc_attr__( '0123456789', 'business-press' ) ) ); ?>"><?php echo esc_attr( get_theme_mod( 'business_press_tpbr_lft_phne', esc_attr__( '0123456789', 'business-press' ) ) ); ?></a>
						<?php
						}
						?>
						
						<?php
						if( get_theme_mod( 'business_press_tpbr_lft_phne', esc_attr__( '0123456789', 'business-press' ) ) && get_theme_mod( 'business_press_tpbr_lft_email', esc_attr__( 'info@example.com', 'business-press' ) ) )
						{
							esc_attr_e( ' | ', 'business-press' );
						}
						?>
						
						<?php
						if( get_theme_mod( 'business_press_tpbr_lft_email', esc_attr__( 'info@example.com', 'business-press' ) ) )
						{
						?>
							<span class="fa fa-at"></span><?php esc_attr_e( ' Email: ', 'business-press' ) ?><a href="mailto:<?php echo esc_attr( get_theme_mod( 'business_press_tpbr_lft_email', esc_attr__( 'info@example.com', 'business-press' ) ) ); ?>"><?php echo esc_attr( get_theme_mod( 'business_press_tpbr_lft_email', esc_attr__( 'info@example.com', 'business-press' ) ) ); ?></a>
						<?php
						}
						?>
						
						</p>
					<?php
					}
				}
				?>
			</div>
			
			<div class="col-md-6">
				<p class="fr-spsl iconouter">
				
					<?php
					if( class_exists( 'WooCommerce' ) )
					{
					?>
						<?php
						if( get_theme_mod( 'business_press_display_shop_link_top_bar', '1' ) == 1 )
						{
						?>
						<a title="<?php esc_attr_e( 'Shop', 'business-press' ); ?>" href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_shop_page_id' ) ) ); ?>"><span class="fa fa-shopping-bag bgtoph-icon-clr"></span></a>
						<?php
						}
						?>
						
						<?php
						if( get_theme_mod( 'business_press_display_cart_link_top_bar', '1' ) == 1 )
						{
						?>
						<a title="<?php esc_attr_e( 'Cart', 'business-press' ); ?>" href="<?php echo esc_url( get_permalink( get_option('woocommerce_cart_page_id') ) ); ?>"><span class="fa fa-shopping-cart bgtoph-icon-clr"></span></a>
						<?php
						}
						?>
						
						<?php
						if( get_theme_mod( 'business_press_display_myaccount_link_top_bar', '1' ) == 1 )
						{
						?>
						<a title="<?php esc_attr_e( 'My Account ', 'business-press' ); ?>" href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>"><span class="fa fa-user bgtoph-icon-clr"></span></a>
						<?php
						}
						?>
					
					<?php
					}
					?>
					
					<?php
					// Social link open in new tab or same.
					if( get_theme_mod( 's_link_open', '1' ) == 1 )
					{
						$s_link_tab = 'target="_blank"';
					}
					else
					{
						$s_link_tab = '';
					}

					if( get_theme_mod( 'business_press_display_sicons_top_bar', '1' ) == 1 )
					{
						if( get_theme_mod( 'business_press_social_profile_link_facebook', 'http://facebook.com' ) )
						{
						?>
							<a title="<?php esc_attr_e( 'Facebook', 'business-press' ); ?>" <?php echo $s_link_tab; ?> href="<?php echo esc_url( get_theme_mod( 'business_press_social_profile_link_facebook', 'http://facebook.com' ) ); ?>"><span class="fa fa-facebook bgtoph-icon-clr"></span></a>
						<?php
						}
						?>
						
						<?php
						if( get_theme_mod( 'business_press_social_profile_link_twitter', 'http://twitter.com' ) )
						{
						?>
							<a title="<?php esc_attr_e( 'Twitter', 'business-press' ); ?>" <?php echo $s_link_tab; ?> href="<?php echo esc_url( get_theme_mod( 'business_press_social_profile_link_twitter', 'http://twitter.com' ) ); ?>"><span class="fa fa-twitter bgtoph-icon-clr"></span></a>
						<?php
						}
						?>
						
						<?php
						if( get_theme_mod( 'business_press_social_profile_link_youtube', 'http://youtube.com' ) )
						{
						?>
							<a title="<?php esc_attr_e( 'YouTube', 'business-press' ); ?>" <?php echo $s_link_tab; ?> href="<?php echo esc_url( get_theme_mod( 'business_press_social_profile_link_youtube', 'http://youtube.com' ) ); ?>"><span class="fa fa-youtube bgtoph-icon-clr"></span></a>
						<?php
						}
						?>

						<?php
						if( get_theme_mod( 'social_profile_link_vk' ) )
						{
						?>
							<a title="<?php _e( 'VK', 'business-press' ); ?>" <?php echo $s_link_tab; ?> href="<?php echo esc_url( get_theme_mod( 'social_profile_link_vk' ) ); ?>"><span class="fa fa-vk bgtoph-icon-clr"></span></a>
						<?php
						}
						?>
						
						<?php
						if( get_theme_mod( 'business_press_social_profile_link_googleplus' ) )
						{
						?>
							<a title="<?php esc_attr_e( 'Google Plus', 'business-press' ); ?>" <?php echo $s_link_tab; ?> href="<?php echo esc_url( get_theme_mod( 'business_press_social_profile_link_googleplus' ) ); ?>"><span class="fa fa-google bgtoph-icon-clr"></span></a>
						<?php
						}
						?>
						
						<?php
						if( get_theme_mod( 'business_press_social_profile_link_linkedin' ) )
						{
						?>
							<a title="<?php esc_attr_e( 'Linkedin', 'business-press' ); ?>" <?php echo $s_link_tab; ?> href="<?php echo esc_url( get_theme_mod( 'business_press_social_profile_link_linkedin' ) ); ?>"><span class="fa fa-linkedin bgtoph-icon-clr"></span></a>
						<?php
						}
						?>
						
						<?php
						if( get_theme_mod( 'business_press_social_profile_link_pinterest' ) )
						{
						?>
							<a title="<?php esc_attr_e( 'Pinterest', 'business-press' ); ?>" <?php echo $s_link_tab; ?> href="<?php echo esc_url( get_theme_mod( 'business_press_social_profile_link_pinterest' ) ); ?>"><span class="fa fa-pinterest-p bgtoph-icon-clr"></span></a>
						<?php
						}
						?>

						<?php
						if( get_theme_mod( 'sprofile_link_instagram' ) )
						{
						?>
							<a title="<?php _e( 'Instagram', 'business-press' ); ?>" <?php echo $s_link_tab; ?> href="<?php echo esc_url( get_theme_mod( 'sprofile_link_instagram' ) ); ?>"><span class="fa fa-instagram bgtoph-icon-clr"></span></a>
						<?php
						}
						?>

						<?php
						if( get_theme_mod( 'sprofile_link_telegram' ) )
						{
						?>
							<a title="<?php _e( 'Telegram', 'business-press' ); ?>" <?php echo $s_link_tab; ?> href="<?php echo esc_url( get_theme_mod( 'sprofile_link_telegram' ) ); ?>"><span class="fa fa-telegram bgtoph-icon-clr"></span></a>
						<?php
						}
						?>

						<?php
						if( get_theme_mod( 'sprofile_link_snapchat' ) )
						{
						?>
							<a title="<?php _e( 'Snapchat', 'business-press' ); ?>" <?php echo $s_link_tab; ?> href="<?php echo esc_url( get_theme_mod( 'sprofile_link_snapchat' ) ); ?>"><span class="fa fa-snapchat bgtoph-icon-clr"></span></a>
						<?php
						}
						?>

						<?php
						if( get_theme_mod( 'sprofile_link_flickr' ) )
						{
						?>
							<a title="<?php _e( 'Flickr', 'business-press' ); ?>" <?php echo $s_link_tab; ?> href="<?php echo esc_url( get_theme_mod( 'sprofile_link_flickr' ) ); ?>"><span class="fa fa-flickr bgtoph-icon-clr"></span></a>
						<?php
						}
						?>

						<?php
						if( get_theme_mod( 'sprofile_link_reddit' ) )
						{
						?>
							<a title="<?php _e( 'Reddit', 'business-press' ); ?>" <?php echo $s_link_tab; ?> href="<?php echo esc_url( get_theme_mod( 'sprofile_link_reddit' ) ); ?>"><span class="fa fa-reddit bgtoph-icon-clr"></span></a>
						<?php
						}
						?>

						<?php
						if( get_theme_mod( 'sprofile_link_tumblr' ) )
						{
						?>
							<a title="<?php _e( 'Tumblr', 'business-press' ); ?>" <?php echo $s_link_tab; ?> href="<?php echo esc_url( get_theme_mod( 'sprofile_link_tumblr' ) ); ?>"><span class="fa fa-tumblr bgtoph-icon-clr"></span></a>
						<?php
						}
						?>

						<?php
						if( get_theme_mod( 'sprofile_link_yelp' ) )
						{
						?>
							<a title="<?php _e( 'Yelp', 'business-press' ); ?>" <?php echo $s_link_tab; ?> href="<?php echo esc_url( get_theme_mod( 'sprofile_link_yelp' ) ); ?>"><span class="fa fa-yelp bgtoph-icon-clr"></span></a>
						<?php
						}
						?>

						<?php
						if( get_theme_mod( 'sprofile_link_whatsappno' ) )
						{
						?>
							<a title="<?php _e( 'WhatsApp', 'business-press' ); ?>" <?php echo $s_link_tab; ?> href="whatsapp://send?text=Hi&phone=<?php echo esc_attr( get_theme_mod( 'sprofile_link_whatsappno' ) ); ?>&abid=<?php echo esc_attr( get_theme_mod( 'sprofile_link_whatsappno' ) ); ?>"><span class="fa fa-whatsapp bgtoph-icon-clr"></span></a>
						<?php
						}
						?>
						
						<?php
						if( get_theme_mod( 'business_press_social_profile_link_skype' ) )
						{
						?>
							<a title="<?php esc_attr_e( 'Skype', 'business-press' ); ?>" <?php echo $s_link_tab; ?> href="skype:<?php echo esc_attr( get_theme_mod( 'business_press_social_profile_link_skype' ) ); ?>?add"><span class="fa fa-skype bgtoph-icon-clr"></span></a>
						<?php
						}
					}

					if( get_theme_mod( 'top_bar_seach_icon', '1' ) == 1 )
					{
					?>
						<a id="scp-btn-search" title="<?php _e( 'Search Website', 'business-press' ); ?>" href="javascript:void(0)"><span class="fa fa-search bgtoph-icon-clr"></span></a>
					<?php
					}
					
					?>

				</p>

				<?php
				// Top bar search form container.
				if( get_theme_mod( 'top_bar_seach_icon', '1' ) == 1 )
				{
				?>
					<div class="scp-search">
						<button id="scp-btn-search-close" class="scp-btn scp-btn--search-close" aria-label="Close search form"><i class="fa fa-close"></i></button>
						<form class="scp-search__form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<input class="scp-search__input" name="s" type="search" placeholder="<?php esc_attr_e( 'Keywords...', 'business-press' ); ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" value="<?php echo get_search_query(); ?>" />
							<span class="scp-search__info"><?php _e( 'Hit enter to search or ESC to close', 'business-press' ); ?></span>
						</form>
					</div>
				<?php
				}
				?>

			</div>
			
		</div>
	</div>
</div>
<?php
}
?>