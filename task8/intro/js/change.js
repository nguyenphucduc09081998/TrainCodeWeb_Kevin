$('#list').change(function(){
	var list_value = $('#list').val();
	$('#list_feedback').html('you have selected: ' + list_value);
});