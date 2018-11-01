$(document).ready(function(){
	eventTime = Date.parse('25 september 2020')/1000;
	
	currentTime = Math.floor($.now()/1000);	

	
	seconds = eventTime - currentTime;
	
	days =Math.floor(seconds/(60*60*24));
	
	$('#days').text('Only'+ days + 'days until the event');
	
	
});