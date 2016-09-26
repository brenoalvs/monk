<?php

/**
 * Provide the view for the monk_select_render function
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

	$options = get_option( 'monk_language' );
	?>
	<select name='monk_language[language_select]'>
		<option value='portuguese'<?php selected( $options['language_select'], 'portuguese' ); ?>>Portuguese</option>
		<option value='english'<?php selected( $options['language_select'], 'english' ); ?>>English</option>
		<option value='spanish'<?php selected( $options['language_select'], 'spanish' ); ?>>Spanish</option>
		<option value='french'<?php selected( $options['language_select'], 'french' ); ?>>French</option>
	</select>
