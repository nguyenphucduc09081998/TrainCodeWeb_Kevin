$('#hideshow').toggle(function(){
	$('#hideshow').text('show');
	$('#message').hide();
}, function(){
	$('#hideshow').text('hide');
	$('#message').show();
});

