$('.link').click(function(){
	var item = $(this).text();
	$('#list').append('<option>' + item + '</option>');
});