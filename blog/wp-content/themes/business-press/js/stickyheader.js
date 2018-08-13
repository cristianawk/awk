( function( $ ) {
	$(document).ready(function() {
		
		if( $(window).width() > 943 )
		{
			var placeholder = document.createElement('div');
			placeholder.setAttribute("id", "headerplaceholder");
			placeholder.style.width = $('#navbar').width() + 'px';
			placeholder.style.height = $('#navbar').height() + 'px';
			
			var h = $('#navbar').offset().top;
			//for default menu sticky
			$(window).scroll(function () {
				
				if( $(this).scrollTop() > h )
				{
					$('#navbar').addClass('navbarr_sticky');
					$('.nav-sticky-selector').addClass('navbar_sticky');
					$('#logoastext').addClass('logoastext_sticky');
					$('#logoasimg .custom-logo').addClass('logoasimg_sticky');
					$('#navbar').after(placeholder);
					$('#headerplaceholder').css('display','block');
				}
				else
				{
					$('#navbar').removeClass('navbarr_sticky');
					$('.nav-sticky-selector').removeClass('navbar_sticky');
					$('#logoastext').removeClass('logoastext_sticky');
					$('#logoasimg .custom-logo').removeClass('logoasimg_sticky');
					$('#headerplaceholder').css('display','none');
				}
				
			});
			//for default menu sticky END

		}
	});
})( jQuery );
