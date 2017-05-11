<?php
/**
 * Provide the view for the monk_default_language_render function
 *
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$languages = get_available_languages();
$args = array(
	'id'        => 'monk_default_language',
	'name'      => 'monk_default_language',
	'selected'  => $default_language,
	'languages' => $languages,
);
wp_dropdown_languages( $args );
