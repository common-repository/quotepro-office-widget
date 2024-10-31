(function ($) {
	"use strict";
	$(function () {
	    $('form.quotepro-office-widget .colorbox').colorbox({iframe: true, top: 50, width: "90%",height: $(window).height()-100, close:"X"});
		$('form.quotepro-office-widget').submit(function(e) {
                var action = $(this).prop('action')+"?"+$(this).serialize();
                e.preventDefault();
		    	if ( $(window).width() < 900 ) {
			 		document.location = action;
                } else {
	        		$('.colorbox',this).attr('href',action).click();
                }
            });
	});
}(jQuery));