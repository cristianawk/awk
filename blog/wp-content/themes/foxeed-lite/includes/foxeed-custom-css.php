<?php

$imagepath =  get_template_directory_uri() . '/images/';

$_background_image_size	= esc_attr(get_theme_mod('background_size', 'cover'));
$_primary_color_scheme	= esc_attr(get_theme_mod('foxeed_lite_pri_color', '#f98b6e') );
$_secondary_color_scheme= esc_attr(get_theme_mod('foxeed_lite_sec_color', '#2eb9d0') );
$_persistent_on_off		= esc_attr(get_theme_mod('foxeed_lite_persistent_on_off', 'on'));
$_home_service_sec_img	=  esc_url(get_theme_mod('foxeed_lite_home_service_sec_img', $imagepath.'services-bg.png' ));

function foxeed_lite_skeHex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
} 

$rgb = foxeed_lite_skeHex2RGB($_secondary_color_scheme);
$rgbcolor = "rgba(". $rgb['red'] .",". $rgb['green'] .",". $rgb['blue'] .",.4)";
$brgbcolor = "rgba(". $rgb['red'] .",". $rgb['green'] .",". $rgb['blue'] .",.7)";

$hrgb = foxeed_lite_skeHex2RGB($_primary_color_scheme);
$hrgbcolor = "rgba(". $hrgb['red'] .",". $hrgb['green'] .",". $hrgb['blue'] .",.7)";

?>
<style type="text/css">
	
<?php if(isset($_primary_color_scheme)) { ?>
	
	*::-moz-selection {
		background: <?php echo $_primary_color_scheme; ?>;
		color:#fff;
	}
	::selection {
		background: <?php echo $_primary_color_scheme; ?>;
		color: #fff;
	}
	/* Direct Primary Color */
	.section-heading, .foxeed-counter-h i, .error-txt, #sidebar .skt-blog-thumbnail i, .page-content-title, .nws-subscribe, .navigation #foxeed-paginate a, table thead th {
		color: <?php echo $_primary_color_scheme; ?>;
	}
	
	/* Primary Color on Hover */	
	.foxeed-iconbox a:hover, .foxeed-iconbox a:hover .first-word, .post-title a:hover, .post-title a:hover .first-word, .foxeed-widget-container.mid-box a:hover {
		color: <?php echo $_primary_color_scheme; ?>;
	}

	/* Direct Primary Background Color */
	#header-top, .bread-title-holder, .post-date, #wp-calendar tfoot, #wp-calendar a, #wp-calendar caption, a#backtop, .sktmenu-toggle, .navigation .alignleft a, .navigation .alignright a, #latest-news .owl-prev:before, #latest-news .owl-next:before, ul.horizontal-style li, .submitsearch, .comments-template .reply a, .filter li .selected, .img-404,.postformat-gallerycontrol-nav li a, form input[type="submit"], #searchform input[type="submit"] {
		background-color: <?php echo $_primary_color_scheme; ?>;
	}

	/* Hover Primary Background Color + Misclleneous*/
	.tagcloud a:hover, .foxeed-header-image, #footer {
		border-color: <?php echo $_primary_color_scheme; ?>;
	}

	#sidebar .social li a:hover, .widget_tag_cloud a:hover, .filter a:hover, .continue a:hover, .navigation .nav-previous:hover, .navigation .nav-next:hover {
		background-color: <?php echo $_primary_color_scheme; ?> !important;
	}

	/* hrgb */
	#skenav ul ul a:hover{
		background-color: <?php echo $hrgbcolor; ?>;
	}
	#skenav .foxeed-mob-menu ul a:hover{
		background-color: <?php echo $_primary_color_scheme; ?>;
	}
<?php } ?>

/**************** SECONDARY COLOR *****************/
<?php if(isset($_secondary_color_scheme)) { ?>
	/* Direct Secondary Color */
	#respond .comment-notes,	.foxeed-iconbox h4, #sidebar .news-date-meta, #sidebar .news-month-meta,#sidebar .news-title {
	 	color: <?php echo $_secondary_color_scheme; ?>;
	}
	
	a,.foxeed_widget ul ul li:hover:before,.foxeed_widget ul ul li:hover,.foxeed_widget ul ul li:hover a,.title a ,.skepost-meta a:hover, .post-tags a:hover,.entry-title a:hover,.readmore a:hover, .childpages li a, .foxeed_widget a,.foxeed_widget a:hover, .mid-box:hover .iconbox-icon i,.foxeed-widget-title, .reply a, a.comment-edit-link {
		text-decoration: none;
	}
	.single #content .title, #content .post-heading, .childpages li , .fullwidth-heading, #respond .required {
		color: <?php echo $_secondary_color_scheme; ?>;
	}
	#content .featured-image-shadow-box .fa {
		color: <?php echo $_secondary_color_scheme; ?>;
	}
	section > h1 {
		color: <?php echo $_secondary_color_scheme; ?>;
	}
	
	/* Hover Secondary Color */
	.skepost-meta .comments:hover .fa, .skepost-meta .author-name:hover .fa, .foxeed_widget ul ul li:hover > a, .skepost a:hover, blockquote a:hover, #footer a:hover,#footer li:hover > a, #wrapper .hsearch .hsearch-close:hover, #footer .third_wrapper a:hover, .foxeed-footer-container ul li:hover:before, .foxeed-footer-container ul li:hover a, .cont_nav_inner a:hover, .foxeed-widget-container a:hover, .skepost-meta a:hover{
		color: <?php echo $_secondary_color_scheme; ?>;
	}

	/* Direct Secondary Background Color */
	blockquote, .continue a, .post-icon, .navigation .nav-previous, .navigation .nav-next {
		background-color: <?php echo $_secondary_color_scheme; ?>;
	}

	/* Hover Secondary Background Color + Misclleneous */
	.foxeed-iconbox .iconbox-content h4 hr, form input[type="submit"]:hover, .foxeed-footer-container .tagcloud a:hover {
		border-color: <?php echo $_secondary_color_scheme; ?>;
	} 	
	input[type="submit"]:hover, input[type="button"]:hover, .submitsearch:hover , .navigation .alignleft a:hover, .navigation .alignright a:hover, a#backtop:hover, #latest-news .owl-prev:hover:before, #latest-news .owl-next:hover:before, #wp-calendar a:hover, #footer .tagcloud a:hover {
		background-color: <?php echo $_secondary_color_scheme; ?> !important;
	}
	#respond input[type="submit"]:hover, .comments-template .reply a:hover {
		background-color: <?php echo $_secondary_color_scheme; ?>;
	}
	.foxeed-iconbox.iconbox-top:hover .iconboxhover {
		background-color: <?php echo $_secondary_color_scheme; ?>;
	}
	
<?php } ?>
	body.custom-background {
		background-size: <?php echo $_background_image_size; ?>;
	}
	/*************** TOP HEADER **************/
	<?php if( !display_header_text() ) { ?>
		#site-title { display: none; }
	<?php } ?>
	/*************** CUSTOM HEADER COLOR **************/
	<?php if($_persistent_on_off == 'off') { ?>
		.skehead-headernav-shrink {position: relative;}
	<?php } ?>
	/********************** MAIN NAVIGATION PERSISTENT **********************/
	.bread-title-holder a,.bread-title-holder .title, .bread-title-holder .title span, .cont_nav_inner a {  color: #<?php header_textcolor(); ?>;  }
	<?php if( $_home_service_sec_img != '' ) { ?>
		#front-our-services{
			background-image: <?php echo "url('".$_home_service_sec_img."')"; ?>;
			padding: 55px 0;
			background-repeat: no-repeat;
			background-size: cover;
			background-position: center;
		}
		#front-our-services .top-title, #front-our-services .top-description, .iconbox-icon i, .iconbox-icon h4 a {
			color: #fff;
		}
		.top-style{
			border-top:1px solid #e3e3e3;
		}
		#front-our-services .foxeed-widget-container, .iconbox-content {
			color: #e3e3e3;
		}
	<?php } ?>
</style>