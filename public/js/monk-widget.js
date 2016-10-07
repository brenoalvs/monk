(function( $ ) {

	/**
	 * Defines toggle event on click #monk-seletor to show and hide language list
	 */
	$( '#monk-selector' ).on( 'click', function() {
		$( '.monk-lang' ).toggle();
	});

	/**
	 * Defines toggle event on click #monk-seletor to show and hide language list
	 */
	$( '#monk-selector' ).on( 'mouseleave', function() {
		$( '.monk-lang' ).hide();
	});
})( jQuery );
