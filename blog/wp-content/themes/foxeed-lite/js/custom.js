var $j = jQuery.noConflict();

//MOBILE MENU -----------------------------------------
//-----------------------------------------------------
jQuery(document).ready(function(){
	'use strict';
	jQuery('#menu-main').superfish();
	jQuery('#menu-main li.current-menu-item,#menu-main li.current_page_item,#menu-main li.current-menu-parent,#menu-main li.current-menu-ancestor,#menu-main li.current_page_ancestor').each(function(){
		jQuery(this).prepend('<span class="item_selected"></span>');
	});
});

(function( $ ) {
'use strict';
	$.fn.sktmobilemenu = function(options) { 
	var defaults = {
		'fwidth': 1025
	};

	//call in the default otions
	var options = $.extend(defaults, options);
	var obj = $(this);
	return this.each(function() {
		if($(window).width() < options.fwidth) {
			sktMobileRes();
		}

		$(window).resize(function() {
			if($(window).width() < options.fwidth) {
				sktMobileRes();
			}else{
				sktDeskRes();
			}
		});
		function sktMobileRes() {
			jQuery('#menu-main').superfish('destroy');
			obj.addClass('foxeed-mob-menu').hide();
			obj.parent().css('position','relative');
				if(obj.prev('.sktmenu-toggle').length === 0) {
					obj.before('<div class="sktmenu-toggle" id="responsive-nav-button"></div>');
				}
			obj.parent().find('.sktmenu-toggle').removeClass('active');
		}

		function sktDeskRes() {
			jQuery('#menu-main').superfish('init');
			obj.removeClass('foxeed-mob-menu').show();
			if(obj.prev('.sktmenu-toggle').length) {
				obj.prev('.sktmenu-toggle').remove();
			}
		}

		obj.parent().on('click','.sktmenu-toggle',function() {
			if(!$(this).hasClass('active')){
				$(this).addClass('active');
				$(this).next('ul').stop(true,true).slideDown();
			}
			else{
				$(this).removeClass('active');
				$(this).next('ul').stop(true,true).slideUp();
			}
		});
	});
};
})( jQuery );

jQuery(document).ready(function(){
'use strict';
	jQuery('#menu-main').sktmobilemenu();
});

// ADD CARET TO PARENT MENU
jQuery(document).ready(function(){
	'use strict';
	jQuery( ".sf-with-ul" ).append( '<span class="caret"></span>' );
});

// MAKE MOB-MENU FULL WIDTH
jQuery(window).load(function(){ 
	if(jQuery('#skenav .foxeed-mob-menu').length > 0){		
		jQuery('#skenav .foxeed-mob-menu').css('width',jQuery('.row-fluid').width());
	}
});

//BACK TO TOP -----------------------------------------
//-----------------------------------------------------
jQuery(document).ready( function() {
	'use strict';
	jQuery('#back-to-top,#backtop').hide();
	jQuery(window).scroll(function() {
		if (jQuery(this).scrollTop() > 100) {
			jQuery('#back-to-top,#backtop').fadeIn();
		} else {
			jQuery('#back-to-top,#backtop').fadeOut();
		}
	});
	jQuery('#back-to-top,#backtop').click(function(){
		jQuery('html, body').animate({scrollTop:0}, 'slow');
	});
});

// Search Box
jQuery(document).ready(function($) {
	'use strict';
	jQuery('.hsearch .submitsearch').on('hover', function(){
		jQuery('.hsearch .searchinput').css( { 'left' : 0 , 'opacity': 1 } );
	});
	jQuery('#header-nav, .foxeed-header-image, #main').on('click', function(){
		jQuery('.hsearch .searchinput').css( { 'left' : 300 , 'opacity': 0 } );
	});
	jQuery('.foxeed-widget-container .submitsearch').on('click', function(){
		jQuery('.foxeed-widget-container .searchform').submit();
	});
	jQuery('.hsearch .submitsearch').on('click', function(){
		jQuery('.hsearch .searchform').submit();
	});
	jQuery('#content .submitsearch').on('click', function(){
		jQuery('#content .searchform').submit();
	});
});

//FEATURED BOXES LAYOUTS
jQuery(document).ready(function($) {
	var a = jQuery('#front-our-services .mid-box-wrap > li.mid-box').length;
	var currentli = '#front-our-services .mid-box-wrap > li.mid-box';
	if(a == 1) {
   		  $(currentli).removeClass('span3');
 		  $(currentli).addClass('span12');
	} else if( a==2 ) {
	     $(currentli).removeClass('span3');
 		 $(currentli).addClass('span6');
	}
	else if(a == 3) {
	     $(currentli).removeClass('span3');
 		 $(currentli).addClass('span4');
	}
	else if(a == 5) {
	     $('.mid-box:nth-child(5)').removeClass('span3');
 		 $('.mid-box:nth-child(5)').addClass('span12');
	}
	else if(a == 6) {
	 	$('.mid-box:nth-child(5)').removeClass('span3');
	 	$('.mid-box:nth-child(6)').removeClass('span3');
 		$('.mid-box:nth-child(5)').addClass('span6');  
 		$('.mid-box:nth-child(6)').addClass('span6');    
	}
	else if(a == 7) {
	    $('.mid-box:nth-child(5)').removeClass('span3');
	 	$('.mid-box:nth-child(6)').removeClass('span3');
	 	$('.mid-box:nth-child(7)').removeClass('span3');
 		$('.mid-box:nth-child(5)').addClass('span4');  
 		$('.mid-box:nth-child(6)').addClass('span4');  
 		$('.mid-box:nth-child(7)').addClass('span4');    
	}
	switch(a) {

		case 1:
		case 5:
		case 9: $('.mid-box:nth-child('+a+'n)').addClass('nobox-line');
		  		break;

		case 2: 
		case 6:
		case 10: $('.mid-box:nth-child('+a+'n)').addClass('nobox-line');
		  		break;

		case 3:
		case 7:
		case 11: $('.mid-box:nth-child('+a+'n)').addClass('nobox-line');
		  		break;
	}
	if(a>3){
		$('.mid-box:nth-child(4n)').addClass('nobox-line');
	}
	
});