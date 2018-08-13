<?php
/**
* The template for displaying the footer.
*
* Contains footer content and the closing of the
* #main and #page div elements.
*
*/
?>
	<div class="clearfix"></div>
</div>
<!-- #main --> 

<!-- #footer -->
<div id="footer" class="foxeed-section">
	<div class="container">
		<div class="row-fluid">
			<div class="second_wrapper">
				<ul class="skeside footer-skeside">
					<?php dynamic_sidebar( 'Footer Sidebar' ); ?>
				</ul>
				<div class="clearfix"></div>
			</div><!-- second_wrapper -->
		</div>
	</div>

	<div class="third_wrapper">
		<div class="container">
			<div class="row-fluid">
				<?php $sketchthemes_url = 'http://www.sketchthemes.com/'; ?>
				<div class="copyright span6"> <?php echo get_theme_mod( 'foxeed_lite_copyright', __('Copyright Text', 'foxeed-lite') ); ?></div>
			
				<div class="clearfix"></div>
			</div>
		</div>
	</div><!-- third_wrapper --> 
</div>
<!-- #footer -->

</div>
<!-- #wrapper -->
<a href="JavaScript:void(0);" title="<?php _e('Back To Top','foxeed-lite'); ?>" id="backtop"></a>
<?php wp_footer(); ?>
</body>
</html>