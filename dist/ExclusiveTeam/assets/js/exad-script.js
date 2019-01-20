(function ($) {
    "use strict";

    var editMode = false;

	var TeamCarousel = function ($scope, $) {
		var $teamCarouselWrapper = $scope.find('.exad-team-carousel-wrapper').eq(0),
		//$team_carousel_id = $teamCarouselWrapper.data("team-carousel-id"),
		$team_preset = $teamCarouselWrapper.data("team-preset"),
		$carousel_nav = $teamCarouselWrapper.data("carousel-nav"),
		$loop = ($teamCarouselWrapper.data("loop") !== undefined) ? $teamCarouselWrapper.data("loop") : false,
		$slidesToShow = $teamCarouselWrapper.data("slidestoshow"),
		$slidesToScroll = $teamCarouselWrapper.data("slidestoscroll"),
		$autoPlay = ($teamCarouselWrapper.data("autoplay") !== undefined) ? $teamCarouselWrapper.data("autoplay") : false,
		$autoplaySpeed = ($teamCarouselWrapper.data("autoplayspeed") !== undefined) ? $teamCarouselWrapper.data("autoplayspeed") : false,
		$transitionSpeed = $teamCarouselWrapper.data("speed"),
		$pauseOnHover = ($teamCarouselWrapper.data("pauseOnHover") !== undefined) ? $teamCarouselWrapper.data("pauseOnHover") : false;


		// Team Carousel 

		//var $teamCarousel = $("#exad-team-carousel-" + $team_carousel_id);

		if ($carousel_nav == "arrows" ) {
			var arrows = true;
			var dots = false;
		} else {
			var arrows = false;
			var dots = true;
		}

		$teamCarouselWrapper.slick({
			infinite: $loop,
			slidesToShow : $slidesToShow,
			slidesToScroll: $slidesToScroll,
			autoplay: $autoPlay,
			autoplaySpeed: $autoplaySpeed,
			speed: $transitionSpeed,
			pauseOnHover: $pauseOnHover,
	      	dots: dots,
	      	arrows: arrows,
	      	prevArrow: "<div class='exad-team-carousel-prev'><i class='fa fa-angle-left'></i></div>",
			nextArrow: "<div class='exad-team-carousel-next'><i class='fa fa-angle-right'></i></div>",
			rows: 0,
	    });

		
	};


	$(window).on('elementor/frontend/init', function () {
        if(elementorFrontend.isEditMode()) {
            editMode = true;
        }
        
        elementorFrontend.hooks.addAction('frontend/element_ready/exad-team-carousel.default', TeamCarousel);
        
    });

}(jQuery));
