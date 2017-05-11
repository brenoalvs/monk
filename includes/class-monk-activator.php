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
		get_monk_languages();
		$monk_languages = get_transient( 'monk_languages' );

		$locale = get_locale();
		if ( array_key_exists( $locale, $monk_languages ) ) {
			$language = $locale;
		} else {
			$language = 'en_US';
		}

		update_option( 'monk_default_language', $language );
		update_option( 'monk_active_languages', array( $language ) );
		update_option( 'monk_settings_notice', true );
		update_option( 'monk_first_media_list_access', true );
		set_transient( '_monk_redirect', true, 30 );
	}

}
