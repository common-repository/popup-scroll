jQuery( document ).ready( function ( $ ) {

		$( ".cc-pusf-close" ).click( function ( e ) {
				e.preventDefault();

				chchPopUpID = $( this ).attr( 'data-modalID' );
				controlViews = $( this ).attr( 'data-views-control' );
				controlExpires = $( this ).attr( 'data-expires-control' );

				if (controlViews === 'yes' && controlExpires != 'refresh') {
						if (!Cookies.get( 'shown_modal_' + chchPopUpID )) {
								switch ( controlExpires ) {
										case 'session':
												Cookies.set( 'shown_modal_' + chchPopUpID, 'true', {
														path : '/'
												} );
												break;
								}

						}
				}

				$( "#modal-" + chchPopUpID ).hide( "slow" );

		} );
		$( ".cc-pu-bg" ).click( function () {
				$( this ).next( '.pop-up-cc' ).find( '.cc-pu-close' ).trigger( 'click' );
		} );

		$( ".cc-pusf-newsletter-form" ).submit( function ( event ) {
				event.preventDefault();
				email = $( this ).find( '.cc-pu-form-control' ).val();
				nounce = $( this ).find( '#_ajax_nonce' ).val();
				popup = $( this ).find( '#_ajax_nonce' ).attr( 'data-popup' );
				thanks = $( this ).find( '.cc-pu-thank-you' );
				errorMessage = $( this ).find( '.cc-pu-main-error' );
				autoClose = $( this ).find( '.cc-pu-btn' ).attr( 'data-auto-close' );
				closeTimeOut = $( this ).find( '.cc-pu-btn' ).attr( 'data-auto-close-time' ) * 1000;
				closeButton = $( this ).closest( '.modal-inner' ).find( '.cc-pu-close' );
				sendButton = $( this ).find( '.cc-pu-btn' );
				inputField = $( this ).find( '.cc-pu-form-control' );
				sendButton.addClass( 'cc-pu-btn-sending' ).prop( "disabled", true );
				inputField.prop( "disabled", true );

				subscribeParams = {
						action : "chch_pusf_newsletter_subscribe",
						nounce : nounce,
						popup : popup
				};

				fields = [];
				$( this ).find( '.cc-pusf-form-additional' ).each( function ( index, element ) {
						fields.push( {
								fieldName : $( this ).attr( 'name' ),
								fieldVal : $( this ).val(),
								fieldType : $( this ).data( 'type' ),
								fieldReq : $( this ).data( 'req' ),
						} );
				} );

				subscribeParams[ "fields" ] = fields;

				$.ajax( {
						url : chch_pusf_ajax_object.ajaxUrl,
						async : true,
						type : "POST",
						data : subscribeParams,
						success : function ( data ) {
								var response = JSON.parse( data );
								console.log( response.status );
								// $(".cc-pu-newsletter-form").find('.cc-pu-form-control__wrapper').removeClass('show_error');

								if (response.status === 'ok') {

										errorMessage.fadeOut();
										thanks.fadeIn();
										$( '.cc-pu-main-error' ).fadeOut();

										if (autoClose === 'yes') {
												setTimeout( function () {
														closeButton.trigger( 'click' );
												}, closeTimeOut );
										}

										sendButton.removeClass( 'cc-pu-btn-sending' );

								}

								if (response.status === "fields_error") {

										$.each( response.errors, function ( index, el ) {

												errorField = $( ".cc-pusf-newsletter-form" ).find( 'input[name="' + el.field_name + '"]' );
												errorField.closest( '.cc-pu-form-control__wrapper' ).addClass( 'show_error' ).find( '.cc-pu-error-message' ).html( el.error_message );
										} );
								}

								if (response.status === 'error') {
										console.log( response.status );
										thanks.fadeOut();
										errorMessage.fadeIn();
								}

								sendButton.removeClass( 'cc-pu-btn-sending' ).prop( "disabled", false );
								inputField.prop( "disabled", false );
						}
				} );
				$( '.cc-pu-error-message' ).on( 'click', function () {
						$( this ).closest( '.cc-pu-form-control__wrapper' ).removeClass( 'show_error' );
				} )
		} );
} );
