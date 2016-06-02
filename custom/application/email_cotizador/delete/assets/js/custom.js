'use strict';

var data = null;

$(document).ready(function() {
	enterExitSectons();
	$(window).resize(function(){});
});

function scrollInicio() {
	$(".scroll_menu").click(function() {
		var href = $(this).attr('href');
		$('html, body').animate({
			"scrollTop": $(href).offset().top-15,
		}, 600);
	});
}


function enterExitSectons(){
	$('.scroll_menu').click(function(e) {
		e.preventDefault();

		var toAnimate = $(this).attr('href');

		$('.scroll_menu').parent().removeClass('active');

		$(this).parent().addClass('active')

		$('.section-container, .section-separator h2').fadeOut('50');

		$(toAnimate).parents('.section-separator').find('h2').fadeIn();

		$(toAnimate).fadeIn();
		$(toAnimate).callMeLoad();
	});
}
