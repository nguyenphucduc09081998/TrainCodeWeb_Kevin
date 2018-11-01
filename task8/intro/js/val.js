$('#name').keyup(function(){
	var name = $('#name').val().length;
	$('#area').text(name);
});