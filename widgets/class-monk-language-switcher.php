<?php
/**
 * Monk Language Switcher.
 *
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Widgets
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class responsible for create Monk_Language_Switcher widget.
 *
 * @package    Monk
 * @subpackage Monk/Widgets
 */
class Monk_Language_Switcher extends WP_Widget {

	/**
	 * The default language of the plugin.
	 *
	 * @since    0.7.0
	 * @access   private
	 * @var      string    $default_language    The default language of the plugin.
	 */
	private $default_language;

	/**
	 * The active languages of the plugin.
	 *
	 * @since    0.7.0
	 * @access   private
	 * @var      array $active_languages The active languages of the plugin.
	 */
	private $active_languages;

	/**
	 * Sets up the widgets classname and description.
	 *
	 * @since  0.1.0
	 */
	public function __construct() {
		$this->default_language = get_option( 'monk_default_language', false );
		$this->active_languages = get_option( 'monk_active_languages', array() );
		$widget_options         = array(
			'classname'   => 'monk_language_switcher',
			'description' => __( 'Switch between site translations.', 'monk' ),
		);
		parent::__construct( 'monk_language_switcher', __( 'Language Switcher', 'monk' ), $widget_options );
	}

	/**
	 * Outputs the content of the front-end side
	 *
	 * @since  0.1.0
	 * @param array $args     Args.
	 * @param array $instance The widget options.
	 */
	public function widget( $args, $instance ) {
		$monk_languages = monk_get_available_languages();

		$switchable_languages     = array();
		$active_languages_slug    = array();
		$title                    = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Languages', 'monk' );
		$flag                     = empty( $instance['flag'] ) ? true : false;
		$monk_love                = ! empty( $instance['monk_love'] ) ? true : false;
		$active_languages         = $this->active_languages;
		$current_language         = '';
		$monk_languages_reverted  = array();
		$default_language         = $this->default_language;
		$default_slug             = $monk_languages[ $default_language ]['slug'];
		$has_default_language_url = get_option( 'monk_default_language_url', false );
		$current_locale           = monk_get_locale_by_slug( get_query_var( 'lang' ) );

		$translation_data = monk_get_translations();

		foreach ( $translation_data as $slug => $data ) {
			if ( ! empty( $data['url'] ) ) {
				$switchable_languages[ $slug ] = $data['url'];
			}
			if ( true === $translation_data[ $slug ]['current'] ) {
				$current_language = $slug;
			}
		}

		require plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/partials/public-monk-language-switcher.php';
	}

	/**
	 * Outputs the options form on admin side
	 *
	 * @since  0.1.0
	 * @param array $instance The widget options.
	 * @return  void
	 */
	public function form( $instance ) {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/partials/admin-monk-language-switcher.php';
	}

	/**
	 * Process widget options on save
	 *
	 * @since  0.1.0
	 * @param array $new_instance The new options.
	 * @param array $old_instance The previous options.
	 * @return array $instance
	 */
	public function update( $new_instance, $old_instance ) {
		$instance              = $old_instance;
		$instance['title']     = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['flag']      = ! empty( $new_instance['flag'] ) ? true : false;
		$instance['monk_love'] = ! empty( $new_instance['monk_love'] ) ? true : false;

		return $instance;
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

		$wp_customize->add_section( 'monk_language_switcher', array(
			'title'    => __( 'Language Switcher', 'monk' ),
			'priority' => 700,
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
}
