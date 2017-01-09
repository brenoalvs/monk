<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Monk
 * @subpackage Monk/Public
 * @author     Breno Alves <breno.alvs@gmail.com>
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
	 * @param      string    $monk       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $monk, $version ) {

		$this->plugin_name = $monk;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Monk_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Monk_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/monk-public.css', array(), $this->version, 'all' );

		/**
		 * This function does enqueue jquery-ui .css files in public side.
		 */
		wp_enqueue_style( 'jquery-ui-style', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), $this->version, 'all' );

		/**
		 * This function does enqueue monk widget .css files in public side.
		 */
		wp_enqueue_style( 'public-monk-language-switcher-style', plugin_dir_url( __FILE__ ) . 'css/monk-widget.css', array(), $this->version, 'all' );

		/**
		 * This function does enqueue flag icon .css files in public side.
		 */
		wp_enqueue_style( 'monk-flags', plugin_dir_url( dirname( __FILE__ ) ) . 'lib/css/flag-icon.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Monk_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Monk_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/monk-public.js', array( 'jquery' ), $this->version, false );

		/**
		 * This function does enqueue monk widget .js files in public side.
		 */
		wp_enqueue_script( 'monk-language-switcher-script', plugin_dir_url( __FILE__ ) . 'js/monk-widget.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-selectmenu' ), $this->version, true );
	}

	/**
	 * Function to filter posts when in the front-end.
	 *
	 * @since    0.1.0
	 * @param    Object $query WP_query object.
	 */
	public function monk_public_posts_filter( $query ) {
		if ( is_admin() || $query->is_main_query() && ! ( is_front_page() || is_post_type_archive() ) ) {
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

		if ( $default_language === $current_language ) {
			$query_args[] = array(
				'relation' => 'OR',
				array(
					'key'   => '_monk_post_language',
					'value' => $current_language,
				),
				array(
					'key'     => '_monk_post_language',
					'compare' => 'NOT EXISTS',
				)
			);
		} else {
			$query_args[] = array(
				'key'   => '_monk_post_language',
				'value' => $current_language,
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
	 * @return Object $term_query Instance of $WP_Term_Query .
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

		if ( $default_language === $current_language ) {
			$query_args['meta_query'] = array(
				'relation' => 'OR',
				array(
					'key'   => '_monk_term_language',
					'value' => $current_language,
				),
				array(
					'key'     => '_monk_term_language',
					'compare' => 'NOT EXISTS',
				)
			);
		} else {
			$query_args = array(
				'meta_key'   => '_monk_term_language',
				'meta_value' => $current_language,
			);
		}

		$term_query->parse_query( $query_args );
	}
}
