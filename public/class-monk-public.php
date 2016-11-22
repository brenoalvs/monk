<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
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
 * @author     Breno Alves <email@example.com>
 */
class Monk_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $monk    The ID of this plugin.
	 */
	private $monk;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
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
	 * @since    1.0.0
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
	 * @since    1.0.0
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
	 * Function to filter terms when in the front-end
	 *
	 * @param    string    $version    The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function monk_public_terms_filter( $term_query ) {
		$args = array();

		if ( ! is_admin() && isset( get_query_var( 'lang' ) ) ) {
			$args             = array( 'meta_key' => '_monk_term_language' );
			$lang_query       = get_query_var( 'lang' );
			$default_language = get_option( 'monk_default_language', false );

			if ( isset( $lang_query ) && $lang_query ) {
				$args['meta_value'] = $lang_query;
			}
		}

		return $term_query->parse_query( $args ); 
	}
}
