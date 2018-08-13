<?php
if( get_theme_mod( 'business_press_titlebar_showhide', '1' ) == 1 )
{
?>
<div class="title-bar">
	<div class="container-fluid">
		<div class="container">
		
			<?php
			if( get_theme_mod( 'business_press_breadcrumbx_setting', '1' ) == '1' )
			{
				echo '<div class="row"><div class="col-md-12">';
				business_press_breadcrumbs();
				echo '</div></div>';
			}
			?>

			<div class="row">
				<div class="col-md-12">
					<h3><?php business_press_page_headline(); ?></h3>
				</div>
			</div>
			
		</div>
	</div>
</div>
<?php
}
?>