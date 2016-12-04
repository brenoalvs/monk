(function( $ ) {

	/**
	 * Defines toggle event on click #monk-seletor to show and hide language list
	 */
	$( '.monk-current-lang' ).on( 'click', function( event ) {
		event.stopPropagation();
		$( '.monk-current-lang' ).toggleClass( 'monk-language-switcher-open' );
		$( '.monk-language-dropdown' ).toggle();
	});

	/**
	 * Defines toggle event on click #monk-seletor to show and hide language list
	 */
	$( document ).on( 'click', function() {
		$( '.monk-current-lang' ).removeClass( 'monk-language-switcher-open' );
		$( '.monk-language-dropdown' ).hide();
	});
})( jQuery );
