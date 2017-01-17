<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.1.0
 * @package    Monk
 * @subpackage Monk/Includes
 * @author     Breno Alves <breno.alvs@gmail.com>
 */
class Monk_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.1.0
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
