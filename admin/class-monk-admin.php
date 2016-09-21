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

		wp_enqueue_style( $this->monk, plugin_dir_url(__FILE__) . 'css/monk-admin.css', array(), $this->version, 'all' );

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
			'Monk Settings',
			'Monk',
			'manage_options',
			'monk',
			array( $this, 'monk_options' ),
			'dashicons-translation',
			3 );
	}

	public function monk_options_init() {
		register_setting( 'generalOptions', 'monk_language' );
		add_settings_section(
			'monk_general_options',
			'General options',
			array( $this, 'monk_settings_section_render'),
			'generalOptions'
		);
		add_settings_field(
			'monk_language_field',
			'Default language',
			array( $this, 'language_select_render' ),
			'generalOptions',
			'monk_general_options'
		);
	}

	public function language_select_render() {
		$options = get_option( 'monk_language' );
		?>
		<select name='monk_language[language_select]'>
			<option value='portuguese'<?php selected( $options['language_select'], 'portuguese' ); ?>>Portuguese</option>
			<option value='english'<?php selected( $options['language_select'], 'english' ); ?>>English</option>
			<option value='spanish'<?php selected( $options['language_select'], 'spanish' ); ?>>Spanish</option>
			<option value='french'<?php selected( $options['language_select'], 'french' ); ?>>French</option>
		</select>
		<?php
	}

	public function monk_settings_section_render() {
		echo _e( 'Adjust all you want', 'monk');
	}

	public function monk_options() {
		?>
		<div class='wrap'>
			<h2>Monk</h2>
			<form action='options.php' method='POST'>
				<?php
				settings_fields( 'generalOptions' );
				do_settings_sections( 'generalOptions' );
				submit_button();
				delete_option( 'language_select' );
				?>
			</form>
		</div>
		<?php
	}
}
