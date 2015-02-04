var $ = jQuery;

var JapkinWP = {
	Init: function() {
		JapkinWP.BindElements();
	},
	
	BindElements: function() {
		$('#japkin_email, #japkin_password').bind( 'keypress', function( e ) {
			if ( e.keyCode == 13 ) {
				$('.button.login').click();
			}
		});
		
		$('.button.login').click( function() {
			var email = $.trim( $('#japkin_email').val() );
			var password = $.trim( $('#japkin_password').val() );
			
			JapkinWP.ShowMessage( '' );
			
			if ( email == '' ) {
				JapkinWP.ShowMessage( 'Email address is required.', 'fail' );
			} else if ( !JapkinWP.ValidEmail( email ) ) {
				JapkinWP.ShowMessage( 'Invalid email address format.', 'fail' );
			} else if ( password == '' ) {
				JapkinWP.ShowMessage( 'Password is required.', 'fail' );
			} else {
				JapkinWP.ShowMessage( 'Logging in...' );
				$.ajax({
					type: 'post',
					url: 'http://www.japkin.com/websites/webservice.php',
					data: {
						method: 'login',
						email: email,
						password: password,
						domain: window.location.hostname
					},
					dataType: 'jsonp',
					success: function( data ) {
						if ( data.Status == 500 ) {
							JapkinWP.ShowMessage( data.Message, 'fail' );
						} else if ( data.Status == 404 ) {
							JapkinWP.ShowMessage( 'Site not found. Please go to your <a href="http://www.japkin.com/websites/my-settings/" target="_blank">Settings Page</a>.', 'fail' );
						} else {
							JapkinWP.ShowMessage( '' );
							
							JapkinWP.SaveOptions({
								'id' : data.Params.id,
								'email' : data.Params.email,
								'key' : data.Params.key,
								'widget' : 'enabled',
								'embed' : 'enabled'
							});
						}
					} 
				});
			}
		});
		
		$('.button.change-user').click( function() {
			JapkinWP.ClearOptions();
		});
		
		$('#japkin_settings .option .check').click( function() {
			if ( $(this).parent().hasClass( 'enabled' ) ) {
				$(this).parent().removeClass('enabled').addClass('disabled').children('span').html('disabled');
			} else {
				$(this).parent().removeClass('disabled').addClass('enabled').children('span').html('enabled');
			}

			JapkinWP.SaveOptions({
				'id' : $('#user_id').val(),
				'email' : $('#user_email').val(),
				'key' : $('#user_key').val(),
				'widget' : $('#japkin_settings .options .option.for_widget span').html(),
				'embed' : $('#japkin_settings .options .option.for_embed span').html(),
				'no_reload' : 'NO_RELOAD'
			});
		})
	},
	
	SaveOptions: function( options ) {
		JapkinWP.ShowMessage( 'Saving your settings...' );
		$.ajax({
			type: 'post',
			url: ajaxurl,
			data: {
				action: 'japkin_options',
				id: options.id,
				email: options.email,
				key: options.key,
				widget: options.widget,
				embed: options.embed,
				no_reload: ( ( typeof( options.no_reload ) != 'undefined' ) ? options.no_reload : 'OK' ) 
			},
			dataType: 'json',
			success: function( data ) {
				if ( data.Message == 'OK' ) {
					window.location.reload();
				} else {
					$('#japkin_settings .message').attr( 'class', 'message' ).html( '' );
				}
			}
		});
	},
	
	ClearOptions: function() {
		JapkinWP.ShowMessage( 'Clearing plugin cache...' );
		$.ajax({
			type: 'post',
			url: ajaxurl,
			data: {
				action: 'japkin_options',
				method: 'clear'
			},
			dataType: 'json',
			success: function( data ) {
				window.location.reload();
			}
		});
	},
	
	ShowMessage: function( message, type ) {
		if ( typeof( type ) == 'undefined' ) { var type = ''; }
		
		if ( message != '' ) {
			$('#japkin_settings .message').addClass( type ).html( message );
		} else {
			$('#japkin_settings .message').attr( 'class', 'message' ).html( message );
		}
	},
	
	ValidEmail: function( email ) {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test( email );
	}
};

$(document).ready( function() {
	JapkinWP.Init();
});