<?php
/**
 * Fired during plugin activation
 *
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.1.0
 * @package    Monk
 * @subpackage Monk/Includes
 */
class Monk_Activator {

	/**
	 * Plugin for translations
	 *
	 * Activation function
	 *
	 * @since    0.1.0
	 * @return  void
	 */
	public static function activate() {
		$monk_languages = monk_get_available_languages();

		$language = get_locale() ? get_locale() : 'en_US';

		update_option( 'monk_default_language', $language );
		update_option( 'monk_active_languages', array( $language ) );
		update_option( 'monk_settings_notice', true );
		update_option( 'monk_first_media_list_access', true );
		set_transient( '_monk_redirect', true, 30 );
	}

}
