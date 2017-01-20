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
		if ( is_admin() || $query->is_main_query() && ! ( is_front_page() || is_post_type_archive() || is_date() ) ) {
			return;
		}
		global $monk_languages;

		$query_args       = array();
		$default_language = get_option( 'monk_default_language', false );
		$current_language = get_query_var( 'lang', false );
		$active_languages = get_option( 'monk_active_languages' );
		$current_url      = monk_get_current_url();

		foreach ( $active_languages as $lang_code ) {
			$active_slug[ $lang_code ] = $monk_languages[ $lang_code ]['slug'];
		}

		if ( $current_language && ! in_array( $current_language, $active_slug ) ) {
			$current_url = add_query_arg( 'lang', esc_attr( $monk_languages[ $default_language ]['slug'], 'monk' ), $current_url );
			wp_safe_redirect( $current_url );
			exit;
		}

		if ( ! $current_language ) {
			if ( is_singular() ) {
				$current_language = get_post_meta( get_queried_object_id(), '_monk_post_language', true );
			} elseif ( is_archive() && ( is_category() || is_tag() ) ) {
				$current_language = get_term_meta( get_queried_object_id(), '_monk_term_language', true );
			} else {
				$current_language = $default_language;
			}
		}

		if ( ! $current_language || substr( $default_language, 0, 2 ) === substr( $current_language, 0, 2 ) ) {
			$query_args[] = array(
				'relation' => 'OR',
				array(
					'key'     => '_monk_post_language',
					'value'   => $default_language,
					'compare' => 'LIKE',
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
				'compare' => 'LIKE',
			);
		}

		$query->set( 'meta_query', $query_args );
	}

	/**
	 * Function to filter terms when in the front-end
	 *
	 * @since    0.1.0
	 *
	 * @param  Object $term_query Instance of $WP_Term_Query.
	 * @return Object $term_query Instance of $WP_Term_Query.
	 */
	public function monk_public_terms_filter( $term_query ) {
		if ( is_admin() ) {
			return;
		}

		$query_args       = array();
		$default_language = get_option( 'monk_default_language', false );
		$current_language = get_query_var( 'lang', false );

		if ( ! $current_language ) {
			if ( is_singular() ) {
				$current_language = get_post_meta( get_queried_object_id(), '_monk_post_language', true );
			} elseif ( is_archive() && ( is_category() || is_tag() ) ) {
				$current_language = get_term_meta( get_queried_object_id(), '_monk_term_language', true );
			} else {
				$current_language = $default_language;
			}
		}

		if ( ! $current_language || substr( $default_language, 0, 2 ) === substr( $current_language, 0, 2 ) ) {
			$query_args['meta_query'] = array(
				'relation' => 'OR',
				array(
					'key'     => '_monk_term_language',
					'value'   => $default_language,
					'compare' => 'LIKE',
				),
				array(
					'key'     => '_monk_term_language',
					'compare' => 'NOT EXISTS',
				)
			);
		} else {
			$query_args['meta_query'] = array(
				array(
					'key'     => '_monk_term_language',
					'value'   => $current_language,
					'compare' => 'LIKE',
				),
			);
		}

		$term_query->parse_query( $query_args );
	}
}
