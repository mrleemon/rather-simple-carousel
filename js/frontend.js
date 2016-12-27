jQuery( function( $ ) {

	// click to scroll to specific images
	/*$( '.carousel-frame .carousel-item' ).on( 'click', function(e) {
		var container = $(this).parent().parent();
		var slideWidth = $(this).width();
		var frameWidth = container.width() / 2;
		
		var slidePosition = $(this).position().left;
		var offset = container.scrollLeft() + slidePosition - frameWidth + slideWidth / 2;
		container.animate({
			scrollLeft: offset
		}, 500);
		e.preventDefault();
	});*/

	// scroll on hover
	$( '.carousel-frame ul' ).mousemove( function(e) {
		var container = $(this).parent();
		if ((e.pageX - container.offset().left) < container.width() / 2) {
			var direction = function() {
				container.animate({scrollLeft: '-=600'}, 1000, 'linear', direction);
			}
			container.stop().animate({scrollLeft: '-=600'}, 1000, 'linear', direction);
		} else {
			var direction = function() {
				container.animate({scrollLeft: '+=600'}, 1000, 'linear', direction);
			}
			container.stop().animate({scrollLeft: '+=600'}, 1000, 'linear', direction);
		}
	}).mouseleave( function() {
		var container = $(this).parent();
		container.stop();
	});
	
});

