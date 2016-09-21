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
	 * Register languages and flags
	 */
	public function widgets_enqueue_styles() {
		
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
		wp_enqueue_style( 'Monk_Language_Switcher_Style', plugin_dir_url(__FILE__) . 'css/admin-language-switcher-style.css', array(), '1.0.0', 'all' );
	}
}
