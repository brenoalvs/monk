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
	public function register_monk_settings() {
		add_menu_page(
			'Monk Setings',
			'Monk',
			'manage_options',
			'monk-settings',
			array( $this, 'monk_settings_fields' ),
			'dashicons-translation',
			3
		);
	}

	/**
	 * Function to create the option fields in the settings page, each field will have its unique function
	 *
	 * @since    1.0.0
	*/
	public function monk_settings_fields() {
		add_settings_section( 'monk-general-options', 'General options', null, 'monk-settings' );
		add_settings_field(
			'language-select',
			'Select your language',
			array( $this, 'language_select' ),
			'monk-settings',
			'monk-general-options'
		);
		register_setting( 'language', 'language-select' );
		
		?>		
		
		<div class="wrap">
			<h2>Monk</h2>
			<form method="POST" action="options.php">
			<?php
				settings_fields( 'monk_general_options' );
				do_settings_sections( 'monk-settings' );
				submit_button();
			?>
			</form>
		</div>
		
		<?php
		
	}

	/**
	 * Function that handles the main language selector, used in monk_settings_fields()
	 *
	 * @since    1.0.0
	*/
	public function language_select() {
	   ?>
	        <select name="language-select">
	          <option value="portuguese" <?php selected( get_option( 'language-select' ), "portuguese" ); ?>>Portuguese</option>
	          <option value="english" <?php selected( get_option( 'language-select' ), "english" ); ?>>English</option>
	          <option value="spanish" <?php selected( get_option( 'language-select' ), "spanish" ); ?>>Spanish</option>
	          <option value="french" <?php selected( get_option( 'language-select' ), "french" ); ?>>French</option>
	        </select>
	   <?php
	}
}
