jQuery(document).ready(function($) {

	$( '#cp-wp-ajax-button' ).click( function() {
		var id = $( '#cp-ajax-option-id' ).val();
		$.ajax({
			method: "POST",
			url: ajaxurl,
			data: { 'action': 'cp_ajax_tester_approal_action', 'id': id }
		})
		.done(function( data ) {
			console.log('Successful AJAX Call! /// Return Data: ' + data);
			// todo: add if success and not error
			var rawresponse = data;
			data = JSON.parse( data );
			// Response Body
			$("#cp-response").val( rawresponse );
		})
		.fail(function( data ) {
			console.log('Failed AJAX Call :( /// Return Data: ' + data);
		});
	});

});
