
jQuery(document).ready( function($) {
	
	// Disable 'Allow Feeds' Checkbox
	$('input#password_protected_status').change(function(e){
		if ($(this).attr('checked') == 'checked') {
			$('input#password_protected_feeds').removeAttr('disabled');
		} else {
			$('input#password_protected_feeds').attr('disabled', 'disabled');
		}
	}).trigger('change');
 
	// Pointers
	if (typeof(jQuery().pointer) != 'undefined') {
		if (Password_Protected_Settings.pointerTextAllowAdministrators != '') {
			jQuery('#password_protected_administrators').pointer({
				content : Password_Protected_Settings.pointerTextAllowAdministrators,
				position : {
					edge: 'bottom',
					align: 'left'
				},
				close   : function() {
					jQuery.post( ajaxurl, {
						pointer : 'PASSWORD_PROTECTED_ALLOW_ADMINISTRATORS',
						action  : 'dismiss-wp-pointer'
					});
				}
			}).pointer('open');
		}
	}

});
