<?php if( get_header_image() ) { ?>
<div class="container-fluid">
	<div class="row">
		<div class="alignc">
			<img class="headerimg" src="<?php header_image(); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'description' ) ); ?>" />
		</div>
	</div>
</div>
<?php } ?>