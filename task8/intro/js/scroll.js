$('#some_text').scroll(function(){
	var scroll_pos = $('#some_text').scrollTop();
	$('#some_feedback').html('you have scrolled, and are at position'+ scroll_pos);
});