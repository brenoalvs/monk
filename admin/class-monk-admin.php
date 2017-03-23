<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Monk
 * @subpackage Monk/Admin
 */
class Monk_Admin {

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
	 * @param    string $monk       The name of this plugin.
	 * @param    string $version    The version of this plugin.
	 * @return  void
	 */
	public function __construct( $monk, $version ) {

		$this->monk = $monk;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.1.0
	 * @return  void
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->monk, plugin_dir_url( __FILE__ ) . 'css/monk-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'monk-widgets', plugin_dir_url( __FILE__ ) . 'css/monk-widgets.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'monk-flags', plugin_dir_url( __FILE__ ) . 'css/monk-flags.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1.0
	 * @return  void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->monk, plugin_dir_url( __FILE__ ) . 'js/monk-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->monk, 'monk', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		));
	}

	/**
	 * Handle redirects to setup page after install.
	 *
	 * @since    0.1.0
	 * @return  void
	 */
	public function monk_activation_redirect() {
		global $pagenow;
		if ( 'plugins.php' === $pagenow ) {
			$monk_settings_notice = get_option( 'monk_settings_notice', false );
			$activate_multi       = isset( $_GET['activate-multi'] ) ? true : false;

			if ( get_transient( '_monk_redirect' ) && ! $activate_multi && $monk_settings_notice ) {
				wp_safe_redirect( admin_url( 'admin.php?page=monk' ) );
				exit();
			}
			delete_transient( '_monk_redirect' );
		}
	}

	/**
	 * Function to register the settings page of the plugin
	 *
	 * @since    0.1.0
	 * @return  void
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
	 * @since    0.1.0
	 * @return  void
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
	 * @since    0.1.0
	 * @return  void
	 */
	public function monk_general_settings_render() {
		?>
		<p><?php esc_html_e( 'Here you can configure your language preferences.', 'monk' ); ?><br />
		<?php esc_html_e( 'Select a default language for your site and check the languages you will translate.', 'monk' ); ?></p>
		<?php
	}

	/**
	 * Function to render the select field, callback for the monk_default_language element
	 *
	 * @since    0.1.0
	 * @return  void
	 */
	public function monk_default_language_render() {
		$default_language = get_option( 'monk_default_language', false );
		require_once plugin_dir_path( __FILE__ ) . '/partials/admin-monk-default-language-render.php';
	}

	/**
	 * Function to render the checkbox field, callback for the monk_active_languages element
	 *
	 * @since    0.1.0
	 * @return  void
	 */
	public function monk_active_languages_render() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/admin-monk-active-languages-render.php';
	}

	/**
	 * Function to render the admin settings page for the plugin
	 *
	 * @since    0.1.0
	 * @return  void
	 */
	public function monk_settings_render() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/admin-monk-settings-render.php';
	}

	/**
	 * Function to create the main language meta box for posts
	 *
	 * @since    0.1.0
	 * @return  void
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
	 * @since    0.1.0
	 * @return  void
	 */
	public function monk_post_meta_box_field_render( $post ) {
		global $current_screen;
		global $monk_languages;
		$monk_id                = get_post_meta( $post->ID, '_monk_post_translations_id', true );
		$post_default_language  = get_post_meta( $post->ID, '_monk_post_language', true );
		$site_default_language  = get_option( 'monk_default_language', false );
		$active_languages       = get_option( 'monk_active_languages', false );

		if ( empty( $monk_id ) ) {
			if ( isset( $_GET['monk_id'] ) ) {
				$monk_id = sanitize_text_field( wp_unslash( $_GET['monk_id'] ) );
			} else {
				$monk_id = $post->ID;
			}
		}

		if ( isset( $_GET['lang'] ) ) {
			$lang    = sanitize_text_field( wp_unslash( $_GET['lang'] ) );
		} else {
			$lang    = $site_default_language;
		}

		$monk_translations      = get_option( 'monk_post_translations_' . $monk_id, false );
		$monk_translation_url   = admin_url( 'post-new.php' );
		$is_available_languages = false;

		foreach ( $active_languages as $language ) {
			if ( ! array_key_exists( $language, (array) $monk_translations ) ) {
				$is_available_languages = true;
			}
		}

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
	 * @since    0.1.0
	 * @return  void
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

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		global $monk_languages;

		$active_languages  = get_option( 'monk_active_languages' );
		$current_language  = get_post_meta( $post_id, '_monk_post_language', true );

		if ( ! $current_language ) {
			$current_language = sanitize_text_field( wp_unslash( $_REQUEST['monk_post_language'] ) );
		}

		$post_translations = array( $current_language => $post_id );
		$language          = '';

		if ( isset( $_REQUEST['monk_post_language'] ) && ! empty( $_REQUEST['monk_post_language'] ) ) {
			$language = sanitize_text_field( wp_unslash( $_REQUEST['monk_post_language'] ) );

			if ( in_array( $language , $active_languages ) ) {
				update_post_meta( $post_id, '_monk_post_language', $language );
			}
		}

		if ( isset( $_REQUEST['monk_id'] ) ) {
			$monk_id = sanitize_text_field( wp_unslash( $_REQUEST['monk_id'] ) );
		} else {
			$monk_id = get_post_meta( $post_id, '_monk_post_translations_id', true );
		}

		$option_name       = 'monk_post_translations_' . $monk_id;
		$post_translations = get_option( $option_name );

		if ( ! empty( $post_translations ) ) {
			if ( array_key_exists( $current_language, $post_translations ) ) {
				if (  $post_id === $post_translations[ $current_language ] && $language ) {
					unset( $post_translations[ $current_language ] );
					$post_translations[ $language ] = $post_id;
				}
			} else {
				$post_translations[ $current_language ] = $post_id;
			}
		} else {
			$monk_id           = $post_id;
			$post_translations = array( $current_language => $post_id );
		}

		foreach ( $post_translations as $lang_code => $id ) {
			if ( ! array_key_exists( $lang_code, $monk_languages ) ) {
				unset( $post_translations[ $lang_code ] );
			}
		}

		update_post_meta( $post_id, '_monk_post_translations_id', $monk_id );
		update_option( 'monk_post_translations_' . $monk_id, $post_translations );
	}

	/**
	 * Function that erases the data stored by the plugin when post is deleted permanently.
	 *
	 * @param   string $post_id ID of the post, page or post_type to be deleted.
	 *
	 * @since    0.1.0
	 * @return  void
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
	 * Add parameters to filter by meta_key.
	 *
	 * @since  0.1.0
	 * @param object $query Object Query.
	 * @return  void
	 */
	public function monk_admin_posts_filter( $query ) {
		global $mode;
		if ( ! is_admin() || is_customize_preview() || ( 'attachment' === $query->get( 'post_type' ) && 'list' !== $mode ) || 'nav_menu_item' === $query->get( 'post_type' ) ) {
			return;
		}

		$default_language = get_option( 'monk_default_language', false );
		$active_languages = get_option( 'monk_active_languages', false );
		$filter           = filter_input( INPUT_GET , 'monk_language_filter' );

		if ( 'nav-menus' === get_current_screen()->base ) {
			$menu_id  = filter_input( INPUT_GET , 'menu' ) ? filter_input( INPUT_GET , 'menu' ) : get_user_option( 'nav_menu_recently_edited' );
			$language = get_term_meta( $menu_id, '_monk_menu_language', true );
			$language = empty( $language ) ? $default_language : $language;
		} else {
			$language = filter_input( INPUT_GET , 'lang' );
		}

		if ( $query->is_search() ) {
			if ( empty( $filter ) ) {
				return;
			} else {
				$language = $filter;
			}
		}

		if ( $language === $default_language || ! in_array( $language, $active_languages, true ) ) {
			$meta_query = array(
				'relation' => 'OR', // Optional, defaults to "AND".
				array(
					'key'     => '_monk_post_language',
					'value'   => $default_language,
				),
				array(
					'key'     => '_monk_post_language',
					'compare' => 'NOT EXISTS',
				),
			);

			$query->set( 'meta_query', $meta_query );
		} else {
			$query->set( 'meta_key', '_monk_post_language' );
			$query->set( 'meta_value', $language );
		}
	}

	/**
	 * Function to filter the query inside the category meta box using the languages
	 *
	 * @param   array $args       Array of arguments.
	 * @param   array $taxonomies Array of taxonomies.
	 *
	 * @since    0.1.0
	 * @return  array $args.
	 */
	public function monk_admin_terms_filter( $args, $taxonomies ) {
		if ( ! is_admin() ) {
			return $args;
		}

		if ( is_customize_preview() && in_array( 'nav_menu', $taxonomies ) ) {
			$language = get_option( 'monk_default_language', false );

			$meta_query = array(
				'relation' => 'OR', // Optional, defaults to "AND".
				array(
					'key'   => '_monk_menu_language',
					'value' => $language,
				),
				array(
					'key'     => '_monk_menu_language',
					'compare' => 'NOT EXISTS',
				),
			);
			$args['meta_query'] = $meta_query;
			return $args;
		}

		if ( ! is_customize_preview() ) {
			$screen = get_current_screen();

			if ( ( 'edit' === $screen->parent_base && 'post' === $screen->base ) || ( 'nav-menus' === $screen->base ) ) {
				$active_languages = get_option( 'monk_active_languages', array() );
				$default_language = get_option( 'monk_default_language', false );

				if ( 'nav-menus' === $screen->base ) {
					$menu_id  = filter_input( INPUT_GET , 'menu' ) ? filter_input( INPUT_GET , 'menu' ) : get_user_option( 'nav_menu_recently_edited' );
					$language = get_term_meta( $menu_id, '_monk_menu_language', true );
					$language = empty( $language ) ? $default_language : $language;
				} else {
					$post_id       = get_the_id();
					$post_language = sanitize_text_field( get_post_meta( $post_id, '_monk_post_language', true ) );
					$language      = filter_input( INPUT_GET, 'lang' );
					$language      = filter_input( INPUT_GET , 'lang' );
				}

				if ( isset( $language ) && in_array( $language, $active_languages, true ) ) {
					$relation = array(
						'key'   => '_monk_term_language',
						'value' => $language,
					);
				} elseif ( $post_language ) {
					$relation = array(
						'key'   => '_monk_term_language',
						'value' => $post_language,
					);
				}

				if ( isset( $relation ) ) {
					$meta_query = array(
						'relation' => 'OR', // Optional, defaults to "AND".
						$relation,
						array(
							'key'     => '_monk_term_language',
							'compare' => 'NOT EXISTS',
						),
					);

					$args['meta_query'] = $meta_query;
				}
			}
		}

		return $args;
	}

	/**
	 * Add components on Appearance->Customize.
	 *
	 * @param object $wp_customize Customize object.
	 *
	 * @since    0.1.0
	 * @access   public
	 * @return  void
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
	 *
	 * @since  0.1.0
	 * @return  void
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
	 *
	 * @since  0.1.0
	 * @return  void
	 */
	public function monk_admin_languages_selector() {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/monk-language-filter.php';
	}

	/**
	 * Include styles related to Customize options
	 *
	 * @since  0.1.0
	 * @param array $title Title of the column.
	 * @return array $title
	 */
	public function monk_language_column_head( $title ) {
		$title['languages'] = __( 'Languages', 'monk' );
		return $title;
	}

	/**
	 * Include styles related to Customize options.
	 *
	 * @since  0.1.0
	 * @param string $column_name Title of the column.
	 * @param string $post_id    Post id.
	 * @return  void
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

			if ( ! $monk_translations_id ) {
				$monk_translations_id = $post_id;
			}

			if ( 'post' !== $post_type ) {
				$new_url = add_query_arg( array(
					'post_type' => $post_type,
					'monk_id'   => $monk_translations_id,
				), admin_url( 'post-new.php' ) );
			} else {
				$new_url = add_query_arg( array(
					'monk_id'   => $monk_translations_id,
				), admin_url( 'post-new.php' ) );
			}

			foreach ( $active_languages as $language ) {
				if ( ! $monk_language || $monk_translations && ! array_key_exists( $language, $monk_translations ) ) {
					$available_languages = true;
				}
			}

			require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/monk-language-column.php';
		}
	}

	/**
	 * Add select term language
	 *
	 * @since  0.1.0
	 * @return  void
	 */
	public function monk_custom_taxonomy_field() {
		global $monk_languages;
		$languages        = get_option( 'monk_active_languages', false );
		$taxonomies       = get_taxonomies();
		$default_language = get_option( 'monk_default_language', false );

		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/admin-monk-language-term.php';
	}

	/**
	 * Saves the term language on a new term creation
	 *
	 * @since  0.1.0
	 *
	 * @param   int    $term_id Id of the term.
	 * @param   int    $tt_id Term taxonomy ID.
	 * @param   string $taxonomy Taxonomy slug.
	 * @return  void
	 */
	public function monk_create_term_meta( $term_id, $tt_id, $taxonomy ) {

		if ( null !== filter_input( INPUT_POST, 'monk_language' ) && ! empty( filter_input( INPUT_POST, 'monk_language' ) ) ) {

			$active_languages  = get_option( 'monk_active_languages', false );
			$language          = sanitize_text_field( wp_unslash( filter_input( INPUT_POST, 'monk_language' ) ) );
			$term_translations = array( $language => $term_id );
			$is_menu           = 'nav_menu' === $taxonomy ? 'menu' : 'term';

			if ( null !== filter_input( INPUT_GET, 'monk_id' ) ) {
				$monk_id           = sanitize_text_field( wp_unslash( filter_input( INPUT_GET, 'monk_id' ) ) );
				$term_translations = get_option( 'monk_' . $is_menu . '_translations_' . $monk_id, array() );

				if ( in_array( $language , $active_languages, true ) && ( ! array_key_exists( $language , $term_translations ) || empty( $term_translations ) ) ) {
					add_term_meta( $term_id, '_monk_' . $is_menu . '_language', $language, true );

					if ( false !== $term_translations ) {
						add_term_meta( $term_id, '_monk_' . $is_menu . '_translations_id', $monk_id, true );
						$term_translations[ $language ] = $term_id;
						update_option( 'monk_' . $is_menu . '_translations_' . $monk_id, $term_translations );
					} else {
						add_term_meta( $term_id, '_monk_' . $is_menu . '_translations_id', $term_id, true );
						update_option( 'monk_' . $is_menu . '_translations_' . $term_id, $term_translations );
					}
				}
			} else {
				add_term_meta( $term_id, '_monk_' . $is_menu . '_language', $language, true );
				add_term_meta( $term_id, '_monk_' . $is_menu . '_translations_id', $term_id, true );
				update_option( 'monk_' . $is_menu . '_translations_' . $term_id, $term_translations );
			}
		}
	}

	/**
	 * Update term language
	 *
	 * @since  0.1.0
	 * @param int $term_id  Term ID.
	 * @param int $taxonomy Taxonomy slug.
	 * @return  void
	 */
	public function monk_update_term_meta( $term_id, $taxonomy ) {

		$new_language              = sanitize_text_field( wp_unslash( filter_input( INPUT_POST, 'monk_language' ) ) );
		$is_menu                   = 'nav_menu' === $taxonomy ? 'menu' : 'term';
		$current_language          = get_term_meta( $term_id, '_monk_' . $is_menu . '_language', true );
		$monk_term_translations_id = get_term_meta( $term_id, '_monk_' . $is_menu . '_translations_id', true );

		update_term_meta( $term_id, '_monk_' . $is_menu . '_language', $new_language );

		if ( ! $monk_term_translations_id ) {
			update_term_meta( $term_id, '_monk_' . $is_menu . '_translations_id', $term_id );
			$monk_term_translations_id = $term_id;
		}

		$monk_term_translations = get_option( 'monk_' . $is_menu . '_translations_' . $monk_term_translations_id, false );

		if ( ! array_key_exists( $new_language , $monk_term_translations ) ) {
			if ( ! empty( $monk_term_translations ) ) {
				unset( $monk_term_translations[ $current_language ] );
			}

			$monk_term_translations[ $new_language ] = $term_id;
			update_option( 'monk_' . $is_menu . '_translations_' . $monk_term_translations_id, $monk_term_translations );
		}
	}

	/**
	 * Function that erases the data stored by the plugin when term is deleted.
	 *
	 * @since 0.1.0
	 *
	 * @param  int $term_id  Term ID.
	 * @param  int $taxonomy Taxonomy slug.
	 * @return void
	 */
	public function monk_delete_term_meta( $term_id, $taxonomy ) {

		$is_menu                   = 'nav_menu' === $taxonomy ? 'menu' : 'term';
		$term_language             = get_term_meta( $term_id, '_monk_' . $is_menu . '_language', true );
		$monk_term_translations_id = get_term_meta( $term_id, '_monk_' . $is_menu . '_translations_id', true );
		$option_name               = 'monk_' . $is_menu . '_translations_' . $monk_term_translations_id;
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
	 * @since  0.1.0
	 * @param Object $term Object term.
	 * @return  void
	 */
	public function monk_edit_custom_taxonomy_field( $term ) {
		global $monk_languages;
		$monk_language             = get_term_meta( $term->term_id, '_monk_term_language', true );
		$monk_term_translations_id = get_term_meta( $term->term_id, '_monk_term_translations_id', true );
		$option_name               = 'monk_term_translations_' . $monk_term_translations_id;
		$languages                 = get_option( 'monk_active_languages', false );
		$monk_term_translations    = get_option( $option_name, false );

		require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/admin-monk-language-update-term.php';
	}

	/**
	 * Add column content
	 *
	 * @since  0.1.0
	 * @param string $content    The content.
	 * @param string $column_name Title of the column.
	 * @param int    $term_id    Id of the term.
	 * @return  void
	 */
	public function monk_taxonomy_language_column_content( $content, $column_name, $term_id ) {
		if ( 'languages' === $column_name ) :
			global $monk_languages;
			$taxonomies                = get_taxonomies();
			$monk_language             = get_term_meta( $term_id, '_monk_term_language', true );
			$monk_term_translations_id = get_term_meta( $term_id, '_monk_term_translations_id', true );
			$languages                 = get_option( 'monk_active_languages', false );
			$monk_term_translations    = get_option( 'monk_term_translations_' . $monk_term_translations_id, false );
			$default_language          = get_option( 'monk_default_language', false );
			$available_languages       = false;
			$post_type                 = 'none';

			if ( ! is_array( $monk_term_translations ) ) {
				$monk_term_translations = array( $monk_term_translations );
			}

			foreach ( $languages as $language ) {
				if ( ! $monk_language || ( $monk_term_translations && ! array_key_exists( $language, $monk_term_translations ) ) ) {
					$available_languages = true;
				}
			}

			foreach ( $taxonomies as $taxonomy ) {
				if ( isset( $_REQUEST['taxonomy'] ) ) {
					if ( $_REQUEST['taxonomy'] === $taxonomy ) {
						$base_url = admin_url( 'term.php?taxonomy=' . $taxonomy );
						$new_url  = add_query_arg( array(
							'monk_id' => $monk_term_translations_id,
						), admin_url( 'edit-tags.php?taxonomy=' . $taxonomy ) );
					}
				}
			}

			require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/monk-language-column.php';
		endif;
	}

	/**
	 * Add term translation meta field.
	 *
	 * @since  0.1.0
	 * @param object $term Term object.
	 * @return  void
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

		if ( ! is_array( $monk_term_translations ) ) {
			$monk_term_translations = array( $monk_term_translations );
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
	 * @since   0.1.0
	 * @return  void
	 */
	public function monk_activation_notice() {
		$monk_settings_notice = get_option( 'monk_settings_notice', false );

		if ( $monk_settings_notice ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/admin-monk-notice-render.php';
		}
	}

	/**
	 * Function to create a new media translation.
	 *
	 * @return void
	 * @since  0.2.0
	 */
	public function monk_add_attachment_translation() {
		$monk_id          = $_REQUEST['monk_id'];
		$current_post_id  = $_REQUEST['current_post_id'];
		$lang             = $_REQUEST['lang'];
		$attach_path      = get_attached_file( $current_post_id );
		$attach_url       = wp_get_attachment_url( $monk_id );

		$filetype      = wp_check_filetype( basename( $attach_path ), null );
		$wp_upload_dir = wp_upload_dir();

		$attachment    = array(
			'guid'           => $attach_url,
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $attach_path ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attach_id                         = wp_insert_attachment( $attachment, $attach_path );
		$monk_attach_translations          = get_option( 'monk_post_translations_' . $monk_id, false );
		$monk_attach_translations[ $lang ] = $attach_id;

		update_option( 'monk_post_translations_' . $monk_id, $monk_attach_translations );
		if ( $lang ) {
			update_post_meta( $attach_id, '_monk_post_language', $lang );
		}
		update_post_meta( $attach_id, '_monk_post_translations_id', $monk_id );

		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		$attach_data = wp_generate_attachment_metadata( $attach_id, $attach_path );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		$monk_attach_url = admin_url( 'post.php' );
		$language_url = add_query_arg( array(
			'post'    => $attach_id,
			'action'  => 'edit',
			'lang'    => $lang,
			'monk_id' => $monk_id,
		), $monk_attach_url );

		wp_send_json_success( $language_url );
	}

	/**
	 * Function to render input type select in medias modal.
	 *
	 * @param  object $post_id       Post object.
	 * @param  string $language_code Current post language.
	 * @return string $monk_attach_options
	 * @since  0.2.0
	 */
	public function monk_language_selector_render( $post_id, $language_code = false ) {
		global $monk_languages;

		$monk_id             = get_post_meta( $post_id, '_monk_post_translations_id', true );
		$language            = get_post_meta( $post_id, '_monk_post_language', true );
		$active_languages    = get_option( 'monk_active_languages', false );
		$default_language    = get_option( 'monk_default_language', false );
		$post_translations   = get_option( 'monk_post_translations_' . $monk_id, false );
		$is_modal            = ! isset( $_REQUEST['post'] ) ? true : false;
		$post_type           = get_post_type( $post_id );
		$available_languages = false;

		foreach ( $active_languages as $lang ) {
			if ( ( $post_translations && ! array_key_exists( $lang, $post_translations ) ) ) {
				$available_languages = true;
			}
		}

		ob_start();
		if ( 'attachment' === $post_type && $is_modal ) {
			if ( $language_code ) {
				require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/admin-monk-language-selector-render.php';
			} else {
				require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/admin-monk-language-name-render.php';
			}
			$monk_attach_options = ob_get_contents();
			ob_end_clean();

			if ( ! $monk_attach_options ) {
				$monk_attach_options = __( 'No translations available', 'monk' );
			}
			return $monk_attach_options;
		}
	}

	/**
	 * Function to save attachment language, monk id and translations array when media is uploaded on modal.
	 *
	 * @param  object $post        Post object.
	 * @param  array  $attachment  Form fields array.
	 * @return array  $post        Post object.
	 * @since  0.2.0
	 */
	public function monk_fields_to_save( $post, $attachment ) {
		$active_languages = get_option( 'monk_active_languages', false );

		if ( 'upload.php' !== substr( strrchr( parse_url( $_SERVER['HTTP_REFERER'] )['path'], '/' ), 1 ) ) {
			if ( isset( $attachment['language'] ) ) {
				if ( is_array( $active_languages ) ) {
					if ( in_array( $attachment['language'], $active_languages ) ) {
						update_post_meta( $post['ID'], '_monk_post_language', $attachment['language'] );
						update_option( 'monk_post_translations_' . $post['ID'], array( $attachment['language'] => $post['ID'] ) );
						update_post_meta( $post['ID'], '_monk_post_translations_id', $post['ID'] );
						$attachment['language'] = get_post_meta( $post['ID'], '_monk_post_language', true );
					}
				} else {
					if ( $attachment['language'] === $active_languages ) {
						update_post_meta( $post['ID'], '_monk_post_language', $attachment['language'] );
						update_option( 'monk_post_translations_' . $post['ID'], array( $attachment['language'] => $post['ID'] ) );
						update_post_meta( $post['ID'], '_monk_post_translations_id', $post['ID'] );
						$attachment['language'] = get_post_meta( $post['ID'], '_monk_post_language', true );
					}
				}
			}
		}
		return $post;
	}

	/**
	 * Function to display meta fields in medias modal on grid mode.
	 *
	 * @param  array  $form_fields Form fields array.
	 * @param  object $post        Post object.
	 * @return array  $form_fields Form fields array.
	 * @since  0.2.0
	 */
	public function monk_attachment_meta_box( $form_fields, $post ) {
		global $monk_languages;

		$post_id           = $post->ID;
		$language          = get_post_meta( $post_id, '_monk_post_language', true );
		$new_post_language = monk_get_url_args( 'lang' );
		$post_language     = isset( $_REQUEST['post_id'] ) ? get_post_meta( $_REQUEST['post_id'], '_monk_post_language', true ) : '';
		$default_language  = get_option( 'monk_default_language', false );
		$is_modal          = ! isset( $_REQUEST['post'] ) ? true : false;
		$post_type         = $post->post_type;
		$is_translatable   = true;

		if ( $language ) {
			$lang_code = $language;
			$language  = $this->monk_language_selector_render( $post_id );
		} elseif ( ! $language && $post_language ) {
			$lang_code = $post_language;
			$language = $monk_languages[ $post_language ]['name'];
		} elseif ( ! $language && $new_post_language ) {
			$lang_code = $new_post_language;
			$language = $monk_languages[ $new_post_language ]['name'];
		} else {
			$lang_code = $default_language;
			$language = $monk_languages[ $default_language ]['name'];
		}

		if ( 'upload.php' !== substr( strrchr( wp_parse_url( $_SERVER['HTTP_REFERER'] )['path'], '/' ), 1 ) ) {
			$is_translatable = false;
		}

		if ( $is_translatable && ! get_post_meta( $post_id, '_monk_post_language', true ) ) {
			$language = '-';
		}

		if ( ! $is_translatable && ! get_post_meta( $post_id, '_monk_post_language', true ) ) {
			$language = $this->monk_language_selector_render( $post_id );
		}

		if ( 'attachment' === $post_type && $is_modal ) {
			$form_fields['language'] = array(
				'input' => 'html',
				'html'  => $language,
				'label' => __( 'Language', 'monk' ),
			);
			if ( $is_translatable ) {
				$form_fields['translate'] = array(
					'input' => 'html',
					'html'  => $this->monk_language_selector_render( $post_id, $lang_code ),
					'label' => __( 'Translate', 'monk' ),
				);
			}
		}
		return $form_fields;
	}

	/**
	 * This function shows a message if widget needs activation.
	 *
	 * @since   0.1.0
	 * @return  void
	 */
	public function monk_widget_message() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/admin-monk-widget-notice.php';
	}

	/**
	 * This function is helper to monk_delete_attachment_file function.
	 *
	 * @param  int $post_id Id of the post.
	 * @since  0.2.0
	 * @return void
	 */
	public function monk_delete_attachment( $post_id ) {
		global $monk_id;
		$monk_id = get_post_meta( $post_id, '_monk_post_translations_id', true );
	}

	/**
	 * This function prevents that the media file to be deleted if attachment has translations.
	 *
	 * @param  string $file The path of media file.
	 * @since  0.2.0
	 * @return string $file
	 */
	public function monk_delete_attachment_file( $file ) {
		global $monk_id;
		$monk_translations = get_option( 'monk_post_translations_' . $monk_id, array() );

		if ( 1 <= count( $monk_translations ) ) {
			$file = '';
		}

		return $file;
	}

	/**
	 * This function sets the view mode to list when the user first accesses the media list page.
	 *
	 * @since  0.2.0
	 * @return void
	 */
	public function define_view_mode() {
		global $current_screen;

		$is_first_access = get_option( 'monk_first_media_list_access', false );

		if ( 'upload' === $current_screen->base && $is_first_access ) {
			delete_option( 'monk_first_media_list_access' );
			$url = add_query_arg( 'mode', 'list', admin_url( $current_screen->base . '.php' ) );
			wp_safe_redirect( $url, 303 );
			exit();
		}
	}

	/**
	 * This function filter attachments by meta_key in post edit page.
	 *
	 * @since  0.2.0
	 * @param object $query Object Query.
	 * @return  void
	 */
	public function medias_modal_filter( $query ) {
		if ( is_admin() && ( isset( $_REQUEST['post_id'] ) && '0' !== $_REQUEST['post_id'] ) && isset( $_REQUEST['action'] ) ) {

			$default_language  = get_option( 'monk_default_language' );
			$post_id  = $_REQUEST['post_id'];
			$language = get_post_meta( $post_id, '_monk_post_language', true );

			if ( empty( $language ) ) {
				$lang_from_url = monk_get_url_args( 'lang' );

				if ( ! empty( $lang_from_url ) ) {
					$language = $lang_from_url;
				} else {
					$language = $default_language;
				}
			}

			if ( 'query-attachments' === $_REQUEST['action'] ) {
				if ( $language !== $default_language ) {
					$query->set( 'meta_key', '_monk_post_language' );
					$query->set( 'meta_value', $language );
				} else {
					$meta_query_args = array(
						'relation' => 'OR', // Optional, defaults to "AND".
						array(
							'key'   => '_monk_post_language',
							'value' => $default_language,
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
	}

	/**
	 * Adds new menu components to allow their translation
	 *
	 * There are no hooks to use in the menu edit page, so we create the
	 * components using the admin_footer action and move them to the right location
	 *
	 * @since    0.3.0
	 *
	 * @return void
	 */
	public function monk_add_menu_translation_fields() {
		if ( 'nav-menus' !== get_current_screen() -> base || 'locations' === filter_input( INPUT_GET, 'action' ) ) {
			return;
		}

		$active_languages = get_option( 'monk_active_languages', false );
		$default_language = get_option( 'monk_default_language', false );

		if ( '0' === filter_input( INPUT_GET, 'menu' ) ) {
			if ( filter_input( INPUT_GET, 'monk_id' ) ) {
				$monk_id           = filter_input( INPUT_GET, 'monk_id' );
				$menu_translations = get_option( 'monk_menu_translations_' . $monk_id, array() );
			}
			require_once plugin_dir_path( __FILE__ ) . '/partials/admin-monk-new-menu-fields-render.php';
		} else {
			$menu_id             = empty( filter_input( INPUT_GET, 'menu' ) ) || 'delete' === filter_input( INPUT_GET, 'action' ) ? get_user_option( 'nav_menu_recently_edited' ) : filter_input( INPUT_GET, 'menu' );
			$monk_id             = get_term_meta( $menu_id, '_monk_menu_translations_id', true );
			$monk_id             = empty( $monk_id ) ? $menu_id : $monk_id;
			$menu_language       = get_term_meta( $menu_id, '_monk_menu_language', true );
			$menu_translations   = get_option( 'monk_menu_translations_' . $monk_id, array() );
			$new_translation_url = admin_url( 'nav-menus.php?action=edit&menu=0&monk_id=' . $monk_id );

			require_once plugin_dir_path( __FILE__ ) . '/partials/admin-monk-menu-translation-fields-render.php';
		}
	}

	/**
	 * Change nav_menu page components to improve the user experience
	 *
	 * There are no hooks to use in the menu edit page, so we create the
	 * components using the admin_footer action and move them to the right location
	 *
	 * @since    0.3.0
	 *
	 * @return void
	 */
	public function monk_change_nav_menu_fields() {
		$args             = array(
			'hide_empty' => false,
		);
		$nav_menus        = get_terms( 'nav_menu', $args );
		$monk_ids         = array();
		$current_id       = empty( filter_input( INPUT_GET, 'menu' ) ) || 'delete' === filter_input( INPUT_GET, 'action' ) ? get_user_option( 'nav_menu_recently_edited' ) : filter_input( INPUT_GET, 'menu' );
		$registered_menus = get_registered_nav_menus();
		$menus            = get_nav_menu_locations();
		$current_menus    = get_theme_mod( 'nav_menu_locations' );
		$default_language = get_option( 'monk_default_language', false );

		foreach ( $nav_menus as $nav_menu ) {
			$menu_id = $nav_menu->term_id;
			$monk_id = get_term_meta( $menu_id, '_monk_menu_translations_id', true );

			if ( ! in_array( $monk_id, $monk_ids ) ) {
				$monk_ids[] = $monk_id;
			}
		}
		require_once plugin_dir_path( __FILE__ ) . '/partials/admin-monk-select-menu-to-edit-render.php';
	}
}
