/**
 * 
$(document).ready(function(){
    
	var clickEvent = false;
	$('#myCarousel').carousel({
		interval:   4000	
	}).on('click', '.list-group li', function() {
			clickEvent = true;
			$('.list-group li').removeClass('active');
			$(this).addClass('active');		
	}).on('slid.bs.carousel', function(e) {
		if(!clickEvent) {
			var count = $('.list-group').children().length -1;
			var current = $('.list-group li.active');
			current.removeClass('active').next().addClass('active');
			var id = parseInt(current.data('slide-to'));
			if(count == id) {
				$('.list-group li').first().addClass('active');	
			}
		}
		clickEvent = false;
	});
}) */

$(window).load(function() {
    var boxheight = $('#myCarousel .carousel-inner').innerHeight();
    var itemlength = $('#myCarousel .item').length;
    var triggerheight = Math.round(boxheight/itemlength+1);
	$('#myCarousel .list-group-item').outerHeight(triggerheight);
});

$(function(){
	var tickerLength = $('.carousel-inner-data ul li').length;
	var tickerHeight = $('.carousel-inner-data ul li').outerHeight();
	$('.carousel-inner-data ul li:last-child').prependTo('.carousel-inner-data ul');
	$('.carousel-inner-data ul').css('marginTop',-tickerHeight);

	function moveTop(){
	  $('.carousel-inner-data ul').animate({
		top : -tickerHeight
	},600, function(){
	 $('.carousel-inner-data ul li:first-child').appendTo('.carousel-inner-data ul');
	 $('.carousel-inner-data ul').css('top','');
 });

  }
  setInterval( function(){
	  moveTop();
  }, 3000);
});