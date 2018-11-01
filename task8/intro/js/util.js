$(document).ready(function(){
	
	setInterval(function(){
		var timestamp = jQuery.now();
		$('#time').text(timestamp);
	}, 1);
	
	
});