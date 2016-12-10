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
	 * @param    string $monk       The name of this plugin.
	 * @param    string $version    The version of this plugin.
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
	 * Function to create a section for the Monk General Options in the administration menu
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
		?>
		<p>Here you can configure your language preferences.<br />
		Select a default language for your site and check the languages you will translate.</p>
		<?php
	}

	/**
	 * Function to render the select field, callback for the monk_default_language element
	 *
	 * @since    1.0.0
	 */
	public function monk_default_language_render() {
		$default_language = get_option( 'monk_default_language', false );
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
	 * Function to make the view for the post monk meta box
	 *
	 * @param    object $post Post object.
	 *
	 * @since    1.0.0
	 */
	public function monk_post_meta_box_field_render( $post ) {
		global $current_screen;
		global $monk_languages;
		$site_default_language = get_option( 'monk_default_language', false );
		$active_languages      = get_option( 'monk_active_languages', false );
		$monk_id               = get_post_meta( $post->ID, '_monk_post_translations_id', true );
		$post_default_language = get_post_meta( $post->ID, '_monk_post_language', true );
		$monk_translation_url  = admin_url( 'post-new.php' );

		wp_nonce_field( basename( __FILE__ ), 'monk_post_meta_box_nonce' );

		if ( '' === $post_default_language ) {
			$selected = $monk_languages[ $site_default_language ]['name'];
		} else {
			$selected = $post_default_language;
		}

		require_once plugin_dir_path( __FILE__ ) . '/partials/admin-monk-post-meta-box-field-render.php';
	}

	/**
	 * Function to save data from the monk post meta box
	 *
	 * @param   string $post_id ID of the post.
	 *
	 * @since    1.0.0
	 */
	public function monk_save_post_meta_box( $post_id ) {
		if ( ! isset( $_REQUEST['monk_post_meta_box_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['monk_post_meta_box_nonce'] ) ), basename( __FILE__ ) ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$current_post_language = get_post_meta( $post_id, '_monk_post_language', true );
		if ( isset( $_REQUEST['monk_post_language'] ) ) {
			$post_language = sanitize_text_field( wp_unslash( $_REQUEST['monk_post_language'] ) );
			update_post_meta(
				$post_id,
				'_monk_post_language',
				$post_language
			);
		}

		if ( isset( $_REQUEST['monk_id'] ) ) {
			$monk_id = sanitize_text_field( wp_unslash( $_REQUEST['monk_id'] ) );
			update_post_meta(
				$post_id,
				'_monk_post_translations_id',
				$monk_id
			);
		}

		/**
		 * Now we get the saved $monk_id and $post_language metadata
		 * and attach this post to its corresponding Monk_Post_Translation object
		 */

		if ( isset( $post_language ) && isset( $monk_id ) && ! wp_is_post_revision( $post_id ) ) {
			$option_name = 'monk_post_translations_' . $monk_id;

			if ( get_option( $option_name ) !== false ) {
				$post_translations = get_option( $option_name );

				if ( $post_language !== $current_post_language ) {
					unset( $post_translations[ $current_post_language ] );
				}

				$post_translations[ $post_language ] = $post_id;
				update_option( $option_name, $post_translations );
			} else {
				add_option( $option_name, array( $post_language => $post_id ), null, 'no' );
			}
		}
	}

	/**
	 * Function that erases the data stored by the plugin when post is deleted permanently.
	 *
	 * @param   string $post_id ID of the post, page or post_type to be deleted.
	 *
	 * @since    1.0.0
	 */
	public function monk_delete_post_data( $post_id ) {
		$monk_id           = get_post_meta( $post_id, '_monk_post_translations_id', true );
		$post_lang         = get_post_meta( $post_id, '_monk_post_language', true );
		$post_translations = get_option( 'monk_post_translations_' . $monk_id, false );

		if ( isset( $post_translations ) && $post_translations ) {
			unset( $post_translations[ $post_lang ] );
			if ( empty( $post_translations ) ) {
				delete_option( 'monk_post_translations_' . $monk_id );
			} else {
				update_option( 'monk_post_translations_' . $monk_id, $post_translations );
			}
		} else {
			delete_option( 'monk_post_translations_' . $monk_id );
		}
	}

	/**
	 * Function to filter the query inside the category meta box using the languages
	 *
	 * @param   object $term_query instance of WP_Term_Query class.
	 *
	 * @since    1.0.0
	 */
	public function monk_category_language_filter( $term_query ) {
		if ( is_admin() && ! is_customize_preview() ) {
			$screen = get_current_screen();

			if ( 'edit' === $screen->parent_base ) {
				$term_args  = '';
				$post_id    = get_the_id();
				$meta_lang  = sanitize_title( get_post_meta( $post_id, '_monk_post_language', true ) );
				$term_args  = array( 'meta_key' => '_monk_term_language' );

				if ( isset( $_GET['lang'] ) ) {
					$query_lang = sanitize_title( wp_unslash( $_GET['lang'] ) );
				}

				if ( isset( $query_lang ) ) {
					$term_args['meta_value'] = $query_lang;
				} elseif ( isset( $meta_lang ) ) {
					$term_args['meta_value'] = $meta_lang;
				}
				return $term_query->parse_query( $term_args );
			}
		}
	}

	/**
	 * Add components on Appearance->Customize.
	 *
	 * @param object $wp_customize Customize object.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function monk_language_customizer( $wp_customize ) {

		$wp_customize->add_section( 'monk_language_switcher' , array(
			'title'    => __( 'Language Switcher', 'monk' ),
			'priority' => 1,
		));

		/**
		 * Add setting and control related to Language Switcher Background.
		 */
		$wp_customize->add_setting( 'monk_background_color', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'default'           => '#fff',
			'sanitize_callback' => 'sanitize_hex_color',
		));

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'monk_background_color', array(
			'label'   => __( 'Background', 'monk' ),
			'section' => 'monk_language_switcher',
		)));

		/**
		 * Add setting and control related to Language Switcher Text Color.
		 */
		$wp_customize->add_setting( 'monk_text_color', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'default'           => '#000',
			'sanitize_callback' => 'sanitize_hex_color',
		));

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'monk_text_color', array(
			'label'   => __( 'Text', 'monk' ),
			'section' => 'monk_language_switcher',
		)));

		/**
		 * Add setting and control related to Language List Background Hover.
		 */
		$wp_customize->add_setting( 'monk_language_background_hover', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'default'           => '#000',
			'sanitize_callback' => 'sanitize_hex_color',
		));

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'monk_language_background_hover', array(
			'label'   => __( 'Background Hover', 'monk' ),
			'section' => 'monk_language_switcher',
		)));

		/**
		 * Add setting and control related to Language Text Hover.
		 */
		$wp_customize->add_setting( 'monk_language_hover_color', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'default'           => '#fff',
			'sanitize_callback' => 'sanitize_hex_color',
		));

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'monk_language_hover_color', array(
			'label'   => __( 'Text Hover', 'monk' ),
			'section' => 'monk_language_switcher',
		)));
	}

	/**
	 * Include styles related to Customize options.
	 */
	public function monk_customize_css() {
		?>
		<style type="text/css">
			div#monk-language-switcher div.monk-current-lang { background-color: <?php echo esc_attr( get_option( 'monk_background_color', '#fff' ) ); ?>; border-color: <?php echo esc_attr( get_option( 'monk_text_color', '#000' ) ); ?>; }
			div#monk-language-switcher div.monk-current-lang:hover { background-color: <?php echo esc_attr( get_option( 'monk_language_background_hover', '#000' ) ); ?>; }
			div#monk-language-switcher div.monk-current-lang span.monk-current-lang-name { color: <?php echo esc_attr( get_option( 'monk_text_color', '#000' ) ); ?>; }
			div#monk-language-switcher div.monk-current-lang:hover span.monk-current-lang-name { color: <?php echo esc_attr( get_option( 'monk_language_hover_color', '#fff' ) ); ?>; }
			div#monk-language-switcher ul.monk-language-dropdown { border-color: <?php echo esc_attr( get_option( 'monk_text_color', '#000' ) ); ?>; }
			div#monk-language-switcher ul.monk-language-dropdown li.monk-lang { background-color: <?php echo esc_attr( get_option( 'monk_background_color', '#fff' ) ); ?>; }
			div#monk-language-switcher ul.monk-language-dropdown li.monk-lang:hover { background-color: <?php echo esc_attr( get_option( 'monk_language_background_hover', '#000' ) ); ?>; }
			div#monk-language-switcher ul.monk-language-dropdown li.monk-lang a.monk-language-link { color: <?php echo esc_attr( get_option( 'monk_text_color', '#000' ) ); ?>; }
			div#monk-language-switcher ul.monk-language-dropdown li.monk-lang:hover a.monk-language-link { color: <?php echo esc_attr( get_option( 'monk_language_hover_color', '#fff' ) ); ?>; }
			div#monk-language-switcher div.monk-current-lang span.monk-dropdown-arrow { border-top-color: <?php echo esc_attr( get_option( 'monk_text_color', '#000' ) ); ?>; }
			div#monk-language-switcher div.monk-current-lang:hover span.monk-dropdown-arrow { border-top-color: <?php echo esc_attr( get_option( 'monk_language_hover_color', '#fff' ) ); ?>; }
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
	 * Add parameters to filter by meta_key.
	 *
	 * @param object $query Object Query.
	 */
	public function monk_admin_languages_filter( $query ) {
		if ( is_admin() && $query->is_main_query() ) {
			$default_language = get_option( 'monk_default_language' );
			$not_filter       = false;

			if ( isset( $_GET['monk_language_filter'] ) ) {
				$not_filter           = true;
				$monk_language_filter = sanitize_text_field( wp_unslash( $_GET['monk_language_filter'] ) );
			}

			$has_filter = $not_filter && ! empty( $_GET['monk_language_filter'] ) ? true : false;
			$is_default = $has_filter && 0 === strcmp( $monk_language_filter, $default_language ) ? true : false;

			if ( $query->is_search() && $has_filter && ! $is_default ) {
				$monk_language = $monk_language_filter;

				$query->set( 'meta_key', '_monk_post_language' );
				$query->set( 'meta_value', $monk_language );
			} elseif ( ! $not_filter || $is_default ) {
				$meta_query_args = array(
					'relation' => 'OR', // Optional, defaults to "AND".
					array(
						'key'     => '_monk_post_language',
						'value'   => $default_language,
					),
					array(
						'key'     => '_monk_post_language',
						'compare' => 'NOT EXISTS',
					)
				);

				$query->set( 'meta_query', $meta_query_args );
			}
		}
	}

	/**
	 * Include styles related to Customize options
	 *
	 * @param array $title Title of the column.
	 */
	public function monk_language_column_head( $title ) {
		$title['languages'] = __( 'Languages', 'monk' );
		return $title;
	}

	/**
	 * Include styles related to Customize options.
	 *
	 * @param string $column_name Title of the column.
	 * @param string $post_id    Post id.
	 */
	public function monk_language_column_content( $column_name, $post_id ) {
		if ( 'languages' === $column_name ) {
			global $monk_languages;

			$monk_language        = get_post_meta( $post_id, '_monk_post_language', true );
			$monk_translations_id = get_post_meta( $post_id, '_monk_post_translations_id', true );
			$monk_translations    = get_option( 'monk_post_translations_' . $monk_translations_id, false );
			$default_language     = get_option( 'monk_default_language', false );
			$base_url             = admin_url( 'post.php?action=edit' );
			$active_languages     = get_option( 'monk_active_languages', false );
			$post_type            = get_query_var( 'post_type' );
			$post_type            = isset( $post_type ) && ! empty( $post_type ) ? sanitize_text_field( wp_unslash( $post_type ) ) : false;
			$available_languages  = false;
			$post_url             = add_query_arg( array(
				'post' => $post_id,
			), $base_url );

			if ( 'post' !== $post_type ) {
				$new_post_url = add_query_arg( array(
					'post_type' => $post_type,
					'monk_id'   => $monk_translations_id,
				), admin_url( 'post-new.php' ) );
			} else {
				$new_post_url = add_query_arg( array(
					'monk_id'   => $monk_translations_id,
				), admin_url( 'post-new.php' ) );
			}

			foreach ( $active_languages as $language ) {
				if ( $monk_translations && ! array_key_exists( $language, $monk_translations ) ) {
					$available_languages = true;
				}
			}

			require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/monk-language-column.php';
		}
	}

	/**
	 * Add select term language
	 */
	public function monk_custom_taxonomy_field() {
		global $monk_languages;
		$languages        = get_option( 'monk_active_languages', false );
		$taxonomies       = get_taxonomies();
		$default_language = get_option( 'monk_default_language', false );

		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/admin-monk-language-term.php';
	}

	/**
	 * Save term language
	 *
	 * @param int $term_id  Id of the term.
	 */
	public function monk_create_term_meta( $term_id ) {
		if ( isset( $_REQUEST['monk-language'] ) && ! empty( $_REQUEST['monk-language'] ) ) {
			$language = sanitize_text_field( wp_unslash( $_REQUEST['monk-language'] ) );
			add_term_meta( $term_id, '_monk_term_language', $language, true );

			if ( isset( $_REQUEST['monk_id'] ) ) {
				$monk_term_translations_id = sanitize_text_field( wp_unslash( $_REQUEST['monk_id'] ) );
				add_term_meta( $term_id, '_monk_term_translations_id', $monk_term_translations_id, true );

				if ( get_option( 'monk_term_translations_' . $monk_term_translations_id ) !== false ) {
					$current_term_translations = get_option( 'monk_term_translations_' . $monk_term_translations_id );
					$current_term_translations[ $language ] = $term_id;
					update_option( 'monk_term_translations_' . $monk_term_translations_id, $current_term_translations );
				}
			} else {
				$option_value = array(
					$language => $term_id,
				);

				add_option( 'monk_term_translations_' . $term_id, $option_value, null, 'no' );
				add_term_meta( $term_id, '_monk_term_translations_id', $term_id, true );
			}
		}
	}

	/**
	 * Update term language
	 *
	 * @param int $term_id Id of the term.
	 */
	public function monk_update_term_meta( $term_id ) {
		if ( isset( $_REQUEST['monk-language'] ) && ! empty( $_REQUEST['monk-language'] ) ) {
			$current_language          = get_term_meta( $term_id, '_monk_term_language', true );
			$new_language              = sanitize_text_field( wp_unslash( $_REQUEST['monk-language'] ) );
			$monk_term_translations_id = get_term_meta( $term_id, '_monk_term_translations_id', true );

			update_term_meta( $term_id, '_monk_term_language', $new_language );
			$monk_term_translations = get_option( 'monk_term_translations_' . $monk_term_translations_id, false );

			if ( ! empty( $monk_term_translations ) ) {
				unset( $monk_term_translations[ $current_language ] );
				$monk_term_translations[ $new_language ] = $term_id;
				update_option( 'monk_term_translations_' . $monk_term_translations_id, $monk_term_translations );
			}
		}
	}

	/**
	 * Function that erases the data stored by the plugin when term is deleted.
	 *
	 * @param int $term_id ID of the Term object.
	 *
	 * @since 1.0.0
	 */
	public function monk_delete_term_meta( $term_id ) {
		$monk_term_translations_id = get_term_meta( $term_id, '_monk_term_translations_id', true );
		$term_language             = get_term_meta( $term_id, '_monk_term_language', true );
		$option_name               = 'monk_term_translations_' . $monk_term_translations_id;
		$monk_term_translations    = get_option( $option_name, false );

		if ( isset( $monk_term_translations ) && $monk_term_translations ) {
			unset( $monk_term_translations[ $term_language ] );
			if ( empty( $monk_term_translations ) ) {
				delete_option( $option_name );
			} else {
				update_option( $option_name, $monk_term_translations );
			}
		} else {
			delete_option( $option_name );
		}
	}

	/**
	 * Add select term language inside edit page
	 *
	 * @param Object $term Object term.
	 */
	public function monk_edit_custom_taxonomy_field( $term ) {
		$monk_language = get_term_meta( $term->term_id, '_monk_term_language', true );
		global $monk_languages;
		$languages = get_option( 'monk_active_languages', false );
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/admin-monk-language-update-term.php';
	}

	/**
	 * Add column content
	 *
	 * @param string $content    The content.
	 * @param string $column_name Title of the column.
	 * @param int    $term_id    Id of the term.
	 */
	public function monk_taxonomy_language_column_content( $content, $column_name, $term_id ) {
		if ( 'languages' === $column_name ) :
			global $monk_languages;
			$monk_language             = get_term_meta( $term_id, '_monk_term_language', true );
			$languages                 = get_option( 'monk_active_languages', false );
			$taxonomies                = get_taxonomies();
			$monk_term_translations_id = get_term_meta( $term_id, '_monk_term_translations_id', true );
			$monk_term_translations    = get_option( 'monk_term_translations_' . $monk_term_translations_id, false );
			$default_language          = get_option( 'monk_default_language', false );
			$available_languages       = false;

			foreach ( $languages as $language ) {
				if ( $monk_term_translations && ! array_key_exists( $language, $monk_term_translations ) ) {
					$available_languages = true;
				}
			}

			foreach ( $taxonomies as $taxonomy ) {
				if ( isset( $_GET['taxonomy'] ) ) {
					if ( $_GET['taxonomy'] === $taxonomy ) {
						$base_url     = admin_url( 'term.php?taxonomy=' . $taxonomy );
						$new_term_url = add_query_arg( array(
								'monk_term_id' => $monk_term_translations_id,
						), admin_url( 'edit-tags.php?taxonomy=' . $taxonomy ) );
					}
				}
			}

			require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/admin-taxonomy-monk-language-column.php';
		endif;
	}

	/**
	 * Add term translation meta field.
	 *
	 * @param object $term Term object.
	 */
	public function monk_term_translation_meta_field( $term ) {
		global $monk_languages;
		$monk_language             = get_term_meta( $term->term_id, '_monk_term_language', true );
		$languages                 = get_option( 'monk_active_languages', false );
		$taxonomies                = get_taxonomies();
		$monk_term_translations_id = get_term_meta( $term->term_id, '_monk_term_translations_id', true );
		$monk_term_translations    = get_option( 'monk_term_translations_' . $monk_term_translations_id, false );
		$available_languages       = false;

		foreach ( $taxonomies as $taxonomy ) {
			if ( isset( $_GET['taxonomy'] ) ) {
				$tax = sanitize_text_field( wp_unslash( $_GET['taxonomy'] ) );
				if ( $tax === $taxonomy ) {
					$base_url = admin_url( 'edit-tags.php?taxonomy=' . $taxonomy );
					$base_url_translation = admin_url( 'term.php?taxonomy=' . $taxonomy );
				}
			}
		}

		foreach ( $languages as $language ) {
			if ( ! array_key_exists( $language, $monk_term_translations ) ) {
				$available_languages = true;
			}
		}

		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/admin-monk-term-translation.php';
	}

	/**
	 * Function to display a notice on plugin activation
	 *
	 * This function gets the user to the configuration page
	 *
	 * @since   1.0.0
	 */
	public function monk_activation_notice() {
		$monk_settings_notice = get_option( 'monk_settings_notice', false );
		
		if ( $monk_settings_notice ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/admin-monk-notice-render.php';
		}
	}
}
