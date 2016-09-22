<?php
/**
 * Monk Language Switcher.
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Widgets/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'monk' );
?>
<p class="monk-widgets-form">
	<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title: ', 'monk' ); ?></label> 
	<input class="widefat monk-input-text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">

	<label for="<?php echo $this->get_field_name( 'view-mode' ) ?>"><?php _e( 'Show Flags', 'monk'  ); ?></label>
	<input class="monk-input-checkbox" type="checkbox" name="<?php echo $this->get_field_name( 'view-mode' ) ?>" value="flag"></br >
	<label for="<?php echo $this->get_field_name( 'view-mode' ) ?>"><?php _e( 'Show Language Names', 'monk' ); ?></label>
	<input class="monk-input-checkbox" type="checkbox" name="<?php echo $this->get_field_name( 'view-mode' ) ?>" value="name" checked="checked"></br >
	<label for="<?php echo $this->get_field_name( 'lang-mode' ) ?>"><?php _e( 'Show Native Language Name', 'monk' ); ?></label>
	<input class="monk-input-radio" type="radio" name="<?php echo $this->get_field_name( 'lang-mode' ) ?>" value="native"></br >
	<label for="<?php echo $this->get_field_name( 'lang-mode' ) ?>"><?php _e( 'Show English Language Name', 'monk' ); ?></label>
	<input class="monk-input-radio" type="radio" name="<?php echo $this->get_field_name( 'lang-mode' ) ?>" value="english" checked="checked"></br >
</p>
<?php 
