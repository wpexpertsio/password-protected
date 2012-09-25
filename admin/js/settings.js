
jQuery(document).ready( function($) {
	
	// Disable 'Allow Feeds' Checkbox
	$('input#password_protected_status').change(function(e){
		if ($(this).attr('checked') == 'checked') {
			$('input#password_protected_feeds').removeAttr('disabled');
		} else {
			$('input#password_protected_feeds').attr('disabled', 'disabled');
		}
	}).trigger('change');

});
