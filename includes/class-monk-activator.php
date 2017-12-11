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

		$default_post_category = get_term( get_option( 'default_category' ), OBJECT );
		$uncategorized_term    = get_term_by( 'id', 1, 'category' );

		update_option( 'monk_default_language', $language );
		update_option( 'monk_active_languages', array( $language ) );
		update_option( 'monk_settings_notice', true );
		update_option( 'monk_first_media_list_access', true );
		set_transient( '_monk_redirect', true, 30 );

		if ( $uncategorized_term && $uncategorized_term->term_id === $default_post_category->term_id ) {
			$term_translations[ $language ] = $default_post_category->term_id;
			update_term_meta( $default_post_category->term_id, '_monk_term_language', $language );
			update_term_meta( $default_post_category->term_id, '_monk_term_translations_id', $default_post_category->term_id );
			update_option( 'monk_term_translations_' . $default_post_category->term_id, $term_translations );
		}
	}

}
