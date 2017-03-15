<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Monk
 * @subpackage Monk/Public
 */
class Monk_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $monk    The ID of this plugin.
	 */
	private $monk;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param      string $monk       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 * @return void
	 */
	public function __construct( $monk, $version ) {
		$this->plugin_name = $monk;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.1.0
	 * @return void
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/monk-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'public-monk-language-switcher-style', plugin_dir_url( __FILE__ ) . 'css/monk-widget.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'monk-flags', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/css/monk-flags.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    0.1.0
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/monk-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'monk-language-switcher-script', plugin_dir_url( __FILE__ ) . 'js/monk-widget.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-selectmenu' ), $this->version, true );
	}

	/**
	 * Function to filter posts when in the front-end.
	 *
	 * @since    0.1.0
	 * @param    Object $query WP_query object.
	 * @return void
	 */
	public function monk_public_posts_filter( $query ) {
		// Whether we must filter main query.
		$filter_main_query = false;

		if ( is_home() || is_front_page() || is_archive() || is_search() ) {
			$filter_main_query = true;
		}

		if ( is_admin() || ( $query->is_main_query() && ! $filter_main_query ) || 'nav_menu_item' === $query->get('post_type') ) {
			return;
		}

		global $monk_languages;

		$query_args       = array();
		$default_language = get_option( 'monk_default_language', false );
		$default_slug     = $monk_languages[ $default_language ]['slug'];
		$current_language = get_query_var( 'lang', $default_slug );

		if ( is_singular() ) {
			$current_language = get_post_meta( get_queried_object_id(), '_monk_post_language', true );
		} elseif ( is_tax() || is_category() || is_tag() ) {
			$current_language = get_term_meta( get_queried_object_id(), '_monk_term_language', true );
		} else {
			$current_language = monk_get_locale_by_slug( $current_language );
		}

		if ( $default_language === $current_language ) {
			$query_args[] = array(
				'relation' => 'OR',
				array(
					'key'     => '_monk_post_language',
					'value'   => $default_language,
					'compare' => '=',
				),
				array(
					'key'     => '_monk_post_language',
					'compare' => 'NOT EXISTS',
				),
			);
		} else {
			$query_args[] = array(
				'key'     => '_monk_post_language',
				'value'   => $current_language,
				'compare' => '=',
			);
		}

		$query->set( 'meta_query', array( $query_args ) );
	}

	/**
	 * Function to filter terms when in the front-end
	 *
	 * @since    0.1.0
	 *
	 * @param  array $args Array of arguments.
	 * @return array $args Array of arguments.
	 */
	public function monk_public_terms_filter( $args ) {
		if ( is_admin() ) {
			return $args;
		}

		global $monk_languages;

		$default_language = get_option( 'monk_default_language', false );
		$default_slug     = $monk_languages[ $default_language ]['slug'];
		$current_language = get_query_var( 'lang', $default_slug );

		if ( is_singular() ) {
			$current_language = get_post_meta( get_queried_object_id(), '_monk_post_language', true );
		} elseif ( is_tax() || is_category() || is_tag() ) {
			$current_language = get_term_meta( get_queried_object_id(), '_monk_term_language', true );
		} else {
			$current_language = monk_get_locale_by_slug( $current_language );
		}

		$current_language = monk_get_locale_by_slug( $current_language );

		if ( $default_language === $current_language ) {
			$args['meta_query'] = array(
				'relation' => 'OR',
				array(
					'key'     => '_monk_term_language',
					'value'   => $default_language,
					'compare' => '=',
				),
				array(
					'key'     => '_monk_term_language',
					'compare' => 'NOT EXISTS',
				)
			);
		} else {
			$args['meta_query'] = array(
				array(
					'key'     => '_monk_term_language',
					'value'   => $current_language,
					'compare' => '=',
				),
			);
		}

		return $args;
	}

	public function monk_show_menu_translation( $args ) {
		$location         = $args['theme_location'];
		$menus            = get_nav_menu_locations();
		$language         = get_query_var( 'lang' );
		$default_language = get_option( 'monk_default_language', false );

		$language  = monk_get_locale_by_slug( $language );

		if ( array_key_exists( $location, $menus ) ) {
			$menu_id           = $menus[ $location ];
			$monk_id           = get_term_meta( $menu_id, '_monk_term_translations_id', true );
			$monk_translations = get_option( 'monk_term_translations_' . $monk_id, false );

			if ( $language !== $default_language && ( is_array( $monk_translations ) && array_key_exists( $language, $monk_translations ) ) ) {
				$menus[ $location ] = $monk_translations[ $language ];
			} elseif ( is_array( $monk_translations ) && array_key_exists( $default_language, $monk_translations ) ) {
				$menus[ $location ] = $monk_translations[ $default_language ];
			} else {
				$menu_id_fallback = array_shift( $monk_translations );
				$menus[ $location ] = $menu_id_fallback;
			}

			set_theme_mod( 'nav_menu_locations', $menus );
		}

		return $args;
	}
}
