(function( $ ) {

	/**
	 * Defines toggle event on click #monk-seletor to show and hide language list
	 */
	$( '.monk-active-lang' ).on( 'click', function( event ) {
		event.stopPropagation();
		$( '.monk-language-switcher' ).toggle();
	});

	/**
	 * Defines toggle event on click #monk-seletor to show and hide language list
	 */
	$( document ).on( 'click', '.monk-language-switcher' , function() {
		$( this ).hide();
	});
})( jQuery );
