<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Monk
 * @subpackage Monk/Admin
 * @author     Breno Alves
 */
class Monk_Admin {

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
	 * @param      string    $monk       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $monk, $version ) {

		$this->monk = $monk;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Monk_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->monk, plugin_dir_url( __FILE__ ) . 'css/monk-admin.css', array(), $this->version, 'all' );

		/**
		 * This function does enqueue widget .css files in admin side.
		 */
		wp_enqueue_style( 'monk-widgets', plugin_dir_url( __FILE__ ) . 'css/monk-widgets.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->monk, plugin_dir_url( __FILE__ ) . 'js/monk-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Function to register the settings page of the plugin
	 *
	 * @since    1.0.0
	 */
	public function monk_add_menu_page() {
		add_menu_page(
			__( 'Monk Settings' ),
			'Monk',
			'manage_options',
			'monk',
			array( $this, 'monk_options' ),
			'dashicons-translation',
			3
		);
	}

	/**
	 * Function to create a section for General Options in the administration menu
	 *
	 * @since    1.0.0
	 */
	public function monk_options_init() {
		add_settings_section(
			'monk_general_options',
			__( 'General options', 'monk' ),
			array( $this, 'monk_settings_section_render'),
			'general_options'
		);

		register_setting( 'general_options', 'monk_default_language' );
		add_settings_field(
			'monk_language_field',
			__( 'Default language', 'monk' ),
			array( $this, 'monk_language_select_render' ),
			'general_options',
			'monk_general_options'
		);

		register_setting( 'general_options', 'monk_active_languages' );
		add_settings_field(
			'monk_list',
			__( 'Add new translation' ),
			array( $this, 'monk_translation_list_render' ),
			'general_options',
			'monk_general_options'
		);
	}

	/**
	 * This is the callback for the monk_general_options section
	 *
	 * Prints a description in the section
	 *
	 * @since    1.0.0
	 */
	public function monk_settings_section_render() {
		_e( 'Adjust the way you want', 'monk');
	}

	/**
	 * Function to render the select field, callback for the monk_language_field element
	 *
	 * @since    1.0.0
	 */
	public function monk_language_select_render() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/admin-monk-select-render.php';
	}

	/**
	 * Function to render the checkbox field, callback for the monk_translation_list element
	 *
	 * @since    1.0.0
	 */
	public function monk_translation_list_render() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/admin-monk-translation-list-render.php';
	}

	/**
	 * Function to render the admin page for the plugin
	 *
	 * @since    1.0.0
	 */
	public function monk_options() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/admin-monk-options.php';
	}
}
