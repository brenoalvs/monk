(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$( document ).ready( function() {
		$( document ).on( 'change', 'select[name="monk_default_language"]', function() {
			var selected_language = $( this ).val();
			$( 'input[type="checkbox"][name="monk_active_languages[]"]' ).each( function() {
				var current_language = $( this ).val();
				if ( current_language === selected_language ) {
					$( this ).prop({
						'checked': true
					});
					$( this ).parent().addClass( 'option-disabled' );
				} else {
					if ( $( this ).hasClass( 'monk-saved-language' ) ) {
						$( this ).parent().removeClass( 'option-disabled' );
					} else {
						$( this ).parent().removeClass( 'option-disabled' );
					}
				}
			});
		});

		$( document ).on( 'click', 'span.monk-add-translation', function() {
			$( '.monk-post-meta-add-translation' ).slideToggle( 150 );
		});

		$( document ).on( 'click', 'a.monk-cancel-submit-translation', function( e ) {
			$( '.monk-post-meta-add-translation' ).slideUp( 150 );
			e.preventDefault();
		});

		$( document ).on( 'click', 'span.monk-change-language', function() {
			$( '.monk-change-current-language' ).slideToggle( 150 );
		});

		$( document ).on( 'change', '#new-post-language', function() {
			var new_name = $( 'select[name="monk_post_language"] option:selected' ).text();
			$( '#current-language' ).html( new_name );
		});

		$( document ).on( 'click', 'a.monk-cancel-language-change', function( e ) {
			$( '.monk-change-current-language' ).slideUp( 150 );
			$( 'select[name="monk_new_language"] option[value=""]').attr( 'selected', 'selected' );
			e.preventDefault();
		});

		$( document ).on( 'click', 'button#monk-attach', function( event ) {
			var form_data = {
				monk_id : $( '#monk-id' ).val(),
				lang : $( 'select[name="monk_post_translation_id"]' ).val(),
				action : 'monkattach'
			}
			
			$.ajax({
				type: 'POST',
				url: monkattach.monk_ajax,
				data: form_data,
				success: function( response ) {
					console.log(response);
					window.location.replace( response );
				}
			});

			return false;
		});

		$( document ).on( 'click', 'button.monk-submit-translation', function( e ) {
			e.preventDefault();
			var encoded_url = $( 'select[name="monk_post_translation_id"]' ).val();
			window.location.replace( encoded_url );
		});

		$( document ).on( 'change', '#monk-term-translation', function( e ) {
			e.preventDefault();
			var encoded_url = $( 'select[name="monk-term-translation"]' ).val();
			window.location.replace( encoded_url );
		});

		$( document ).on( 'hover', 'td.column-languages', function() {
			$( this ).children( '.monk-column-translations' ).toggleClass( 'monk-show' );
			$( this ).children( '.monk-column-translations-arrow' ).toggleClass( 'monk-hide' );
		});

		var monk_id = $( '#monk-id' ).val();
		var path    = window.location.pathname.split( '/' );
		var url     = window.location.href.split( '&' );

		if ( 'edit-tags.php' === path[ path.length - 1 ] && monk_id ) {
			$( document ).ajaxComplete( function() {
				$( location ).attr( 'href', url[0] );
			});
		}
	});
})( jQuery );
