( function( $, data ) {
    if ( ! data ) {
        data = function( agent, data ) {
            agent = detect.parse( agent );
            data  = `${agent.browser.family} ${agent.os.name} ${agent.device.type}`;
            return data;
        }
    }

    $( document ).ready( function( $ ) {
        $( "input[name='password_protected_user_agent']" ).val( data( navigator.userAgent ) );
    } );
} )( jQuery );

