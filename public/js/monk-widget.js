(function( $ ) {

	/**
	 * Function for include icon on select menu.
	 */
	$.widget( 'custom.iconselectmenu', $.ui.selectmenu, {
	 	_renderItem: function( ul, item ) {
	 		var li = $( '<li>' ),
	 		wrapper = $( '<div>', { text: item.label } );

	 		if ( item.disabled ) {
	 			li.addClass( 'ui-state-disabled' );
	 		}

	 		$( '<span>', {
	 			style: item.element.attr( 'data-style' ),
	 			'class': 'ui-icon ' + item.element.attr( 'data-class' )
	 		})
	 		.appendTo( wrapper );

	 		return li.append( wrapper ).appendTo( ul );
	 	}
	});

	/**
	 * Defines #widget-language-select as jquery-ui iconselectmenu and create form submit event on change #widget-language-select
	 * to change URL
	 */
	$( '#monk-widget-language-selector' ).iconselectmenu({
  		change: function( event, ui ) {
  			$( '#monk-form-language' ).submit();
  		}
	}).iconselectmenu( 'menuWidget' ).addClass( 'ui-menu-icons avatar' );
})( jQuery );
