jQuery( document ).ready(function( $ ) {

    toggle_recaptcha_version( $ );
    set_recaptcha_version_fields( $ );

} );

function set_recaptcha_version_fields( $ ) {
    var selected_version = $("input[name='password_protected_recaptcha[version]']:checked").val();
    if( selected_version == 'google_recaptcha_v2' ) {
        hide_recaptcha_v3_fields( $ );
    } else {
        hide_recaptcha_v2_fields( $ );
    }
}

function toggle_recaptcha_version( $ ) {
    $("input[name='password_protected_recaptcha[version]']").on('change', function() {
        if( $(this).val() === 'google_recaptcha_v2' ) {
            // hide v3 fields
            hide_recaptcha_v3_fields( $ );
            
            // show v2 fields
            $("#pp_google_recaptcha_v2_site_key").parent('div').fadeIn();
            $("#pp_google_recaptcha_v2_secret_key").parent('div').fadeIn();
            $("input[name='password_protected_recaptcha[v2_theme]']").parent('label').parent('td').parent('tr').fadeIn();
            
        } else {
            // show v3 fields
            $("#pp_google_recaptcha_v3_site_key").parent('div').fadeIn();
            $("#pp_google_recaptcha_v3_secret_key").parent('div').fadeIn();
            $("#pp_google_recpatcha_v3_score").parent('td').parent('tr').fadeIn();
            $("#pp_google_recpatcha_v3_badge").parent('td').parent('tr').fadeIn();
            
            // hide v2 fields
            hide_recaptcha_v2_fields( $ );
        }
    });
}

function hide_recaptcha_v2_fields( $ ) {
    $("#pp_google_recaptcha_v2_site_key").parent('div').hide();
    $("#pp_google_recaptcha_v2_secret_key").parent('div').hide();
    $("input[name='password_protected_recaptcha[v2_theme]']").parent('label').parent('td').parent('tr').hide();
}

function hide_recaptcha_v3_fields( $ ) {
    $("#pp_google_recaptcha_v3_site_key").parent('div').hide();
    $("#pp_google_recaptcha_v3_secret_key").parent('div').hide();
    $("#pp_google_recpatcha_v3_score").parent('td').parent('tr').hide();
    $("#pp_google_recpatcha_v3_badge").parent('td').parent('tr').hide();
}