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
class Monk_I18n {


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
	 * Changes locale accordingly requested URL.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $locale Language of locale.
	 * @return string $locale New language of locale.
	 */
	public function monk_define_locale( $locale ) {
		if ( is_admin() ) {
			return $locale;
		}

		$monk_languages = monk_get_available_languages();

		/**
		 * As locale is defined before WordPress parse the request.
		 * We need get language directly from URL.
		 */
		$path     = $_SERVER['REQUEST_URI'];
		$has_args = strpos( $path, '?' );

		if ( false !== $has_args ) {
			$path_parts = str_split( $path, $has_args );
			$path       = $path_parts[0];
		}

		$matches = preg_split( '/(\/)/', $path, 0, PREG_SPLIT_NO_EMPTY );
		$slug    = filter_input( INPUT_GET, 'lang' );

		if ( ! empty( $matches ) ) {
			$slug             = $matches[0];
			$locale           = monk_get_locale_by_slug( $slug );
			$active_languages = get_option( 'monk_active_languages', false );

			if ( ! in_array( $locale, $active_languages, true ) ) {
				$locale = get_option( 'monk_default_language', false );
			}
		} elseif ( $slug ) {
			$locale = monk_get_locale_by_slug( $slug );
		} else {
			$locale = get_option( 'monk_default_language', false );
		}

		return $locale;
	}

}
