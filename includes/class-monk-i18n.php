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
	 * The default language of the plugin.
	 *
	 * @since    0.7.0
	 * @access   private
	 * @var      string    $default_language    The default language of the plugin.
	 */
	private $default_language;

	/**
	 * The active languages of the plugin.
	 *
	 * @since    0.7.0
	 * @access   private
	 * @var      array $active_languages The active languages of the plugin.
	 */
	private $active_languages;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since  0.7.0
	 * @param  string $default_language The default language of the plugin.
	 * @param  array  $active_languages The active languages of the plugin.
	 * @return  void
	 */
	public function __construct( $default_language, $active_languages ) {
		$this->default_language = $default_language;
		$this->active_languages = $active_languages;
	}

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
			$active_languages = $this->active_languages;

			if ( ! in_array( $locale, $active_languages, true ) ) {
				$locale = $this->default_language;
			}
		} elseif ( $slug ) {
			$locale = monk_get_locale_by_slug( $slug );
		} else {
			$locale = $this->default_language;
		}

		return $locale;
	}

}
