(function () {

	document.addEventListener('DOMContentLoaded', function (e) {

		horizontal_carousel = function (item, options) {
			var defaults = { speed: 15 };
			// Overwrite default values
			var params = Object.assign(defaults, options)

			var speed = params.speed;
			var container = item.querySelector('.carousel-frame');
			var arrow_left = item.querySelector('.carousel-arrow.left');
			var arrow_right = item.querySelector('.carousel-arrow.right');

			var idx;

			arrow_left.addEventListener('mouseenter', function () {
				idx = setInterval(function () {
					container.scrollLeft -= speed
				}, 10);
			});

			arrow_left.addEventListener('mouseleave', function () {
				clearInterval(idx);
			});

			arrow_right.addEventListener('mouseenter', function () {
				idx = setInterval(function () {
					container.scrollLeft += speed
				}, 10);
			});

			arrow_right.addEventListener('mouseleave', function () {
				clearInterval(idx);
			});

		}

		document.querySelectorAll('.carousel').forEach(function (item) {
			horizontal_carousel(item, {
				speed: 10
			});
		});

	});

})();