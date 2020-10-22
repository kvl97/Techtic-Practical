var $ = jQuery.noConflict();

/* Script on ready
------------------------------------------------------------------------------*/
$(document).ready(function () {
	//do jQuery stuff when DOM is ready

	/* Responsive Jquery Navigation */
	$('.hamburger').click(function (event) {
		$('.mobilenav').toggleClass('is-open');
	});

	$('.mobilenav .nav-backdrop').click(function () {
		$('.mobilenav').removeClass('is-open');
	});

	var clickable = $('.menu-state').attr('data-clickable');
	$('.mobilenav li:has(ul)').addClass('has-sub');
	$('.mobilenav .has-sub>a').after('<em class="caret">');
	if (clickable == 'true') {
		$('.mobilenav .has-sub>.caret').addClass('trigger-caret');
	} else {
		$('.mobilenav .has-sub>a').addClass('trigger-caret').attr('href', 'javascript:;');
	}

	/* menu open and close on single click */
	$('.mobilenav .has-sub>.trigger-caret').click(function () {
		var element = $(this).parent('li');
		if (element.hasClass('is-open')) {
			element.removeClass('is-open');
			element.find('li').removeClass('is-open');
			element.find('ul').slideUp(200);
		}
		else {
			element.addClass('is-open');
			element.children('ul').slideDown(200);
			element.siblings('li').children('ul').slideUp(200);
			element.siblings('li').removeClass('is-open');
			element.siblings('li').find('li').removeClass('is-open');
			element.siblings('li').find('ul').slideUp(200);
		}
	});

	/* datepicker */
	if ($('.datepicker').length > 0) {
		$('.datepicker').datepicker({  
			minDate:new Date()
		 });
		//$(".datepicker").datepicker();
	}

	/* passenger-info */
	/* $('body').on('click', '.ps-info em', function () {
		var ele = $(this).parent().find('input');
		var nmVal = $(this).attr('data-value'),
			curVal = parseInt($(ele).val());
		nmLimit = parseInt($(ele).attr('data-limit'));
		if (nmVal == 'up') {
			if (curVal >= nmLimit) {
				return false
			} else {
				var ChangedVal = curVal + 01;
				ele.attr('value', ChangedVal);
				ele.val(ChangedVal)
			}
		} else {
			if (curVal < 2) {
				return false
			} else {
				var ChangedVal = curVal - 01;
				ele.attr('value', ChangedVal);
				ele.val(ChangedVal)
			}
		}
		  console.log(ele.val());
	}); */
	/* Don't allow typing alphabetic characters */
	$(".ps-info .ps-digit").keydown(function (e) {
		// Allow: backspace, delete, tab, escape, enter and .
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
			// Allow: Ctrl/cmd+A
			(e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
			// Allow: Ctrl/cmd+C
			(e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
			// Allow: Ctrl/cmd+X
			(e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
			// Allow: home, end, left, right
			(e.keyCode >= 35 && e.keyCode <= 39)) {
			// let it happen, don't do anything
			return;
		}
		// Ensure that it is a number and stop the keypress
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});

	$('body').on('keyup', '.ps-info .ps-digit', function () {
		$(this).attr('value', $(this).val());
	});

	/* testimonial-slider */
	var TestimonialSlider = $('.testimonial-slider');
	if (TestimonialSlider.length) {
		TestimonialSlider.each(function () {
			$(this).slick({
				slidesToShow: 1,
				slidesToScrool: 1,
				cssEase: 'linear',
				dots: true,
				arrows: false,
				infinite: true
			});
		});
	}

	/* Background image in ie */
	var userAgent, ieReg, ie;
	userAgent = window.navigator.userAgent;
	ieReg = /msie|Trident.*rv[ :]*11\./gi;
	ie = ieReg.test(userAgent);

	if (ie) {
		$(".img-container").each(function () {
			var $container = $(this),
				imgUrl = $container.find("img").prop("src");
			if (imgUrl) {
				$container.css("backgroundImage", 'url(' + imgUrl + ')').addClass("custom-object-fit");
			}
		});
	}

	/* login form */
	$('.custom-form input , .custom-form select, .custom-form textarea').focus(function () {
		$(this).parents('.form-group').addClass('focused');
	});

	$('.custom-form input , .custom-form select, .custom-form textarea').blur(function () {
		var inputValue = $(this).val();
		if (inputValue == "") {
			$(this).removeClass('filled');
			$(this).parents('.custom-form .form-group').removeClass('focused');
		} else {
			$(this).addClass('filled');
		}
	})

	// Tooltip
	$('[data-toggle="tooltip"]').tooltip();

	// EqualHeight
	if ($(window).width() > 991) {
		EqualHeight();
	}

	// DivEqualHeight
	DivEqualHeight();

	// Custome select box
	if ($('select').length > 0) {
		$('select:not(.multiselect)').select2();
	}

	/* Kiosk-slider */
	var KioskSlider = $('.kiosk-slider');
	if (KioskSlider.length) {
		KioskSlider.each(function () {
			$(this).slick({
				slidesToShow: 1,
				slidesToScrool: 1,
				cssEase: 'linear',
				dots: false,
				arrows: true,
				infinite: true,
				adaptiveHeight: true
			});
		});
	}

	/* mCustomScrollbar */
	$(".kiosk-information .container").mCustomScrollbar();

	/* Faq accordian */
	$('.faq-block.active .faq-content').slideDown();
	$('.faq-block .accordian-title').on("click", function (e) {
		if ($(this).parents('.faq-block').hasClass('active')) {
			$(this).next('.faq-content').stop(true, false).slideUp(400);
			$(this).parents('.faq-block').removeClass('active')
		}
		else {
			$('.faq-block').removeClass('active');
			$('.faq-content').stop(true, false).slideUp();
			$(this).parents('.faq-block').addClass('active');
			$(this).next('.faq-content').stop(true, false).slideDown(400);
		}
		return false;
	});

	/* Target accordian block */
	if ($(window).width() < 768) {
		$('.faq-block .accordian-title').bind('click', function () {
			var self = this;
			setTimeout(function () {
				theOffset = $(self).offset();
				$('body,html').animate({
					scrollTop: theOffset.top - 150
				});
			}, 800);
		});
	}
});

/* Script on load
------------------------------------------------------------------------------*/
$(window).on('load', function () {
	// page is fully loaded, including all frames, objects and images
});

/* Script on scroll
------------------------------------------------------------------------------*/
$(window).on('scroll', function () {

});

/* Script on resize
------------------------------------------------------------------------------*/
$(window).on('resize', function () {

});

/* Script all functions
------------------------------------------------------------------------------*/

function EqualHeight() {
	$('.services-wrapper').each(function () {
		var highestBox = 0;
		$('.services-block .block-inner', this).each(function () {
			if ($(this).height() > highestBox) {
				highestBox = $(this).height();
			}
		});
		$('.services-block .block-inner', this).height(highestBox);
	});
}

function DivEqualHeight() {
	$('.form-wrapper .form-bottom').each(function () {
		var $that = $(this);
		var firstLi, firstLiIndex, firstUL;
		var secondLi, secondLiIndex, secondUL;
		$that.find('.counter-from .counter-block').each(function () {
			firstLi = $(this);
			firstLiIndex = firstLi.index();
			firstUL = firstLi.outerHeight();
			$that.find('.charge-btn .btn-block').each(function () {
				secondLi = $(this);
				secondLiIndex = secondLi.index();
				secondUL = secondLi.outerHeight();
				if (firstLiIndex == secondLiIndex) {
					if (firstUL > secondUL) {
						secondLi.css('min-height', firstUL);
					} else {
						firstLi.css('min-height', secondUL);
					}
				}
			});
		});
	});
}