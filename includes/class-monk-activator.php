<?php
/**
 * Fired during plugin activation
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Monk
 * @subpackage Monk/Includes
 * @author     Breno Alves <email@example.com>
 */
class Monk_Activator {

	/**
	 * Plugin for translations
	 *
	 * Activation function
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $monk_languages;
		$locale = get_locale();
		if ( array_key_exists( $locale, $monk_languages ) ) {
			$language = get_locale();
		} else {
			$language = 'en_US';
		}

		update_option( 'monk_default_language', $language );
		update_option( 'monk_active_languages', array( $language ) );
	}

}
