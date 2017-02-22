<?php
/**
 * Define the internationalization functionality
 *
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Includes
 */

/**
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.1.0
 * @package    Monk
 * @subpackage Monk/Includes
 */
class Monk_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.1.0
	 * @return  void
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'monk',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

	/**
	 * Change locale accordingly Language Switcher.
	 *
	 * @since  0.1.0
	 * @param  string $locale Language of locale.
	 * @return string $locale New language of locale.
	 */
	public function monk_define_locale( $locale ) {
		global $monk_languages;
		$active_languages = get_option( 'monk_active_languages', false );
		if ( is_admin() ) {
			return $locale;
		}
		$language = preg_split( '/(\/)/', $_SERVER['REQUEST_URI'], 0, PREG_SPLIT_NO_EMPTY );
		$language = $language[0];

		if ( ! empty( $language ) ) {
			$lang = $language;
			foreach ( $active_languages as $lang_code ) {
				if ( $lang === $monk_languages[ $lang_code ]['slug'] ) {
					$locale = $lang_code;
				}
			}
		} else {
			$locale = get_option( 'monk_default_language', false );
		}

		return $locale;
	}

}
