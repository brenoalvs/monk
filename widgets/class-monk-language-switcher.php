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
			'classname' => 'Monk_Language_Switcher',
			'description' 	=> 'The Monk Language Switcher is the best language selector widget',
		);
		parent::__construct( 'Monk_Language_Switcher', 'Monk Language Switcher', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
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
		register_widget( 'Monk_Language_Switcher' );
	}
}
