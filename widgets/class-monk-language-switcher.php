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
		// process widget options to be saved
	}
}
