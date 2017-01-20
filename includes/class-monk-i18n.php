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
	 * @param  string $lang Language of locale.
	 * @return string $lang New language of locale.
	 */
	public function monk_define_locale( $lang ) {
		if ( is_admin() ) {
			return $lang;
		}

		if ( isset( $_GET['lang'] ) ) {
			$lang = sanitize_text_field( wp_unslash( $_GET['lang'] ) );
		} else {
			$lang = get_option( 'monk_default_language', false );
		}

		return $lang;
	}

}
