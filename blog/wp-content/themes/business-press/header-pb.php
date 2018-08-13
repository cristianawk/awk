<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<?php do_action( 'business_press_the_head' ); ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<!-- Loader icon -->
<?php if( get_theme_mod( 'business_press_loading_icon', '0' ) == 1 ) { ?>
	<div class="load-icon"></div>
<?php } ?>
<!-- Loader icon Ends -->

<?php get_template_part( 'template-parts/header', 'topbar' ); ?>

<?php get_template_part( 'template-parts/header', 'main' ); ?>

<?php get_template_part( 'template-parts/header', 'sidebar-menu' ); ?>

<?php get_template_part( 'template-parts/header', 'slider' ); ?>

<?php get_template_part( 'template-parts/header', 'headerimage' ); ?>

<?php
if( get_the_ID() )
{
	if( get_post_meta( get_the_id(), 'business_press_hide_titlebar', true ) != 1 )
	{
		get_template_part( 'template-parts/header', 'titlebar' );
	}
}
else
{
	get_template_part( 'template-parts/header', 'titlebar' );
}
?>

<div id="maincontainer" class="container-fluid clearfix">
	<div class="container">
		 <!-- row and col will added by page builder -->