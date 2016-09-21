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

/**
 * Monk Language Switcher.
 *
 * Defines the class name and description.
 *
 * @package    Monk
 * @subpackage Monk/Widgets
 * @author     Leonardo Onofre <leonardodias.14.ld@gmail.com>
 */
class Monk_Language_Switcher extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 *
	 * @param array $classname
	 * @param array $description
	 */
	public function __construct( $classname, $description ) {
		$widget_ops = array( 
			'classname'		=> __( 'Monk_Language_Switcher', 'text_domain' ),
			'description'	=> __( 'The Monk Language Switcher is the best language selector widget', 'text_domain' ),
		);
		parent::__construct( 'Monk_Language_Switcher', __( 'Monk Language Switcher', 'text_domain' ), $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		// outputs the content of the widget
		require plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/partials/public-monk-language-switcher.php';
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {

		// outputs the options form on admin
		require plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/partials/admin-monk-language-switcher.php';
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}

	/**
	 * Register widget
	 */
	public function register_widgets() {

		/**
		 * Register widget
		 */
		register_widget( 'Monk_Language_Switcher' );
	}

	/**
	 * Enqueue scripts and styles on admin-view
	 */
	public function admin_widgets_enqueue_scripts() {
		
		/**
		 * This function does enqueue .css files.
		 */
		wp_enqueue_style( 'Admin_Monk_Language_Switcher_Style', plugin_dir_url(__FILE__) . 'css/admin-language-switcher-style.css', array(), '1.0.0', 'all' );
	}

	/**
	 * Enqueue scripts and styles on public-view
	 */
	public function public_widgets_enqueue_scripts() {
		
		/**
		 * This function does enqueue jquery-ui .css files.
		 */
		wp_enqueue_style( 'Jquery_UI_Style', plugin_dir_url(__FILE__) . 'css/jquery-ui.min.css', array(), '1.0.0', 'all' );

		/**
		 * This function does enqueue .css files.
		 */
		wp_enqueue_style( 'Public_Monk_Language_Switcher_Style', plugin_dir_url(__FILE__) . 'css/public-language-switcher-style.css', array(), '1.0.0', 'all' );

		/**
		 * This function does enqueue .js files.
		 */
		wp_enqueue_script( 'Monk_Language_Switcher_Script', plugin_dir_url(__FILE__) . 'js/public-language-switcher-script.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-selectmenu' ), '1.0.0', tru );
	}

}
