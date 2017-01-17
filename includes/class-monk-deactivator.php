<?php
/**
 * Fired during plugin deactivation
 *
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.1.0
 * @package    Monk
 * @subpackage Monk/Includes
 */
class Monk_Deactivator {

	/**
	 * Plugin for translations
	 *
	 * Desactivation function
	 *
	 * @since    0.1.0
	 */
	public static function deactivate() {
		delete_option( 'monk_settings_notice' );
	}

}
