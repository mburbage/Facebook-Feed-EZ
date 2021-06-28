window.addEventListener('DOMContentLoaded', (event) => {
    document.querySelector('#social-feed-ez-verify-token').addEventListener('click', function (evt){
		verifyPageAccessToken();
	});
});

function verifyPageAccessToken () {

	const feedNonce = document.querySelector('#social-feed-ez-none').value;		
	const userAccessToken = document.querySelector('#social_feed_ez_access_token').value;	
	
	jQuery.ajax({
		url: plugin_data.ajaxurl,
		type: 'POST',
		dataType: 'json',
		data: {
			'action': 'social_feed_ez_verify_token',
			'fb-feed-nonce': feedNonce,
			'fb-access-token': userAccessToken,
		},
		success: function (response) {

			if( response['expires_in'] > 0 ){
				document.querySelector('#social_feed_ez_ll_access_token').value = response['access_token'];
			}

            let expiresMilli = Date.now() + (response['expires_in'] * 1000);

            let expireDate = new Date(expiresMilli).toLocaleDateString();

            console.log(expiresMilli);

			myAdminNotice( '<p>Long-live token recieved. Token expires in '+ expireDate.toString() +'</p>', 'notice-info' );

			//addEpisodeSet(response, null, null, true);

		},
		error: function (response) {
			
			console.log('error: ' + JSON.stringify(response));

			if(response['responseText']){
				myAdminNotice( response['responseText'], 'notice-error' );
			}else{
				myAdminNotice( response, 'notice-error' );
			}
			
			
		}
	});
}

/**
 * Create and show a dismissible admin notice
 */
function myAdminNotice( msg, type ) {
 
    /* create notice div */
     
    var div = document.createElement( 'div' );
    div.classList.add( 'notice', type, 'is-dismissible' );
     
    /* create paragraph element to hold message */
     
    var p = document.createElement( 'p' );
     
    /* Add message text */
     
    //p.appendChild( document.createTextNode( msg ) );
    p.innerHTML = msg;
	
 
    // Optionally add a link here
 
    /* Add the whole message to notice div */
 
    div.appendChild( p );
 
    /* Create Dismiss icon */
     
    var b = document.createElement( 'button' );
    b.setAttribute( 'type', 'button' );
    b.classList.add( 'notice-dismiss' );
 
    /* Add screen reader text to Dismiss icon */
 
    var bSpan = document.createElement( 'span' );
    bSpan.classList.add( 'screen-reader-text' );
    bSpan.appendChild( document.createTextNode( 'Dismiss this notice' ) );
    b.appendChild( bSpan );
 
    /* Add Dismiss icon to notice */
 
    div.appendChild( b );
 
    /* Insert notice after the first h1 */
     
    var h1 = document.getElementsByTagName( 'h1' )[0];
    h1.parentNode.insertBefore( div, h1.nextSibling);
 
 
    /* Make the notice dismissable when the Dismiss icon is clicked */
 
    b.addEventListener( 'click', function () {
        div.parentNode.removeChild( div );
    });
 
     
}
