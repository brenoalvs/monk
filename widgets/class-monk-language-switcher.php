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
	 * Sets up the widgets classname and description.
	 *
	 * @since  0.1.0
	 */
	public function __construct() {
		$widget_options = array(
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
		$flag                     = ! empty( $instance['flag'] ) ? true : false;
		$monk_love                = ! empty( $instance['monk_love'] ) ? true : false;
		$active_languages         = get_option( 'monk_active_languages' );
		$current_language         = '';
		$monk_languages_reverted  = array();
		$default_language         = get_option( 'monk_default_language', false );
		$default_slug             = $monk_languages[ $default_language ]['slug'];
		$has_default_language_url = get_option( 'monk_default_language_url', false );
		$current_locale             = monk_get_locale_by_slug( get_query_var( 'lang' ) );

		if ( get_query_var( 'lang' ) && in_array( $current_locale, $active_languages ) ) {
			$current_language = sanitize_text_field( get_query_var( 'lang' ) );
		} else {
			$current_language = $monk_languages[ $default_language ]['slug'];
		}

		if ( is_front_page() || is_post_type_archive() || is_date() || is_404() || is_author() || is_search() ) {

			foreach ( $active_languages as $code ) {
				$active_languages_slug[] = $monk_languages[ $code ]['slug'];
			}

			foreach ( $active_languages_slug as $lang_code ) {
				$current_url = monk_get_current_url();

				if ( $lang_code !== $current_language ) {
					if ( get_option( 'permalink_structure', false ) ) {
						if ( get_query_var( 'lang' ) ) {
							$pattern                  = '/\/(' . implode( '|', $active_languages_slug ) . ')/';
							$current_url              = remove_query_arg( 'lang', $current_url );
							$current_url              = is_ssl() ? str_replace( 'https://', '', $current_url ) : str_replace( 'http://', '', $current_url );

							if ( empty( $has_default_language_url ) && $lang_code === $default_slug ) {
								$current_url = preg_replace( $pattern, '', $current_url );
							} else {
								$current_url = preg_replace( $pattern, '/' . $lang_code, $current_url );
							}

							$current_url = is_ssl() ? 'https://' . $current_url : 'http://' . $current_url;
						} else {
							if ( dirname( $_SERVER['PHP_SELF'] ) === '\\' || dirname( $_SERVER['PHP_SELF'] ) === '/' ) {
								$current_url = str_replace( home_url(), home_url() . $lang_code . '/', $current_url );
							} else {
								$current_url = str_replace( dirname( $_SERVER['PHP_SELF'] ), trailingslashit( dirname( $_SERVER['PHP_SELF'] ) ) . $lang_code, $current_url );
							}
						}
						$switchable_languages[ $lang_code ] = $current_url;
					} else {
						if ( empty( $has_default_language_url ) && $lang_code === $default_slug ) {
							$switchable_languages[ $lang_code ] = remove_query_arg( 'lang', $current_url );
						} else {
							$switchable_languages[ $lang_code ] = add_query_arg( 'lang', sanitize_key( $lang_code ) );
						}
					}
				}
			}
		} // End if().

		if ( is_singular() && ! is_front_page() ) {
			$current_id                = get_the_id();
			$monk_post_translations_id = get_post_meta( $current_id, '_monk_post_translations_id', true );
			$monk_total_translations   = get_option( 'monk_post_translations_' . $monk_post_translations_id, false );

			if ( get_post_meta( $current_id, '_monk_post_language', true ) ) {
				$current_language = $monk_languages[ get_post_meta( $current_id, '_monk_post_language', true ) ]['slug'];
				$current_locale   = get_post_meta( $current_id, '_monk_post_language', true );
			} else {
				$current_language = $monk_languages[ $default_language ]['slug'];
				$current_locale   = $default_language;
			}

			if ( is_array( $monk_total_translations ) ) {
				foreach ( $monk_total_translations as $lang_code => $post_id ) {
					if ( in_array( $lang_code, $active_languages, true ) || $monk_languages[ $lang_code ]['slug'] === $current_language ) {
						$monk_translations[ $lang_code ] = $post_id;
					}
				}
			} else {
				$monk_translations[ $current_locale ] = $current_id;
			}

			if ( $monk_translations ) {
				foreach ( $monk_translations as $lang_code => $post_id ) {
					if ( $monk_languages[ $lang_code ]['slug'] !== $current_language ) {
						$switchable_languages[ $monk_languages[ $lang_code ]['slug'] ] = get_permalink( $post_id );
					}
				}
			} else {
				$current_language                       = $monk_languages[ get_option( 'monk_default_language', false ) ]['slug'];
				$monk_translations[ $current_language ] = $current_id;
				foreach ( $monk_translations as $lang_code => $post_id ) {
					if ( $lang_code !== $current_language ) {
						$switchable_languages[ $lang_code ] = get_permalink( $post_id );
					}
				}
			}
		} // End if().

		if ( is_archive() && ( is_category() || is_tag() || is_tax() ) ) {
			$monk_term_translations_id = get_term_meta( get_queried_object_id(), '_monk_term_translations_id', true );
			$monk_total_translations   = get_option( 'monk_term_translations_' . $monk_term_translations_id, false );

			if ( get_term_meta( get_queried_object_id(), '_monk_term_language', true ) ) {
				$current_language = $monk_languages[ get_term_meta( get_queried_object_id(), '_monk_term_language', true ) ]['slug'];
				$current_locale   = get_term_meta( get_queried_object_id(), '_monk_term_language', true );
			} else {
				$current_language = $monk_languages[ $default_language ]['slug'];
				$current_locale   = $default_language;
			}

			if ( is_array( $monk_total_translations ) ) {
				foreach ( $monk_total_translations as $lang_code => $term_id ) {
					if ( in_array( $lang_code, $active_languages, true ) || $monk_languages[ $lang_code ]['slug'] === $current_language ) {
						$monk_translations[ $lang_code ] = $term_id;
					}
				}
			} else {
				$monk_translations[ $current_locale ] = get_the_id();
			}

			if ( $monk_translations ) {
				foreach ( $monk_translations as $lang_code => $term_id ) {
					if ( $monk_languages[ $lang_code ]['slug'] !== $current_language ) {
						$switchable_languages[ $monk_languages[ $lang_code ]['slug'] ] = get_term_link( $term_id );
					}
				}
			} else {
				$current_language                       = $monk_languages[ get_option( 'monk_default_language', false ) ]['slug'];
				$monk_translations[ $current_language ] = get_queried_object_id();

				foreach ( $monk_translations as $lang_code => $term_id ) {
					if ( $lang_code !== $current_language ) {
						$switchable_languages[ $lang_code ] = get_term_link( $term_id );
					}
				}
			}
		} // End if().

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

		$wp_customize->add_section( 'monk_language_switcher' , array(
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
