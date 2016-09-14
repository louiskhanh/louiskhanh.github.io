(function($){ 
	$(document).ready(function(){
		var style = $('<style type="text/css" id="theme_color" />').appendTo('head');
		
		var $op = $('#options_panel'),
			$op_btn = $('#options_panel h3');
			
		$op_btn.click(function(){
			if($op.hasClass('opened')) {
				$op.removeClass('opened').animate({'left':'-'+195+'px'}, 500,'easeInOutBack');
				$(this).find('span').removeClass('icon-remove').addClass('icon-wrench');
				
			} else {
				$op.addClass('opened').animate({'left':0}, 500,'easeInOutBack');
				$(this).find('span').removeClass('icon-wrench').addClass('icon-remove');
			}
		});
		
		$('#header_style').change(function(){
			$('#header').attr('class','');
			$('#header').addClass('style'+$(this).val());
		});
		
		
		
		$('#options_panel .color_suggestions li').click(function(){
			var $value = $(this).attr('data-value');
			if ( $value  == 1 ) {
				$('#color_style').remove();
				var style = $('<link rel="stylesheet" href="assets/css/color/gray.css" type="text/css" id="color_style" />').appendTo('head');
			 } else if ( $value == 2 ) {
				$('#color_style').remove();
				var style = $('<link rel="stylesheet" href="assets/css/color/green.css" type="text/css" id="color_style" />').appendTo('head');
			} else if ( $value == 3 ) {
				$('#color_style').remove();
				var style = $('<link rel="stylesheet" href="assets/css/color/brown.css" type="text/css" id="color_style" />').appendTo('head');
			} else if ( $value == 4 ) {
				$('#color_style').remove();
				var style = $('<link rel="stylesheet" href="assets/css/color/yellow.css" type="text/css" id="color_style" />').appendTo('head');
			} else if ( $value == 5 ) {
				$('#color_style').remove();
				var style = $('<link rel="stylesheet" href="assets/css/color/orange.css"	type="text/css" id="color_style" />').appendTo('head');
			} else if ( $value == 6 ) {
				$('#color_style').remove();
				var style = $('<link rel="stylesheet" href="assets/css/color/red.css" type="text/css" id="color_style" />').appendTo('head');
			} else if ( $value == 7 ) {
				$('#color_style').remove();
				var style = $('<link rel="stylesheet" href="assets/css/color/purple.css"  type="text/css" id="color_style" />').appendTo('head');
			} else if ( $value == 8 ) {
				$('#color_style').remove();
				var style = $('<link rel="stylesheet" href="assets/css/color/blue.css" type="text/css" id="color_style" />').appendTo('head');
			} else if ( $value == 9 ) {
				$('#color_style').remove();
				var style = $('<link rel="stylesheet" href="assets/css/color/clarity.css"  type="text/css" id="color_style" />').appendTo('head');
			} else if ( $value == 10 ) {
				$('#color_style').remove();
				var style = $('<link rel="stylesheet" href="assets/css/color/clarity2.css" type="text/css" id="color_style" />').appendTo('head');
			} else if ( $value == 11 ) {
				$('#color_style').remove();
				var style = $('<link rel="stylesheet" href="assets/css/color/vividsea.css" type="text/css" id="color_style" />').appendTo('head');
			} else if ( $value == 12 ) {
				$('#color_style').remove();
				var style = $('<link rel="stylesheet" href="assets/css/color/rain.css" type="text/css" id="color_style" />').appendTo('head');
			} else if ( $value == 13 ) {
				$('#color_style').remove();
				var style = $('<link rel="stylesheet" href="assets/css/color/darkforest.css" type="text/css" id="color_style" />').appendTo('head');
			} else if ( $value == 14 ) {
				$('#color_style').remove();
				var style = $('<link rel="stylesheet" href="assets/css/color/greenmile.css" type="text/css" id="color_style" />').appendTo('head');
			} else if ( $value == 15 ) {
				$('#color_style').remove();
				var style = $('<link rel="stylesheet"  href="assets/css/color/ruby.css" type="text/css" id="color_style" />').appendTo('head');
			} 
		});
		
		
		function changeColor(hex) {
			$('#color_style').remove();
			style.html(
			
				'.rotated_line_location3 , .rotated_line_location1 , .rotated_line1, .accordion .accordion_title , .icon a ,.icon  .about_col_icon  , .gallery .item .item_meta ,.home_back .home_back_icon , .top_icon , .blog_post_img .blog_date , .phone_date , .phone_top_icon , .phone_home_back_icon , .btn:active { background-color:' + hex + '!important;}' +
				'h2 .line ,.cancel_reply a:hover, .reply a:hover , .cat-item a:hover ,.recentcomments a:hover , .sidebar h4 .line , h3 .line , .comment_box .meta .date a , .next_btn:hover, .previous_btn:hover , .button_submit:hover .text_btn , .absolute_logo_text , .testimonial .name ,.widget a:hover ,.official .sticky_navigation li .text { color:' + hex + '!important;}' +
				'.toggle_title a { color:' + hex + ';}' +
				'.accordion .accordion_content , .blog_post_img { border-left-color:' + hex + ';}' +
				'.home_back .home_back_icon , .home_back:hover  .home_back_icon  , .top_icon, .top_icon:hover { border-right-color:' + hex + '!important;}' +
				'.sub_navigation a:hover ,.sticky_navigation  li .bottom_border { border-bottom-color:' + hex + '!important;}' +
				'::-webkit-scrollbar-thumb , ::-webkit-scrollbar-thumb:window-inactive { background:' + hex + '; }');
				
		}
		
		function changeColorIntroContact(hexIntorContact) {
			$('#color_style').remove();
			style.html(

				'.wrap_contact , .wrap_intro ,.rotated_line_location4 , .rotated_line_location5 , .wrap_home_space { background-color: ' + hexIntorContact + ' !important; }');	

		}
		
		function changeColorHeraderNav(hexheadernav) {
			$('#color_style').remove();
			style.html(
				
				'.wrap_header { background-color:' + hexheadernav + ' !important; }');
		}
		
		function changeColorHeaderBtn(hexheaderbtn) {
			$('#color_style').remove();
			style.html(
				
				'#top_button , .toggle_top_bar , .mobile-navigation > a , .mobile-navigation ul { background-color:' + hexheaderbtn + '!important; }');
		}
		
		
		// General Theme Colour
		$('.color-picker').miniColors({
			change: function(hex, rgba) {
				changeColor(hex);
			}
		});
		
		// Circle Menu
		$('.color-picker-headerfooter').miniColors({
			change: function(hexIntorContact, rgba) {
				changeColorIntroContact(hexIntorContact);
			}
		});
		
		// Header Navigation
		$('.color-picker-heradernav').miniColors({
			change: function(hexheadernav, rgba) {
				changeColorHeraderNav(hexheadernav);
			}
		});
		
		// Header Buttons
		$('.color-picker-headerbtn').miniColors({
			change: function(hexheaderbtn, rgba) {
				changeColorHeaderBtn(hexheaderbtn);
			}
		});
		
		$('#options_panel .dark_light_demo li').click(function(){
			var $value = $(this).attr('data-value');
			
			if ( $value  == 1 ) {
				$('#dark_style').remove();
				var style = $('<link rel="stylesheet" href="assets/css/light_skin.css?ver=1.0" type="text/css" id="light_style" />').appendTo('head');
			 } else if ( $value == 2 ) {
				$('#light_style').remove();
				var style = $('<link rel="stylesheet" href="assets/css/dark_skin.css?ver=1.0" type="text/css" id="dark_style" />').appendTo('head');
				}
		});
		
	});
})(jQuery);