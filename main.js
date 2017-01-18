/**
 * Flex
 * Template Name - Flex
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2016 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

jQuery(function($) {
	$(window).load(function() {	
		$('#preloader').fadeOut('slow', function(){
			$(this).remove();
		}); 
	});	

     $('#offcanvas-toggler').on('click', function(event){
        event.preventDefault();
        $('body').addClass('offcanvas');
    });

    $( '<div class="offcanvas-overlay"></div>' ).insertBefore( '.body-innerwrapper > .offcanvas-menu' );

    $('.close-offcanvas, .offcanvas-overlay').on('click', function(event){
        event.preventDefault();
        $('body').removeClass('offcanvas');
    });
	
	
	
	$(".social-icons > li > a[href=#],.separator > a,.sppb-person-social > li > a[href=#],.sppb-addon-content > a[href=#],a.sppb-btn[href=#],.slick-img > a[href=#],.sp-layer a[href=#],[data-toggle=\"popover\"],.flex-icons a[href=#]").click(function(e){
    	e.preventDefault();
	});
	
	
		
	//AP Smart LayerSlider hash
	$('a[href^=\"#ap-smart-layerslider-\"]').click(function(e){
		if ( window.history && window.history.pushState ) { 
			window.history.pushState('', '', window.location.pathname);
		} else { 
			window.location.href = window.location.href.replace(/#.*$/, '#'); 
		}
	});

    //Mega Menu
    $('.sp-megamenu-wrapper').parent().parent().css('position','static').parent().css('position', 'relative');
    $('.sp-menu-full').each(function(){
        $(this).parent().addClass('menu-justify');
    });

	$(document).ready(function(){	
        $("body.sticky-header").find('#sp-header').sticky({topSpacing:0});
    });

    //Tooltip
    //$('[data-toggle="tooltip"]').tooltip();
	
	//Popover
  	//$('[data-toggle="popover"]').popover();

    
    $(document).on('click', '.sp-rating .star', function(event) {
        event.preventDefault();

        var data = {
            'action':'voting',
            'user_rating' : $(this).data('number'),
            'id' : $(this).closest('.post_rating').attr('id')
        };

        var request = {
                'option' : 'com_ajax',
                'plugin' : 'helix3',
                'data'   : data,
                'format' : 'json'
            };

        $.ajax({
            type   : 'POST',
            data   : request,
            beforeSend: function(){
                $('.post_rating .ajax-loader').show();
            },
            success: function (response) {
                var data = $.parseJSON(response.data);

                $('.post_rating .ajax-loader').hide();

                if (data.status == 'invalid') {
                    $('.post_rating .voting-result').text('You have already rated this entry!').fadeIn('fast');
                }else if(data.status == 'false'){
                    $('.post_rating .voting-result').text('Somethings wrong here, try again!').fadeIn('fast');
                }else if(data.status == 'true'){
                    var rate = data.action;
                    $('.voting-symbol').find('.star').each(function(i) {
                        if (i < rate) {
                           $( ".star" ).eq( -(i+1) ).addClass('active');
                        }
                    });

                    $('.post_rating .voting-result').text('Thank You!').fadeIn('fast');
                }

            },
            error: function(){
                $('.post_rating .ajax-loader').hide();
                $('.post_rating .voting-result').text('Failed to rate, try again!').fadeIn('fast');
            }
        });
    });
	jQuery('body').append('<a href=\"#top\" id=\"scroll-top\"><i class=\"pe-7s-angle-up\"></i></a>');
	jQuery('ul.sppb-flickr-gallery > li > a').append('<i class="fa fa-joomla"></i>');

	
	/*------------- Scroll to Top ------------------*/
			jQuery(window).scroll(function(){if(!jQuery('body').hasClass('whatever')){if(jQuery(this).scrollTop()>700){jQuery('a#scroll-top').addClass('open')}else{jQuery('a#scroll-top').removeClass('open')}}else{jQuery('a#scroll-top').removeClass('open')}});jQuery('a#scroll-top').on('click',function(){if(!jQuery('body').hasClass('whatever')){jQuery('html, body').animate({scrollTop:0},700);return false}});
			
//======== CSS Browser Selector v0.4.0 (https://github.com/rafaelp/css_browser_selector) ==========//
function css_browser_selector(u){var ua=u.toLowerCase(),is=function(t){return ua.indexOf(t)>-1},g='gecko',w='webkit',s='safari',o='opera',m='mobile',h=document.documentElement,b=[(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+(/trident\/4\.0/.test(ua) ? '8' : RegExp.jQuery1)):is('firefox/2')?g+' ff2':is('firefox/3.5')?g+' ff3 ff3_5':is('firefox/3.6')?g+' ff3 ff3_6':is('firefox/3')?g+' ff3':is('gecko/')?g:is('opera')?o+(/version\/(\d+)/.test(ua)?' '+o+RegExp.jQuery1:(/opera(\s|\/)(\d+)/.test(ua)?' '+o+RegExp.jQuery2:'')):is('konqueror')?'konqueror':is('blackberry')?m+' blackberry':is('android')?m+' android':is('chrome')?w+' chrome':is('iron')?w+' iron':is('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.jQuery1:''):is('mozilla/')?g:'',is('j2me')?m+' j2me':is('iphone')?m+' iphone':is('ipod')?m+' ipod':is('ipad')?m+' ipad':is('mac')?'mac':is('darwin')?'mac':is('webtv')?'webtv':is('win')?'win'+(is('windows nt 6.0')?' vista':''):is('freebsd')?'freebsd':(is('x11')||is('linux'))?'linux':'','js']; c = b.join(' '); h.className += ' '+c; return c;}; css_browser_selector(navigator.userAgent);	


});
jQuery(function($) {
	$('.hikashop_subcategories .pane-sliders .panel').each(function(index){
		var e=$(this).find('div.content ul.hikashop_category_listmenu').length;
		
		if(e){
			//$(this).find('h3.title a').first().remove();
		}else{
			$(this).find('div.content').hide();
			$(this).find('h3.title').addClass('no-sub');
			
		}
	
	});
	$('.hikashop_subcategories h3.title').click(function(){
		//console.log('aa');
		if($(this).next().is( ":visible" )){
			$(this).next().slideDown();
		}else{
			$(this).next().slideUp();
		}
	
	})
})

jQuery(window).load(function() {
	//var h=jQuery('#sp-header').css("height");
	//console.log(h);
	jQuery('#sp-header-sticky-wrapper').css({'height':'auto'});
});
jQuery(document).ready(function(){
	jQuery(".showsubmenu").click(function(){
		var link = jQuery(this);
		var closest_ul = link.closest("ul");
		var parallel_active_links = closest_ul.find(".active")
		var closest_li = link.closest("li");
		var link_status = closest_li.hasClass("active");
		var count = 0;
		closest_ul.find("ul").slideUp(function(){
			if(++count == closest_ul.find("ul").length){
				parallel_active_links.removeClass("active");
				jQuery(link).removeClass("icon-angle-up").addClass("icon-angle-down");
			}
		});

		if(!link_status){
			closest_li.children("ul").slideDown();
			closest_li.addClass("active");
			jQuery(link).addClass("icon-angle-up").removeClass("icon-angle-down");
		}	
	});
	var url_name=window.location.pathname.split('/');
	url_name=url_name[url_name.length-1];
	jQuery(".hikashop_subcategories_listing a").each(function(index){
		var href=jQuery(this).attr('href').split('/');
		href=href[href.length-1];
		if(url_name==href){
			jQuery(this).addClass('link_active');			
			jQuery(this).parents('li.parent').addClass('active');
			jQuery(this).parents('li.parent').children('.showsubmenu').addClass("icon-angle-up").removeClass("icon-angle-down");;
			jQuery(this).parents('li.parent').children("ul").slideDown();
			return false;
		}
	})
})
