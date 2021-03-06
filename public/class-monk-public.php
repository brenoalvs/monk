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
	 * @since  0.1.0
	 * @param  string $monk             The name of the plugin.
	 * @param  string $version          The version of this plugin.
	 * @param  string $default_language The default language of the plugin.
	 * @param  array  $active_languages The active languages of the plugin.
	 * @return void
	 */
	public function __construct( $monk, $version, $default_language, $active_languages ) {
		$this->plugin_name      = $monk;
		$this->version          = $version;
		$this->default_language = $default_language;
		$this->active_languages = $active_languages;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.1.0
	 * @return void
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/monk-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'public-monk-language-switcher-style', plugin_dir_url( __FILE__ ) . 'css/monk-widget.css', array( 'dashicons' ), $this->version, 'all' );
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

		if ( is_home() || is_archive() || is_search() ) {
			$filter_main_query = true;
		}

		if ( is_admin() || ( $query->is_main_query() && ! $filter_main_query ) || 'nav_menu_item' === $query->get( 'post_type' ) ) {
			return;
		}

		$monk_languages = monk_get_available_languages();

		$query_args       = array();
		$default_language = $this->default_language;
		$active_languages = $this->active_languages;
		$default_slug     = $monk_languages[ $default_language ]['slug'];
		$lang             = get_query_var( 'lang', $default_slug );
		$current_language = monk_get_locale_by_slug( $lang );

		// Hotfix for translatable static front page.
		if ( is_home() && 'page' === get_option( 'show_on_front' ) && ! is_page( get_option( 'page_for_posts' ) ) ) {
			$front_page_id     = get_option( 'page_on_front' );
			$group_id          = get_post_meta( $front_page_id, '_monk_post_translations_id', true );
			$translation_group = get_option( 'monk_post_translations_' . $group_id, false );

			$query->set( 'page_id', $translation_group[ $current_language ] );
			$query->is_home     = false;
			$query->is_page     = true;
			$query->is_singular = true;
			return;
		}

		if ( $current_language && in_array( $current_language, $active_languages ) ) {
			$query_args[] = array(
				'key'     => '_monk_post_language',
				'value'   => $current_language,
				'compare' => '=',
			);
		} else {
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
		}

		$meta_query = $query->get( 'meta_query' );

		if ( is_array( $meta_query ) ) {
			$query_args = array_merge( $meta_query, $query_args );
		}

		$query->set( 'meta_query', $query_args );
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

		$monk_languages = monk_get_available_languages();

		$default_language = $this->default_language;
		$active_languages = $this->active_languages;
		$default_slug     = $monk_languages[ $default_language ]['slug'];
		$current_language = get_query_var( 'lang', $default_slug );
		$current_language = monk_get_locale_by_slug( $current_language );
		$old_meta_query   = $args['meta_query'];

		if ( $current_language && in_array( $current_language, $active_languages ) ) {
			$args['meta_query'] = array(
				array(
					'key'     => '_monk_term_language',
					'value'   => $current_language,
					'compare' => '=',
				),
			);
		} else {
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
				),
			);
		}

		if ( is_array( $old_meta_query ) ) {
			$args['meta_query'] = array_merge( $old_meta_query, $args['meta_query'] );
		}

		return $args;
	}

	/**
	 * Function to filter menus when in the front-end
	 *
	 * @since    0.3.0
	 *
	 * @param  array $args Array of arguments.
	 * @return array $args Array of arguments.
	 */
	public function monk_filter_nav_menus( $args ) {
		$location         = $args['theme_location'];
		$menus            = get_nav_menu_locations();
		$language         = get_query_var( 'lang' );
		$default_language = $this->default_language;

		if ( $language ) {
			$language = monk_get_locale_by_slug( $language );

			if ( array_key_exists( $location, $menus ) ) {
				$menu_id           = $menus[ $location ];
				$monk_id           = get_term_meta( $menu_id, '_monk_menu_translations_id', true );
				$monk_translations = get_option( 'monk_menu_translations_' . $monk_id, array() );

				if ( ! empty( $monk_translations ) ) {
					if ( $language !== $default_language && array_key_exists( $language, $monk_translations ) ) {
						$args['menu'] = $monk_translations[ $language ];
					} elseif ( array_key_exists( $default_language, $monk_translations ) ) {
						$args['menu'] = $monk_translations[ $default_language ];
					} else {
						$menu_id_fallback = array_shift( $monk_translations );
						$args['menu']     = $menu_id_fallback;
					}
				}
			}
		}

		return $args;
	}

	/**
	 * Function to filter any option
	 *
	 * @since    0.5.0
	 *
	 * @param  mixed  $pre_option Value to return instead of the option value.
	 * @param  string $option     String option name.
	 * @return mixed  $pre_option Value to return instead of the option value.
	 */
	public function monk_filter_translatable_option( $pre_option, $option ) {
		$default_language = $this->default_language;
		$current_slug     = get_query_var( 'lang', false );
		$current_locale   = monk_get_locale_by_slug( $current_slug );

		if ( ! empty( $current_locale ) && $current_locale !== $default_language ) {
			$pre_option = get_option( 'monk_' . $current_locale . '_' . $option, false );
		}

		return $pre_option;
	}

	/**
	 * Retrieves translation in current language for page_on_front option.
	 *
	 * @param  int $page_id The page on front ID configured by user.
	 * @return int          Page on front translation ID.
	 */
	public function monk_page_on_front_translations( $page_id ) {
		if ( ! is_admin() ) {
			$page_id = monk_translated_post_id( $page_id );
		}

		return $page_id;
	}

	/**
	 * Prints the hreflang tags according to the current content.
	 *
	 * @return void
	 */
	public function monk_print_localization_tags() {
		if ( is_admin() ) {
			return;
		}

		$monk_localization_data = array();
		$translation_data       = monk_get_translations();

		foreach ( $translation_data as $slug => $data ) {
			$monk_localization_data[ $slug ] = $data['url'];
		}

		$monk_localization_data = apply_filters( 'monk_hreflang_tags', $monk_localization_data );

		foreach ( $monk_localization_data as $slug => $url ) {
			if ( null !== $url ) {
				echo '<link rel="alternate" href="' . esc_url( $url ) . '" hreflang="' . esc_attr( $slug ) . '" />' . PHP_EOL;
			}
		}
	}
}
