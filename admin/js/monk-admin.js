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
			var default_language  = $( 'input[type="hidden"][name="monk_active_languages[]"]' ).val();
			var selected_language = $( this ).val();
			$( 'input[type="checkbox"][name="monk_active_languages[]"]' ).each( function() {
				var current_language = $( this ).val();
				if ( current_language === selected_language ) {
					$( this ).prop({
						'disabled': true,
						'checked': true
					});
				} else {
					if ( $( this ).hasClass( 'monk-saved-language' ) ) {
						$( this ).prop({
							'disabled': false
						});
					} else {
						$( this ).prop({
							'disabled': false,
							'checked': false
						});
					}
				}
			});
		});
		$( document ).on( 'click', 'span.monk-add-translation', function() {
			$( '.monk-post-meta-add-translation' ).slideToggle( 200 );
		});
		$( document ).on( 'click', 'a.monk-cancel-submit-translation', function( e ) {
			$( '.monk-post-meta-add-translation' ).slideUp( 200 );
			e.preventDefault();
		});
		$( document ).on( 'click', 'input.monk-submit-translation', function( e ) {
			e.preventDefault();
		});
	});

})( jQuery );
