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

		/**
		 * This function does enqueue flag icon .css files in admin side.
		 */
		wp_enqueue_style( 'monk-flags', plugin_dir_url( dirname( __FILE__ ) ) . 'lib/css/flag-icon.css', array(), $this->version, 'all' );
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
	 * @param    $post object
	 *
	 * @since    1.0.0
	*/
	public function monk_post_meta_box_field_render( $post ) {
		$site_default_language = get_option( 'monk_default_language' );
		$active_languages      = get_option( 'monk_active_languages' );
		$monk_meta_id          = get_post_meta( $post->ID, '_monk_meta_id', true );
		$post_default_language = get_post_meta( $post->ID, '_monk_post_language', true );
		$post_translation      = get_post_meta( $post->ID, '_monk_post_translation_id', true ) ? get_post_meta( $post->ID, '_monk_post_translation_id', true ) : array();
		$translated_posts      = get_post_meta( $post->ID, '_monk_translated_posts' );
		
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

		if ( function_exists( 'admin_url' ) ) {
			$monk_translation_url = admin_url() . 'post-new.php';
		} else {
			$monk_translation_url = get_option( 'siteurl' ) . '/wp-admin/' . 'post-new.php';
		}

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
	 * @param    $post_id
	 *
	 * @since    1.0.0
	*/
	public function monk_save_post_meta_box( $post_id ) {
		if ( ! isset( $_REQUEST['monk_post_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['monk_post_meta_box_nonce'], basename( __FILE__ ) ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_REQUEST['monk_post_language'] ) ) {
			update_post_meta(
				$post_id,
				'_monk_post_language',
				sanitize_text_field( $_REQUEST['monk_post_language'] )
			);
		}

		/**
		 * Here the correlation between posts is handled
		 *
		 * This section creates a post metadata to save the id
		 * of the 'parent' post, witch is the first to be created and
		 * then translated. The translation posts will have that metadata
		 * being the same as the first, the 'parent'
		*/

		if ( isset( $_REQUEST['monk_id'] ) && isset( $_REQUEST['monk_post_language'] ) ) {
			$option_name = 'monk_post_translations_' . $_REQUEST['monk_id'];
			$post_lang   = $_REQUEST['monk_post_language'];
			if ( get_option( $option_name ) !== false ) {
				$current_post_status = get_option( $option_name );
				if ( ! wp_is_post_revision( $post_id ) ) {
					$current_post_status[$post_id] = $post_lang;
					update_option( $option_name, $current_post_status );
				}
			} else {
				if ( ! wp_is_post_revision( $post_id ) ) {
					add_option( $option_name, array( $post_id => $post_lang ), null, 'no' );
				}
			}
			update_post_meta(
				$post_id,
				'_monk_meta_id',
				sanitize_text_field( $_REQUEST['monk_id'] )
			);
		}
	}

	/**
	 * Add components on Appearance->Customize
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function monk_language_customizer( $wp_customize ) {
		
		$wp_customize->add_section( 'monk_selector' , array(
			'title'    => __( 'Monk Selector', 'monk' ),
			'priority' => 4,
		));

		/**
		 * Add setting and control related to Language Background.
		 */
		$wp_customize->add_setting( 'monk_selector_color', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'default'           => '#ddd',
			'sanitize_callback' => 'sanitize_hex_color',
		));

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'monk_selector_color', array(
			'label'   => __( 'Language Background', 'monk' ),
			'section' => 'monk_selector',
		)));

		/**
		 * Add setting and control related to Active Language Background.
		 */		
		$wp_customize->add_setting( 'monk_selector_active_color', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'default'           => '#fff',
			'sanitize_callback' => 'sanitize_hex_color',
		));

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'monk_selector_active_color', array(
			'label'   => __( 'Active Language Background', 'monk' ),
			'section' => 'monk_selector',
		)));
		
		/**
		 * Add setting and control related to Language Text Color.
		 */
		$wp_customize->add_setting( 'monk_lang_color', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'default'           => '#001aab',
			'sanitize_callback' => 'sanitize_hex_color',
		));

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'monk_lang_color', array(
			'label'   => __( 'Language Text Color', 'monk' ),
			'section' => 'monk_selector',
		)));

		/**
		 * Add setting and control related to Active Language Text Color.
		 */
		$wp_customize->add_setting( 'monk_lang_active_color', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'default'           => '#000',
			'sanitize_callback' => 'sanitize_hex_color',
		));

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'monk_lang_active_color', array(
			'label'   => __( 'Active Language Text Color', 'monk' ),
			'section' => 'monk_selector',
		)));
	}

	/**
	 * Include styles related to Customize options
	 */
	public function monk_customize_css() {
		?>
		<style type="text/css">
			#monk-selector { border-color: <?php echo esc_attr( get_option( 'monk_selector_color' ) ); ?>; }
			.monk-active-lang { background-color: <?php echo esc_attr( get_option( 'monk_selector_active_color' ) ); ?>; }
			.monk-active-lang-name { color: <?php echo esc_attr( get_option( 'monk_lang_active_color' ) ); ?>; }
			#monk-selector .monk-lang { background-color: <?php echo esc_attr( get_option( 'monk_selector_color' ) ); ?>; }
			.monk-selector-link { color: <?php echo esc_attr( get_option( 'monk_lang_color' ) ); ?>; }
			.monk-selector-arrow { color: <?php esc_attr_e( get_option( 'monk_selector_color' ) ); ?>; }
		</style>
		<?php
	}

	/**
	 * Add select filter
	 */
	public function monk_admin_languages_selector() {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/monk-language-filter.php';
	}

	/**
	 * Add parameters to filter by meta_key
	 *
	 * @param string $query 
	 */
	public function monk_admin_languages_filter( $query ) {
		if ( is_admin() && $query->is_main_query() ) {   
			if ( isset( $_GET['monk_language_filter'] ) && ! empty( $_GET['monk_language_filter'] ) && strcmp( $_GET['monk_language_filter'], 'en_US' ) != 0 && $query->is_search() ) {
				$language = $_GET['monk_language_filter'];

				$query->set( 'meta_key', '_monk_languages' );
				$query->set( 'meta_value', $language );
			} elseif ( ! isset( $_GET['monk_language_filter'] ) || 0 === strcmp( $_GET['monk_language_filter'], 'en_US' ) ) {
				$language = get_option( 'monk_default_language' );
				
				$meta_query_args = array(
					'relation' => 'OR', // Optional, defaults to "AND"
					array(
						'key'     => '_monk_languages',
						'value'   => $language,
					),
					array(
						'key'     => '_monk_languages',
						'compare' => 'NOT EXISTS'
					)
				);
				$query->set( 'meta_query', $meta_query_args );
			}
		}    
	}

	/**
	 * Include styles related to Customize options
	 *
	 * @param array $title Title of the column
	 */
	public function monk_language_column_head( $title ) {
		$title['languages'] = __( 'Languages', 'monk' );
		return $title;
	}

	/**
	 * Include styles related to Customize options
	 *
	 * @param string $colum_name Title of the column
	 * @param string $post_ID    Post id
	 */
	public function monk_language_column_content( $column_name, $post_ID ) {
		if ( 'languages' === $column_name ) {
			require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/monk-language-column.php';
		}
	}
}
