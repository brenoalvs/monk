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
 * @param  string $language_code    A language code value to validate.
 * @return boolean                  Language code validation.
 */
function monk_is_language_code( $language_code ) {
	$monk_languages  = monk_get_available_languages();
	$languages_codes = array_keys( $monk_languages );

	return in_array( $language_code , $language_codes, true );
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
	$base_link   = ( is_ssl() ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$current_url = add_query_arg( $query_arg, '', $base_link );

	return $current_url;
}

/**
 * Returns the locale code of a given language.
 *
 * @since 0.2.0
 *
 * @param     string $slug A slug to search for.
 * @return    mixed        The related locale or false if $slug is invalid.
 */
function monk_get_locale_by_slug( $slug ) {
	$monk_languages = monk_get_available_languages();

	foreach ( $monk_languages as $locale => $data ) {
		if ( $data['slug'] === $slug ) {
			return $locale;
		}
	}

	return false;
}

/**
 * Returns an URL parameter.
 *
 * @since  0.2.0
 * @param  string $arg The desired parameter.
 *
 * @return array The URL parameter.
 */
function monk_get_url_args( $arg ) {
	$url    = $_SERVER['HTTP_REFERER'];
	$query  = parse_url( $url );
	$query  = array_key_exists( 'query',  $query ) ? $query['query'] : '';
	$query  = parse_str( $query, $name );

	if ( isset( $name[ $arg ] ) ) {
		return $name[ $arg ];
	} else {
		return;
	}
}

/**
 * This function makes the array of available languages
 *
 * @since  0.4.0
 *
 * @return array The available languages array.
 */
function monk_get_available_languages() {
	$monk_languages = get_transient( 'monk_languages' );

	if ( ! $monk_languages ) {
		require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
		$wp_get_available_translations = wp_get_available_translations();
		$monk_languages['en_US']       = array(
			'native_name'  => 'English (United States)',
			'english_name' => __( 'English (United States)', 'monk' ),
			'slug'         => 'en',
		);

		foreach ( $wp_get_available_translations as $locale => $lang_content ) {
			$slug_pos = strpos( $locale, '_' );
			$slug     = strtolower( substr( $locale, $slug_pos + 1, 2 ) );

			switch ( $locale ) {
				case 'ary':
					$slug = array_shift( $lang_content['iso'] ) . '-ma';
					break;
				case 'azb':
					$slug = array_shift( $lang_content['iso'] ) . '-az';
					break;
				case 'de_CH_informal':
					$slug = array_shift( $lang_content['iso'] ) . '-' . $slug . '-in';
					break;
				case 'de_DE_formal':
				case 'de_CH':
				case 'en_AU':
				case 'en_CA':
				case 'en_NZ':
				case 'en_ZA':
				case 'en_GB':
				case 'es_CO':
				case 'es_GT':
				case 'es_MX':
				case 'es_VE':
				case 'es_PE':
				case 'es_CL':
				case 'es_AR':
				case 'fr_CA':
				case 'fr_BE':
				case 'nl_NL_formal':
				case 'nl_BE':
				case 'pt_BR':
				case 'zh_TW':
				case 'zh_HK':
					$slug = array_shift( $lang_content['iso'] ) . '-' . $slug;
					break;
				default:
					$slug = array_shift( $lang_content['iso'] );
					break;
			} // End switch().

			$wp_languages[ $locale ] = array(
				'native_name' => $lang_content['native_name'],
				'english_name' => $lang_content['english_name'],
				'slug' => $slug,
			);
		} // End foreach().

		$monk_languages = array_merge( $monk_languages, $wp_languages );

		set_transient( 'monk_languages', $monk_languages, YEAR_IN_SECONDS );
	} // End if().

	uasort( $monk_languages, function( $a, $b ) {
			return strcmp( $a['english_name'], $b['english_name'] );
	});

	return $monk_languages;
}
