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
		if ( $data['slug'] === $slug || $slug === $locale ) {
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
	require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
	$wp_get_available_translations = wp_get_available_translations();

	if ( ! $monk_languages ) {
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

			$slug = $slug;

			$monk_languages[ $locale ] = array(
				'native_name'  => $lang_content['native_name'],
				'english_name' => $lang_content['english_name'],
				'slug'         => $slug,
			);
		} // End foreach().

		uasort( $monk_languages, function( $a, $b ) {
			return strcmp( $a['english_name'], $b['english_name'] );
		});

		set_transient( 'monk_languages', $monk_languages, YEAR_IN_SECONDS );
	} // End if().

	$monk_languages['en_US']['slug'] = apply_filters( 'monk_custom_language_slug', 'en', 'en_US' );
	foreach ( $wp_get_available_translations as $locale => $lang_content ) {
		$slug                              = $monk_languages[ $locale ]['slug'];
		$monk_languages[ $locale ]['slug'] = apply_filters( 'monk_custom_language_slug', $slug, $locale );
	}
	return $monk_languages;
}

/**
 * Retrieves translation data for current page.
 *
 * @since  0.7.0
 *
 * @return array $translation_data
 */
function monk_get_translations() {
	$monk_languages = monk_get_available_languages();

	$translation_data         = array();
	$active_languages_slug    = array();
	$current_language_slug    = '';
	$active_languages         = Monk()->get_active_languages();
	$default_language         = Monk()->get_default_language();
	$has_default_language_url = get_option( 'monk_default_language_url', false );
	$current_locale           = monk_get_locale_by_slug( get_query_var( 'lang' ) );
	$default_slug             = $monk_languages[ $default_language ]['slug'];

	if ( $current_locale && in_array( $current_locale, $active_languages, true ) ) {
		$current_language_slug = sanitize_text_field( get_query_var( 'lang' ) );
	} else {
		$current_language_slug = $monk_languages[ $default_language ]['slug'];
		$current_locale        = $default_language;
	}

	foreach ( $monk_languages as $lang_code => $list ) {
		if ( in_array( $lang_code, $active_languages, true ) ) {
			$translation_data[ $list['slug'] ]['id']           = 0;
			$translation_data[ $list['slug'] ]['code']         = $lang_code;
			$translation_data[ $list['slug'] ]['slug']         = $list['slug'];
			$translation_data[ $list['slug'] ]['current']      = false;
			$translation_data[ $list['slug'] ]['native_name']  = $list['native_name'];
			$translation_data[ $list['slug'] ]['english_name'] = $list['english_name'];
			$translation_data[ $list['slug'] ]['url']          = null;
		}
	}

	// Caso 1.
	if ( is_front_page() || is_post_type_archive() || is_date() || is_404() || is_author() || is_search() ) {

		foreach ( $active_languages as $code ) {
			$active_languages_slug[]           = $monk_languages[ $code ]['slug'];
		}

		foreach ( $active_languages_slug as $lang_slug ) {
			$current_url                                    = monk_get_current_url();
			$locale_by_slug                                 = monk_get_locale_by_slug( $lang_slug );

			if ( $lang_slug !== $current_language_slug ) {
				if ( get_option( 'permalink_structure', false ) ) {
					if ( get_query_var( 'lang' ) ) {
						$pattern                  = '/\/(' . implode( '|', $active_languages_slug ) . ')/';
						$current_url              = remove_query_arg( 'lang', $current_url );
						$current_url              = is_ssl() ? str_replace( 'https://', '', $current_url ) : str_replace( 'http://', '', $current_url );

						if ( empty( $has_default_language_url ) && $lang_slug === $default_slug ) {
							$current_url = preg_replace( $pattern, '', $current_url );
						} else {
							$current_url = preg_replace( $pattern, '/' . $lang_slug, $current_url );
						}

						$current_url = is_ssl() ? 'https://' . $current_url : 'http://' . $current_url;
					} else {
						if ( dirname( $_SERVER['PHP_SELF'] ) === '\\' || dirname( $_SERVER['PHP_SELF'] ) === '/' ) {
							$current_url = str_replace( home_url(), home_url() . $lang_slug . '/', $current_url );
						} else {
							$current_url = str_replace( dirname( $_SERVER['PHP_SELF'] ), trailingslashit( dirname( $_SERVER['PHP_SELF'] ) ) . $lang_slug, $current_url );
						}
					}
					$translation_data[ $lang_slug ]['url'] = $current_url;
				} else {
					if ( empty( $has_default_language_url ) && $lang_slug === $default_slug ) {
						$translation_data[ $lang_slug ]['url'] = remove_query_arg( 'lang', $current_url );
					} else {
						$translation_data[ $lang_slug ]['url'] = add_query_arg( 'lang', sanitize_key( $lang_code ) );
					}
				}
			}
		}
	} // End if().

	// Caso 2.
	if ( is_singular() && ! is_front_page() ) {
		$current_id         = get_the_id();
		$current_locale     = get_post_meta( $current_id, '_monk_post_language', true );
		$translations_id    = get_post_meta( $current_id, '_monk_post_translations_id', true );
		$total_translations = get_option( 'monk_post_translations_' . $translations_id, false );

		if ( $current_locale ) {
			$current_language_slug = $monk_languages[ $current_locale ]['slug'];
		} else {
			$current_language_slug = $monk_languages[ $default_language ]['slug'];
			$current_locale   = $default_language;
		}

		if ( is_array( $total_translations ) ) {
			foreach ( $total_translations as $lang_code => $post_id ) {
				if ( in_array( $lang_code, $active_languages, true ) || $monk_languages[ $lang_code ]['slug'] === $current_language_slug ) {
					$monk_translations[ $lang_code ] = $post_id;
				}
			}
		} else {
			$monk_translations[ $current_locale ] = $current_id;
		}

		foreach ( $monk_translations as $lang_code => $post_id ) {
			$translation_data[ $monk_languages[ $lang_code ]['slug'] ]['url'] = get_permalink( $post_id );
			$translation_data[ $monk_languages[ $lang_code ]['slug'] ]['id']  = $post_id;
		}

		foreach ( $active_languages as $code ) {
			if ( in_array( $code, $monk_translations, true ) ) {
				$translation_data[ $monk_languages[ $code ]['slug'] ]['code'] = $code;
				$translation_data[ $monk_languages[ $code ]['slug'] ]['native_name'] = $monk_languages[ $code ]['native_name'];
				$translation_data[ $monk_languages[ $code ]['slug'] ]['english_name'] = $monk_languages[ $code ]['english_name'];
			}
		}
	} // End if().

	// Caso 3.
	if ( is_archive() && ( is_category() || is_tag() || is_tax() ) ) {
		$monk_term_translations_id = get_term_meta( get_queried_object_id(), '_monk_term_translations_id', true );
		$total_translations        = get_option( 'monk_term_translations_' . $monk_term_translations_id, false );

		if ( get_term_meta( get_queried_object_id(), '_monk_term_language', true ) ) {
			$current_language_slug = $monk_languages[ get_term_meta( get_queried_object_id(), '_monk_term_language', true ) ]['slug'];
			$current_locale   = get_term_meta( get_queried_object_id(), '_monk_term_language', true );
		} else {
			$current_language_slug = $monk_languages[ $default_language ]['slug'];
			$current_locale   = $default_language;
		}

		if ( is_array( $total_translations ) ) {
			foreach ( $total_translations as $lang_code => $term_id ) {
				if ( in_array( $lang_code, $active_languages, true ) || $monk_languages[ $lang_code ]['slug'] === $current_language_slug ) {
					$monk_translations[ $lang_code ] = $term_id;
				}
			}
		} else {
			$monk_translations[ $current_locale ] = get_the_id();
		}

		if ( $monk_translations ) {
			foreach ( $monk_translations as $lang_code => $term_id ) {
				if ( $monk_languages[ $lang_code ]['slug'] !== $current_language_slug ) {
					$translation_data[ $monk_languages[ $lang_code ]['slug'] ]['url'] = get_term_link( $term_id );
				}
			}
		} else {
			$current_language_slug                       = $monk_languages[ $monk->get_default_language() ]['slug'];
			$monk_translations[ $current_language_slug ] = get_queried_object_id();

			foreach ( $monk_translations as $lang_code => $term_id ) {
				if ( $lang_code !== $current_language_slug ) {
					$translation_data[ $monk_languages[ $lang_code ]['slug'] ]['url'] = get_term_link( $term_id );
				}
			}
		}
	} // End if().

	foreach ( $translation_data as $slug => $list ) {
		if ( $current_language_slug === $slug ) {
			$translation_data[ $slug ]['current'] = true;
		}
	}

	return $translation_data;
}

/**
 * Function to get post translation id.
 *
 * Retrieves ID of translation in a given language or in current site language.
 *
 * @param  int    $id   Id of the post.
 * @param  string $lang Language slug.
 *
 * @return int    $id   Id of post translation.
 */
function monk_translated_post_id( $id, $lang = null ) {
	if ( empty( $lang ) ) {
		$default_language = Monk()->get_default_language();
		$lang             = get_query_var( 'lang', $default_language );
	}

	$current_language = monk_get_locale_by_slug( $lang );
	$post_group_id    = get_post_meta( $id, '_monk_post_translations_id', true );
	$page_group       = get_option( 'monk_post_translations_' . $post_group_id, array() );

	$id = array_key_exists( $current_language, $page_group ) ? $page_group[ $current_language ] : $id;

	return $id;
}
