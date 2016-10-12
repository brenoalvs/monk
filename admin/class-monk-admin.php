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
			__( 'Monk Settings', 'monk' ),
			'Monk',
			'manage_options',
			'monk',
			array( $this, 'monk_settings_render' ),
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
			'monk_general_settings',
			__( 'General Settings', 'monk' ),
			array( $this, 'monk_general_settings_render' ),
			'monk_settings'
		);

		register_setting( 'monk_settings', 'monk_default_language' );
		add_settings_field(
			'monk_default_language',
			__( 'Default site language', 'monk' ),
			array( $this, 'monk_default_language_render' ),
			'monk_settings',
			'monk_general_settings'
		);

		register_setting( 'monk_settings', 'monk_active_languages' );
		add_settings_field(
			'monk_active_languages',
			__( 'Add new translation', 'monk' ),
			array( $this, 'monk_active_languages_render' ),
			'monk_settings',
			'monk_general_settings'
		);
	}

	/**
	 * This is the callback for the monk_general_settings section
	 *
	 * Prints a description in the section
	 *
	 * @since    1.0.0
	 */
	public function monk_general_settings_render() {
		_e( 'Adjust the way you want', 'monk' );
	}

	/**
	 * Function to render the select field, callback for the monk_default_language element
	 *
	 * @since    1.0.0
	 */
	public function monk_default_language_render() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/admin-monk-default-language-render.php';
	}

	/**
	 * Function to render the checkbox field, callback for the monk_active_languages element
	 *
	 * @since    1.0.0
	 */
	public function monk_active_languages_render() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/admin-monk-active-languages-render.php';
	}

	/**
	 * Function to render the admin settings page for the plugin
	 *
	 * @since    1.0.0
	 */
	public function monk_settings_render() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/admin-monk-settings-render.php';
	}

	/**
	 * Function to create the main language meta box for posts
	 *
	 * @since    1.0.0
	*/
	public function monk_post_meta_box() {
		add_meta_box(
			'monk_post_meta_box_field',
			__( 'Language', 'monk' ),
			array( $this, 'monk_post_meta_box_field_render' ),
			'',
			'side',
			'high',
			''
		);
	}

	/**
	 * Function that makes the view for the post monk meta box
	 *
	 * @since    1.0.0
	*/
	public function monk_post_meta_box_field_render( $post ) {
		$site_default_language = get_option( 'monk_default_language' );
		$active_languages      = get_option( 'monk_active_languages' );
		$post_default_language = get_post_meta( $post->ID, '_monk_post_default_language', true );
		$post_translations     = get_post_meta( $post->ID, '_monk_post_add_translation', true ) ? get_post_meta( $post->ID, '_monk_post_add_translation', true ) : array();
		
		wp_nonce_field( basename( __FILE__ ), 'monk_post_meta_box_nonce' );
		global $current_screen;

		$available_languages   = array(
			'da_DK' => __( 'Danish', 'monk' ),
			'en_US' => __( 'English', 'monk' ),
			'fr_FR' => __( 'French', 'monk' ),
			'de_DE' => __( 'German', 'monk' ),
			'it_IT' => __( 'Italian', 'monk' ),
			'ja'    => __( 'Japanese', 'monk' ),
			'pt_BR' => __( 'Portuguese (Brazil)', 'monk' ),
			'ru_RU' => __( 'Russian', 'monk' ),
			'es_ES' => __( 'Spanish', 'monk' ),
		);

		if ( $post_default_language == '' ) {
			$selected = $available_languages[$site_default_language];
		} else {
			$selected = $post_default_language;
		}
		require_once plugin_dir_path( __FILE__ ) . '/partials/admin-monk-post-meta-box-field-render.php';
	}

	/**
	 * Function to save data from the monk post meta box
	 *
	 * @since    1.0.0
	*/
	public function monk_save_post_meta_box( $post_id ) {
		if ( ! isset( $_POST['monk_post_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['monk_post_meta_box_nonce'], basename( __FILE__ ) ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_REQUEST['monk_post_default_language'] ) ) {
			update_post_meta(
				$post_id,
				'_monk_post_default_language',
				sanitize_text_field( $_POST['monk_post_default_language'] )
			);
		}
		
		if ( isset( $_REQUEST['monk_post_add_translation'] ) ) {
			$post_translations     = get_post_meta( $post->ID, '_monk_post_add_translation', true ) ? get_post_meta( $post->ID, '_monk_post_add_translation', true ) : array();
			$added_translation     = (string) $_POST['monk_post_add_translation'];
			array_push( $post_translations, $added_translation );
			$post_translations = array_map( 'sanitize_text_field', $post_translations );
			update_post_meta(
				$post_id,
				'_monk_post_add_translation',
				$post_translations
			);
		}
	}
}
