<?php
/**
 * Usefull global functions.
 *
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Includes
 */

/**
 * Validates a language code.
 * Validate if a language code value is secure to use across the application.
 *
 * @since  0.1.0
 *
 * @param  string $language_code 	A language code value to validate.
 * @return boolean 					Language code validation.
 */
function monk_is_language_code( $language_code ) {
	global $monk_languages;
	$languages_codes = array_keys( $monk_languages );

	return in_array( $language_code , $language_codes );
}

/**
 * Returns WordPress current URL
 *
 * @since 0.1.0
 *
 * @return string Current URL
 */
function monk_get_current_url() {
	global $wp;

	$query_arg   = http_build_query( $_GET );
	$current_url = add_query_arg( $query_arg, '', home_url( $wp->request ) );

	return $current_url;
}

/**
 * Returns the locale code of a given language.
 *
 * @since 0.1.0
 *
 * @param     string $slug the slug for the rewuired language locale.
 * @return    string Current URL
 */
function monk_get_locale_by_slug( $slug ) {
	global $monk_languages;

	foreach ( $monk_languages as $locale => $data ) {
		if ( $data['slug'] === $slug ) {
			$slug = $locale;
		}
	}

	return $slug;
}
