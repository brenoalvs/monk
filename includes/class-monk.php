<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.1.0
 * @package    Monk
 * @subpackage Monk/Includes
 */
class Monk {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      Monk_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $monk    The string used to uniquely identify this plugin.
	 */
	protected $monk;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.1.0
	 * @return  void
	 */
	public function __construct() {

		$this->plugin_name = 'Monk';
		$this->version = '0.3.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_global_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_link_hooks();
		$this->define_widget_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Monk_Loader. Orchestrates the hooks of the plugin.
	 * - Monk_i18n. Defines internationalization functionality.
	 * - Monk_Admin. Defines all hooks for the admin area.
	 * - Monk_Public. Defines all hooks for the public side of the site.
	 * - Monk_Language_Switcher. Defines all functions related to Monk_Language_Switcher widget.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @return  void
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-monk-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-monk-i18n.php';

		/**
		 * Imports a global array with the translatable and native language names.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/monk-available-languages.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-monk-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-monk-public.php';

		/**
		 * The class responsible for changing the links structure
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-monk-links.php';

		/**
		 * Class responsible for create Monk_Language_Switcher widget.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/class-monk-language-switcher.php';

		/**
		 * Helper functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/monk-functions.php';

		$this->loader = new Monk_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Monk_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @return  void
	 */
	private function set_locale() {

		$monk_i18n = new Monk_I18n();

		$this->loader->add_action( 'plugins_loaded', $monk_i18n, 'load_plugin_textdomain' );
		$this->loader->add_filter( 'locale', $monk_i18n, 'monk_define_locale' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @return  void
	 */
	private function define_global_hooks() {

		$this->loader->add_filter( 'query_vars', $this, 'monk_query_vars', 10, 1 );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @return  void
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Monk_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'monk_activation_notice' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'monk_add_menu_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'monk_options_init' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'monk_activation_redirect' );
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'monk_add_menu_translation_fields' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'monk_post_meta_box' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'monk_save_post_meta_box', 10, 2 );
		$this->loader->add_action( 'wp_trash_post', $plugin_admin, 'monk_delete_post_data' );
		$this->loader->add_action( 'before_delete_post', $plugin_admin, 'monk_delete_post_data' );
		$this->loader->add_action( 'delete_attachment', $plugin_admin, 'monk_delete_post_data' );
		$this->loader->add_action( 'customize_register', $plugin_admin, 'monk_language_customizer' );
		$this->loader->add_action( 'wp_head', $plugin_admin, 'monk_customize_css' );
		$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'monk_admin_languages_selector' );
		$this->loader->add_filter( 'pre_get_posts', $plugin_admin, 'monk_admin_posts_filter' );
		$this->loader->add_filter( 'get_terms_defaults', $plugin_admin, 'monk_admin_terms_filter', 10, 2 );
		$this->loader->add_filter( 'manage_posts_columns', $plugin_admin, 'monk_language_column_head' );
		$this->loader->add_filter( 'manage_pages_columns', $plugin_admin, 'monk_language_column_head' );
		$this->loader->add_filter( 'manage_media_columns', $plugin_admin, 'monk_language_column_head' );
		$this->loader->add_action( 'manage_posts_custom_column', $plugin_admin, 'monk_language_column_content', 10, 2 );
		$this->loader->add_action( 'manage_pages_custom_column', $plugin_admin, 'monk_language_column_content', 10, 2 );
		$this->loader->add_action( 'manage_media_custom_column', $plugin_admin, 'monk_language_column_content', 10, 2 );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'monk_widget_message' );
		$this->loader->add_action( 'wp_loaded', $this, 'add_term_hooks' );
		$this->loader->add_action( 'edit_attachment', $plugin_admin, 'monk_save_post_meta_box' );
		$this->loader->add_action( 'wp_ajax_monk_add_attachment_translation', $plugin_admin, 'monk_add_attachment_translation' );
		$this->loader->add_filter( 'attachment_fields_to_save', $plugin_admin, 'monk_fields_to_save', 10, 2 );
		$this->loader->add_filter( 'attachment_fields_to_edit', $plugin_admin, 'monk_attachment_meta_box', 10, 2 );
		$this->loader->add_filter( 'wp_delete_file', $plugin_admin, 'monk_delete_attachment_file' );
		$this->loader->add_action( 'delete_attachment', $plugin_admin, 'monk_delete_attachment' );
		$this->loader->add_action( 'current_screen', $plugin_admin, 'define_view_mode' );
		$this->loader->add_filter( 'pre_get_posts', $plugin_admin, 'medias_modal_filter' );
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'monk_change_nav_menu_fields' );
		$this->loader->add_action( 'wp_ajax_monk_set_language_to_elements', $plugin_admin, 'monk_set_language_to_elements' );
		$this->loader->add_action( 'wp_ajax_monk_save_language_packages', $plugin_admin, 'monk_save_language_packages' );
		$this->loader->add_action( 'wp_ajax_monk_save_options', $plugin_admin, 'monk_save_site_options' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @return  void
	 */
	private function define_public_hooks() {

		$plugin_public = new Monk_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'pre_get_posts', $plugin_public, 'monk_public_posts_filter' );
		$this->loader->add_filter( 'get_terms_defaults', $plugin_public, 'monk_public_terms_filter' );
		$this->loader->add_filter( 'wp_nav_menu_args', $plugin_public, 'monk_filter_nav_menus' );
		$option_names = apply_filters( 'monk_translatable_option', array(
			'blogname',
			'blogdescription',
		));

		foreach ( $option_names as $option ) {
			$this->loader->add_filter( 'pre_option_' . $option, $plugin_public, 'monk_filter_translatable_option', 10, 2 );
		}
	}

	/**
	 * Register all of the hooks related to links and permalinks
	 *
	 * @since    0.2.0
	 * @access   private
	 */
	private function define_link_hooks() {

		$plugin_links = new Monk_Links( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_links, 'monk_add_home_rewrite_rule' );
		$this->loader->add_filter( 'home_url', $plugin_links, 'monk_add_language_home_permalink', 10, 2 );
		$this->loader->add_filter( 'day_link', $plugin_links, 'monk_add_language_date_permalink', 20, 2 );
		$this->loader->add_filter( 'post_link', $plugin_links, 'monk_add_language_post_permalink', 20, 2 );
		$this->loader->add_filter( 'page_link', $plugin_links, 'monk_add_language_page_permalink', 20, 2 );
		$this->loader->add_filter( 'term_link', $plugin_links, 'monk_add_language_term_permalink', 20, 3 );
		$this->loader->add_filter( 'year_link', $plugin_links, 'monk_add_language_date_permalink', 20, 2 );
		$this->loader->add_filter( 'month_link', $plugin_links, 'monk_add_language_date_permalink', 20, 2 );
		$this->loader->add_filter( 'author_link', $plugin_links, 'monk_add_language_author_permalink', 20, 2 );
		$this->loader->add_filter( 'search_link', $plugin_links, 'monk_add_language_search_permalink', 20 );
		$this->loader->add_filter( 'post_type_link', $plugin_links, 'monk_add_language_post_permalink', 20, 2 );
		$this->loader->add_filter( 'attachment_link', $plugin_links, 'monk_add_language_attachment_permalink', 20, 2 );
		$this->loader->add_filter( 'post_type_archive_link', $plugin_links, 'monk_add_language_post_archive_permalink', 20, 2 );
		$this->loader->add_action( 'get_search_form', $plugin_links, 'monk_change_search_form', 50 );
		$this->loader->add_action( 'template_redirect', $plugin_links, 'monk_canonical_redirection', 5 );
		$this->loader->add_action( 'rewrite_rules_array', $plugin_links, 'monk_create_rewrite_functions', 10, 1 );
		$this->loader->add_action( 'admin_init', $plugin_links, 'monk_flush_on_update' );
		$this->loader->add_action( 'get_previous_post_join', $plugin_links, 'monk_previous_and_next_posts', 10, 5 );
		$this->loader->add_action( 'get_next_post_join', $plugin_links, 'monk_previous_and_next_posts', 10, 5 );
	}

	/**
	 * Register all of the hooks related to Monk Widgets
	 *
	 * @since    0.1.0
	 * @access   private
	 * @return  void
	 */
	private function define_widget_hooks() {

		$this->loader->add_action( 'widgets_init', $this, 'register_widgets' );
	}

	/**
	 * Register all of the hooks related to terms
	 *
	 * @since    0.1.0
	 * @access   public
	 * @return  void
	 */
	public function add_term_hooks() {
		$plugin_admin = new Monk_Admin( $this->get_plugin_name(), $this->get_version() );
		$taxonomies = get_taxonomies();

		foreach ( $taxonomies as $taxonomy ) {
			add_action( $taxonomy . '_add_form_fields', array( $plugin_admin, 'monk_custom_taxonomy_field' ) );
			add_action( $taxonomy . '_edit_form_fields', array( $plugin_admin, 'monk_edit_custom_taxonomy_field' ) );
			add_action( $taxonomy . '_edit_form_fields', array( $plugin_admin, 'monk_term_translation_meta_field' ) );
			add_action( $taxonomy . '_edit_form_fields', array( $plugin_admin, 'monk_post_meta_box' ) );
			add_filter( 'manage_edit-' . $taxonomy . '_columns', array( $plugin_admin, 'monk_language_column_head' ) );
			add_action( 'manage_' . $taxonomy . '_custom_column', array( $plugin_admin, 'monk_taxonomy_language_column_content' ), 10, 3 );
		}

		add_action( 'created_term', array( $plugin_admin, 'monk_create_term_meta' ), 10, 3 );
		add_action( 'edited_terms', array( $plugin_admin, 'monk_update_term_meta' ), 10, 2 );
		add_action( 'pre_delete_term', array( $plugin_admin, 'monk_delete_term_meta' ), 10, 2 );
	}

	/**
	 * Registrate the query vars to generate the custom urls.
	 *
	 * @since    0.1.0
	 *
	 * @param array $vars Array of monk query vars.
	 * @return array $vars
	 */
	public function monk_query_vars( $vars ) {
		$vars[] = 'lang';
		$vars[] = 'monk_id';
		return $vars;
	}

	/**
	 * Register all the widgets
	 *
	 * @since    0.1.0
	 * @access   public
	 * @return  void
	 */
	public function register_widgets() {

		/**
		 * Register the Monk_Language_Switcher widget.
		 */
		register_widget( 'Monk_Language_Switcher' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.1.0
	 * @return  void
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.1.0
	 * @return    Monk_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.1.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
