( function( $ ) {
	$(document).ready(function() {

		//Add img-responsive class to all images
		 $('body img').addClass("img-responsive");
		//Add img-responsive class to all images END

		// Handle drop down toggle
		$( "p.dropdowntoggle" ).click(function() {
			if( $(this).parent('li').hasClass('dd-this-open') )
			{
				$(this).parent('li').removeClass('dd-this-open');
			}
			else
			{
				$(this).parent('li').addClass('dd-this-open');
			}

			return false;
		})
		
		//anything else will go below
		
		});
} )( jQuery );
