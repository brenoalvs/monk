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
			'Monk',
			'Monk Settings',
			'read',
			'monk-settings',
			array( $this, 'monk_settings_fields' ),
			'dashicons-translation',
			3
		);
	}

	/**
	 * Function to handle the options in the settings page of the plugin
	 *
	 * @since    1.0.0
	*/
	public function monk_settings_fields() {
		if ( !current_user_can( 'read' ) ) {
			wp_die( 'Not Allowed' );
		}

		$monk_option_name    = 'language_selector';
		$hidden_field_name   = 'submit_language';
		$data_option_field   = 'language_selector';
		$language_value      = get_option( $monk_option_name );

		if ( isset( $POST[ $hidden_field_name ] ) && $POST[ $hidden_field_name ] == 'Y' ) {
			$language_value  = $POST[ $data_option_field ];
			update_option( $hidden_field_name, $language_value );
		}
?>

	<form name="default-language" method="POST" action="">
		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
		<h3><?php _e( 'Select the dafault language' ); ?></h3>
		<select name="<?php echo $data_option_field; ?>">
			<option value="portuguese" <?php selected( $language_value, 'portuguese' ); ?>>Portuguese</option>
			<option value="english"    <?php selected( $language_value, 'english'    ); ?>>English</option>
			<option value="spanish"    <?php selected( $language_value, 'spanish'    ); ?>>Spanish</option>
			<option value="french"     <?php selected( $language_value, 'french'     ); ?>>French</option>
		</select>
	</form>

	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Use language') ?>" />
	</p>

<?php

	}
}
