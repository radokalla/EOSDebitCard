/*  Table of Contents 
01. MENU ACTIVATION
02. prettyPhoto Activation
03. FITVIDES RESPONSIVE VIDEOS 
04. MOBILE SELECT MENU
05. IE7 z-index fix
06. Show More Search
07. IE7 Z-INDEX FIX
08. IE PLACEHOLDER TEXT
09. COLOR PICKER
*/


/*
=============================================== 01. MENU ACTIVATION  ===============================================
*/
jQuery(document).ready(function(){
	jQuery("ul.sf-menu").supersubs({ 
	        minWidth:    4,   // minimum width of sub-menus in em units 
	        maxWidth:    18,   // maximum width of sub-menus in em units 
	        extraWidth:  1     // extra width can ensure lines don't sometimes turn over 
	                           // due to slight rounding differences and font-family 
	    }).superfish({ 
			animation: {opacity:'show'},   // slide-down effect without fade-in 
			autoArrows:    false,               // if true, arrow mark-up generated automatically = cleaner source code at expense of initialisation performance 
			dropShadows:   false,               // completely disable drop shadows by setting this to false 
			delay:     450               // 1.2 second delay on mouseout 
		});
});


/*
=============================================== 02. prettyPhoto Activation  ===============================================
*/
jQuery(document).ready(function(){
		jQuery("a[rel^='prettyPhoto']").prettyPhoto({
			animation_speed: 'fast', /* fast/slow/normal */
			slideshow: 5000, /* false OR interval time in ms */
			autoplay_slideshow: false, /* true/false */
			opacity: 0.80, /* Value between 0 and 1 */
			show_title: false, /* true/false */
			allow_resize: true, /* Resize the photos bigger than viewport. true/false */
			default_width: 500,
			default_height: 344,
			counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
			theme: 'pp_default', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
			horizontal_padding: 20, /* The padding on each side of the picture */
			hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
			wmode: 'opaque', /* Set the flash wmode attribute */
			autoplay: false, /* Automatically start videos: True/False */
			modal: false, /* If set to true, only the close button will close the window */
			deeplinking: false, /* Allow prettyPhoto to update the url to enable deeplinking. */
			overlay_gallery: false, /* If set to true, a gallery will overlay the fullscreen image on mouse over */
			keyboard_shortcuts: true, /* Set to false if you open forms inside prettyPhoto */
			ie6_fallback: true,
			social_tools: '' /* html or false to disable  <div class="pp_social"><div class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div><div class="facebook"><iframe src="http://www.facebook.com/plugins/like.php?locale=en_US&href='+location.href+'&amp;layout=button_count&amp;show_faces=true&amp;width=500&amp;action=like&amp;font&amp;colorscheme=light&amp;height=23" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:500px; height:23px;" allowTransparency="true"></iframe></div></div> */
		});
});


/*
=============================================== 03. FITVIDES RESPONSIVE VIDEOS  ===============================================
*/
jQuery(document).ready(function($) {  
$("#main").fitVids();
$(".flexslider").fitVids();
});


/*
=============================================== 04. MOBILE SELECT MENU  ===============================================
*/
jQuery(document).ready(function($) {
$('.sf-menu').mobileMenu({
    defaultText: 'Navigate to...',
    className: 'select-menu',
    subMenuDash: '&ndash;&ndash;'
});
});


/*
=============================================== 05. SCROLL TO TOP  ===============================================
*/
jQuery(document).ready(function($){
	// Scroll page to the top
	$('a#scrollToTop').click(function(){
		$('html, body').animate({scrollTop:0}, 'normal');
		return false;
	});
});


/*
=============================================== 06. Show More Search  ===============================================
*/
jQuery(document).ready(function(){

	$(".search-drop-down").click(function(){
		$("#panel-search").slideToggle("normal");
		$(this).toggleClass("active"); return false;
	});


});



/*
=============================================== 07. IE7 Z-INDEX FIX  ===============================================
*/
jQuery(document).ready(function() {
            //fix ie 7 and less quirks issue
            if (($.browser.msie) && (parseInt($.browser.version, 10) <= 7)) {
                $(function() {
                    var zIndexNumber = 1000;
                    $('div').each(function() {
                        $(this).css('zIndex', zIndexNumber);
                        zIndexNumber -= 10;
                    });
                });
            }
        });


/*
=============================================== 08. IE PLACEHOLDER TEXT  ===============================================
*/
jQuery(document).ready(function() {
$('input, textarea').placeholder();
 });



/*
=============================================== 09. COLOR PICKER  ===============================================
*/
jQuery(document).ready(function($) { 
	$("div.panel_button").click(function(){
		$("div#panel").animate({
			left: "0px"
		}, "fast");
		$(".panel_button").animate({
			left: "158px"
		}, "fast");
		$("div.panel_button").toggle();
	});	
   $("div.hide_button").click(function(){
		$("div#panel").animate({
			left: "-159px"
		}, "fast");
		$(".panel_button").animate({
			left: "0px"
		}, "fast");
   });		
});

