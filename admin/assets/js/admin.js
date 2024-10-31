jQuery( document ).ready( function ( $ ) {

		$( '#wpbody-content > .wrap' ).prepend( '<a class="button button-secondary right button-hero" style="margin: 25px 0px 0px 2px; padding: 0px 20px; height: 47px;" href="https://shop.chop-chop.org/contact" target="_blank">Contact Support</a><a class="button button-primary right button-hero" href="http://ch-ch.org/pupro" style="margin: 25px 20px 0 2px;">Get Pro</a>' );

		$( '#_chch_pusf_auto_closed' ).on( 'change', function () {
				var target = $( '.cmb2-id--chch-pusf-close-timer' );

				if ($( this ).is( ':checked' )) {
						$( target ).removeClass( 'hide-section' );
				} else {
						$( target ).addClass( 'hide-section' );
				}
		} );

		$( '#_chch_pusf_auto_closed' ).trigger( 'change' );

		$( "#chch-pu-add-field" ).on( 'click', function ( e ) {
				e.preventDefault();

				fieldIndex = $( this ).attr( 'data-field-count' );
				currentIndex = (parseInt( fieldIndex ) + 1);
				$( this ).attr( 'data-field-count', currentIndex );

				wrapper = $( '.chch-pu-repeater' );

				fields = wrapper.find( '.chch-pu-reapeter-fields:first-child' ).clone( true );
				console.log( fields );
				fields_inputs = fields.find( '.chch-pu-repeater-field' );
				fields.find( '.delete-email-field' ).removeClass( 'hide-section' );
				fields_inputs.each( function () {

						if ($( this ).attr( 'type' ) == 'text') {
								$( this ).val( '' );
						}

						if ($( this ).attr( 'type' ) == 'checkbox') {
								$( this ).attr( 'checked', false );
						}

						if ($( this ).is( "select" )) {
								$( this ).find( "option" ).prop( "selected", false );
						}

						field_name = $( this ).attr( 'name' );
						$( this ).attr( 'name', field_name.replace( /email-fields[[0-9]*]/i, 'email-fields[' + currentIndex + ']' ) );
				} );

				fields.appendTo( wrapper );

				count_fields();
		} );

		$( ".delete-email-field" ).on( 'click', function ( e ) {
				e.preventDefault();
				$( this ).closest( '.chch-pu-reapeter-fields' ).not( '.chch-pu-reapeter-fields:first-child' ).remove();
				count_fields();
		} );

		function count_fields() {
				$( '.chch-pu-repeater .field-count' ).each( function ( index ) {
						$( this ).html( index + 1 );
				} );
		}

		$( '.chch-pu-repeater  tbody' ).sortable( {
				opacity : 0.6,
				revert : true,
				cursor : 'move',
				handle : '.field-count',
				axis : "y",
				update : function ( event, ui ) {
						count_fields();
				}
		} );
} );