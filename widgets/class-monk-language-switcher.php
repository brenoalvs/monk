<?php

/**
 * Monk Language Switcher.
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
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
 * @author     Leonardo Onofre <leonardodias.14.ld@gmail.com>
 */
class Monk_Language_Switcher extends WP_Widget {

	/**
	 * Sets up the widgets classname and description.
	 */
	public function __construct() {
		$widget_options = array( 
			'classname'   => 'monk_language_switcher',
			'description' => __( 'The Monk Language Switcher is the best language selector widget', 'monk' ),
			);
		
		parent::__construct( 'monk_language_switcher', 'Monk Language Switcher', $widget_options );
	}

	/**
	 * Outputs the content of the front-end side
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/partials/public-monk-language-switcher.php';
	}

	/**
	 * Outputs the options form on admin side
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/partials/admin-monk-language-switcher.php';
	}

	/**
	 * Process widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                = $old_instance;
		$instance['title']       = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['flag']        = ! empty( $new_instance['flag'] ) ? true : false;
		$instance['name']        = ! empty( $new_instance['name'] ) ? true : false;
		$instance['lang-native'] = ! empty( $new_instance['lang-native'] ) ? true : false;

		return $instance;
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
	public function add_monk_filter() {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/partials/monk-language-filter.php';
	}

	/**
	 * Add parameters to filter by meta_key
	 *
	 * @param string $query 
	 */
	public function posts_where( $query ) {
		if ( is_admin() && $query->is_main_query() ) {     
			if ( isset( $_GET['monk_language_filter'] ) && ! empty( $_GET['monk_language_filter'] ) && $query->is_search() ) {
				$language = $_GET['monk_language_filter'];
				$query->set( 'meta_key', '_monk_languages' );
				$query->set( 'meta_value', $language );
			} elseif ( ! isset( $_GET['monk_language_filter'] ) ) {
				$query->set( 'meta_key', '_monk_languages' );
				$query->set( 'meta_value', get_option( 'monk_default_language' ) );
			}
		}    
	}

	/**
	 * Include styles related to Customize options
	 *
	 * @param array $title Title of the column
	 */
	public function add_custom_column_head( $title ) {
		$title['languages'] = __( 'Languages', 'monk' );
		return $title;
	}

	/**
	 * Include styles related to Customize options
	 *
	 * @param string $colum_name Title of the column
	 * @param string $post_ID    Post id
	 */
	public function add_custom_column_content( $column_name, $post_ID ) {
		if ( $column_name == 'languages' ) {
			$languages = get_post_meta( $post_ID, '_monk_languages' );
			$teste = get_post_meta( $post_ID, '_monk_translations_id' );
			if ( $languages ) {
				foreach ( $languages as $language ) {
					require plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/partials/monk-language-column.php';
				}
			}
		}
	}
}
