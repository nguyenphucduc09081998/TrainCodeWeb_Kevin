$('#signup_form').submit(function(){
	var user_email = $('#user_email').val();
	
	$('#signup_feedback').html('Thanks, ' + user_email + ' has been signed up.');
});