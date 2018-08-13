<?php

$business_press_number_of_footer_widgets = absint( get_theme_mod( 'business_press_number_of_footer_widgets', '0' ) );

if( $business_press_number_of_footer_widgets == 0 )
{
	return;
}

?>

<div id="footer" class="container-fluid footer pdt10 pdb20 clearfix">
	<div class="container">
		<div class="row">

<?php
//if set 1 widgets in customize
if( $business_press_number_of_footer_widgets == 1 )
{
	if ( is_active_sidebar( 'business_press_footer_1' ) )
	{	
		echo '<div class="col-md-12">';
		if ( is_active_sidebar( 'business_press_footer_1' ) )
		{
			dynamic_sidebar( 'business_press_footer_1' );
		}
		echo '</div>';
	}
}
?>


<?php
//if set 2 widgets in customize
if( $business_press_number_of_footer_widgets == 2 )
{
	if ( is_active_sidebar( 'business_press_footer_1' ) || is_active_sidebar( 'business_press_footer_2' ) )
	{	
		echo '<div class="col-md-6">';
		if ( is_active_sidebar( 'business_press_footer_1' ) )
		{
			dynamic_sidebar( 'business_press_footer_1' );
		}
		echo '</div>';
		
		
		echo '<div class="col-md-6">';
		if ( is_active_sidebar( 'business_press_footer_2' ) )
		{
			dynamic_sidebar( 'business_press_footer_2' );
		}
		echo '</div>';
	}
}
?>


<?php
//if set 3 widgets in customize
if( $business_press_number_of_footer_widgets == 3 )
{
	if ( is_active_sidebar( 'business_press_footer_1' ) || is_active_sidebar( 'business_press_footer_2' ) || is_active_sidebar( 'business_press_footer_3' ) )
	{
		echo '<div class="col-md-4">';
		if ( is_active_sidebar( 'business_press_footer_1' ) )
		{
			dynamic_sidebar( 'business_press_footer_1' );
		}
		echo '</div>';
		
		
		echo '<div class="col-md-4">';
		if ( is_active_sidebar( 'business_press_footer_2' ) )
		{
			dynamic_sidebar( 'business_press_footer_2' );
		}
		echo '</div>';
		
		echo '<div class="col-md-4">';
		if ( is_active_sidebar( 'business_press_footer_3' ) )
		{
			dynamic_sidebar( 'business_press_footer_3' );
		}
		echo '</div>';
	}
}
?>


<?php
//if set 4 widgets in customize
if( $business_press_number_of_footer_widgets == 4 )
{
	if ( is_active_sidebar( 'business_press_footer_1' ) || is_active_sidebar( 'business_press_footer_2' ) || is_active_sidebar( 'business_press_footer_3' ) || is_active_sidebar( 'business_press_footer_4' ) )
	{
		echo '<div class="col-md-3">';
		if ( is_active_sidebar( 'business_press_footer_1' ) )
		{
			dynamic_sidebar( 'business_press_footer_1' );
		}
		echo '</div>';
		
		
		echo '<div class="col-md-3">';
		if ( is_active_sidebar( 'business_press_footer_2' ) )
		{
			dynamic_sidebar( 'business_press_footer_2' );
		}
		echo '</div>';
		
		echo '<div class="col-md-3">';
		if ( is_active_sidebar( 'business_press_footer_3' ) )
		{
			dynamic_sidebar( 'business_press_footer_3' );
		}
		echo '</div>';
		
		echo '<div class="col-md-3">';
		if ( is_active_sidebar( 'business_press_footer_4' ) )
		{
			dynamic_sidebar( 'business_press_footer_4' );
		}
		echo '</div>';
	}
}
?>
		
		</div>
	</div>
</div>