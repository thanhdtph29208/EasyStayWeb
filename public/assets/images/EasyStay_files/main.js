jQuery(document).ready(function ($) {
	//final width --> this is the quick view image slider width
	//maxQuickWidth --> this is the max-width of the quick-view panel
	var sliderFinalWidth = 400,
		maxQuickWidth = 900;

	//open the quick view panel
	$('.cd-trigger').on('click', function (event) {
		var selectedImage = $(this).parent('div').parent('.cd-item-parent').children('.cd-item').children('img'),
			slectedImageUrl = selectedImage.attr('src');
		let imageId = selectedImage.data('id')
		if (!$('[data-id=' + imageId + ']').hasClass('is-visible')) {
			closeQuickView(sliderFinalWidth, maxQuickWidth);
			animateQuickView(selectedImage, sliderFinalWidth, maxQuickWidth, 'open');
		}

		//update the visible slider image in the quick view panel
		//you don't need to implement/use the updateQuickView if retrieving the quick view data with ajax
		updateQuickView(slectedImageUrl);
	});
	$(document).keyup(function (event) {
		//check if user has pressed 'Esc'
		if (event.which == '27') {
			closeQuickView(sliderFinalWidth, maxQuickWidth);
		}
	});

	//quick view slider implementation
	$('.cd-quick-view').on('click', '.cd-slider-navigation a', function () {
		updateSlider($(this));
	});

	$('.cd-close').on('click', function (event) {
		closeQuickView(sliderFinalWidth, maxQuickWidth);
	});

	function updateSlider(navigation) {
		var sliderConatiner = navigation.parents('.cd-slider-wrapper').find('.cd-slider'),
			activeSlider = sliderConatiner.children('.selected').removeClass('selected');
		if (navigation.hasClass('cd-next')) {
			(!activeSlider.is(':last-child')) ? activeSlider.next().addClass('selected') : sliderConatiner.children('li').eq(0).addClass('selected');
		} else {
			(!activeSlider.is(':first-child')) ? activeSlider.prev().addClass('selected') : sliderConatiner.children('li').last().addClass('selected');
		}
	}
	function closeQuickView(finalWidth, maxQuickWidth) {
		var close = $('.cd-close'),
			activeSliderUrl = close.siblings('.cd-slider-wrapper').find('.selected img').attr('src'),
			selectedImage = $('.empty-box').children('img');
		let imageId = selectedImage.data('id')
		//update the image in the gallery
		if (!$('[data-id=' + imageId + ']').hasClass('velocity-animating') && $('[data-id=' + imageId + ']').hasClass('add-content')) {
			selectedImage.attr('src', activeSliderUrl);
			animateQuickView(selectedImage, finalWidth, maxQuickWidth, 'close');
		} else {
			if ($('.cd-quick-view').hasClass('is-visible')) {
				closeNoAnimation(selectedImage, finalWidth, maxQuickWidth);
			}
		}
	}

	function animateQuickView(image, finalWidth, maxQuickWidth, animationType) {
		//store some image data (width, top position, ...)
		//store window data to calculate quick view panel position
		var parentListItem = image.parent('.cd-item'),
			topSelected = image.offset().top - $(window).scrollTop(),
			leftSelected = image.offset().left,
			widthSelected = image.width(),
			heightSelected = image.height(),
			windowWidth = $(window).width(),
			windowHeight = $(window).height(),
			finalLeft = (windowWidth - finalWidth) / 2,
			finalHeight = finalWidth * heightSelected / widthSelected,
			finalTop = (windowHeight - finalHeight) / 2,
			quickViewWidth = (windowWidth * .8 < maxQuickWidth) ? windowWidth * .8 : maxQuickWidth,
			quickViewLeft = (windowWidth - quickViewWidth) / 2;

		let imageId = image.data('id')

		if (animationType == 'open') {
			//hide the image in the gallery
			parentListItem.addClass('empty-box');
			//place the quick view over the image gallery and give it the dimension of the gallery image
			$('[data-id=' + imageId + ']').css({
				"top": topSelected,
				"left": leftSelected,
				"width": widthSelected,
			}).velocity({
				//animate the quick view: animate its width and center it in the viewport
				//during this animation, only the slider image is visible
				'top': finalTop + 'px',
				'left': finalLeft + 'px',
				'width': finalWidth + 'px',
			}, 1000, [400, 20], function () {
				//animate the quick view: animate its width to the final value
				$('[data-id=' + imageId + ']').addClass('animate-width').velocity({
					'left': quickViewLeft + 'px',
					'width': quickViewWidth + 'px',
				}, 300, 'ease', function () {
					//show quick view content
					$('[data-id=' + imageId + ']').addClass('add-content');
				});
			}).addClass('is-visible');
		} else {
			//close the quick view reverting the animation
			$('[data-id=' + imageId + ']').removeClass('add-content').velocity({
				'top': finalTop + 'px',
				'left': finalLeft + 'px',
				'width': finalWidth + 'px',
			}, 300, 'ease', function () {
				$('[data-id=' + imageId + ']').removeClass('animate-width').velocity({
					"top": topSelected,
					"left": leftSelected,
					"width": widthSelected,
				}, 500, 'ease', function () {
					$('[data-id=' + imageId + ']').removeClass('is-visible');
					parentListItem.removeClass('empty-box');
				});
			});
		}
	}
	function closeNoAnimation(image, finalWidth, maxQuickWidth) {
		var parentListItem = image.parent('.cd-item'),
			topSelected = image.offset().top - $(window).scrollTop(),
			leftSelected = image.offset().left,
			widthSelected = image.width();
		let imageId = image.data('id')

		parentListItem.removeClass('empty-box');
		$('[data-id=' + imageId + ']').velocity("stop").removeClass('add-content animate-width is-visible').css({
			"top": topSelected,
			"left": leftSelected,
			"width": widthSelected,
		});
	}

	function updateQuickView(url) {
		$('.cd-quick-view .cd-slider li').removeClass('selected').find('img[src="' + url + '"]').parent('li').addClass('selected');
	}
});