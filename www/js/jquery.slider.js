
(function($){    
    $.fn.slider = function(settings){    
        settings = jQuery.extend({
        	speed : "normal",
			slideBy : 2
    	}, settings);
		return this.each(function() {
		$.fn.slider.run( $( this ), settings );
    });
    }; 
	   
    $.fn.slider.run=function($this, settings){
		var ul = $( "ol:eq(0)", $this );
		var li = ul.children();
		if(li.length > settings.slideBy){
			var $next = $( ".next > a", $this );
			var $back = $( ".back > a", $this );
			var liWidth = $( li[0] ).outerWidth(true);
			var animating = false;
			ul.css( "width", liWidth* li.length);
			var Next = $next.click(function() {
				if(!animating){
					animating=true;
					offsetLeft = parseInt(ul.css("left"))-(liWidth* settings.slideBy);
					if ( offsetLeft + ul.width() > 0 ){
						$back.css( "display", "block" );
						ul.animate({left:offsetLeft},settings.speed,function(){
							if ( parseInt( ul.css( "left" ) ) + ul.width() <= liWidth * settings.slideBy ) {
								$next.css( "display", "none" );
							}
							animating = false;
						});
					}
				}
				return false;
			});
			var Back = $back.click(function() {
				if(!animating){
					animating=true;
					offsetRight = parseInt(ul.css("left"))+(liWidth * settings.slideBy);
					if ( offsetLeft + ul.width() <= ul.width() ){
						$next.css( "display", "block" );
						ul.animate({left:offsetRight},settings.speed,function(){
							if ( parseInt( ul.css( "left" ) ) ==0) {
								$back.css( "display", "none" );
							}
							animating = false;
						});
					}
				}
				return false;
			});	
		};
	}; 
})(jQuery);