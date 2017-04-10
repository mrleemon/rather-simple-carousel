jQuery( function( $ ) {

	$.fn.horizontal_carousel = function( options ) {
	
		var settings = $.extend({
			speed: 600
        }, options );
		
		var b = false;
		var speed = settings.speed;
		var container = $( this ).find( '.carousel-frame' );
		var arrow_left = $( this ).find( '.carousel-arrow.left' );
		var arrow_right = $( this ).find( '.carousel-arrow.right' );
		
		arrow_left.on( 'mousemove', function( e ) {
			var direction = function() {
				container.animate( {scrollLeft: '-=' + speed}, 1000, 'linear', direction );
			}
			if ( b == false ) {
				b = true;
				container.stop( true ).animate( {scrollLeft: '-=' + speed}, 1000, 'linear', direction );
			}
		}).on( 'mouseleave', function() {
			container.stop( true );
			b = false;
		});

		arrow_right.on( 'mousemove', function( e ) {
			var direction = function() {
				container.animate( {scrollLeft: '+=' + speed}, 1000, 'linear', direction );
			}
			if ( b == false ) {
				b = true;
				container.stop( true ).animate( {scrollLeft: '+=' + speed}, 1000, 'linear', direction );
			}
		}).on( 'mouseleave', function() {
			container.stop( true );
			b = false;
		});
	
	}
	
	$( '.carousel' ).each( function() {
		$( this ).horizontal_carousel({
			speed: 500
		});
	});
	
});

