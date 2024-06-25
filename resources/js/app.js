require('./bootstrap');

$('.print-window').click(function() {
  window.print();
});

$(document).click(function(e) {
	if (!$(e.target).is('.collapse')) {
    	$('.collapse').collapse('hide');	    
    }
});