jQuery( function( $ ) {

	// click to scroll to specific images
	$('.carousel-frame .carousel-item').on('click', function(e) {
		var container = $(this).parent().parent();
		var slideWidth = $(this).width();
		var frameWidth = container.width() / 2;
		
		var slidePosition = $(this).position().left;
		var offset = container.scrollLeft() + slidePosition - frameWidth + slideWidth / 2;
		container.animate({
			scrollLeft: offset
		}, 500);
		e.preventDefault();
	});

	
});

