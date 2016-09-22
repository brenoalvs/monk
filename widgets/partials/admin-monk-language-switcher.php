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

$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'monk' );
?>
<p class="monk-widgets-form">
	<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title: ', 'monk' ); ?></label> 
	<input class="widefat monk-input-text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo $this->get_field_name( 'title' ) ?>" type="text" value="<?php echo esc_attr( $title ); ?>">

	<label><?php esc_attr_e( 'Show Flags ', 'monk'  ); ?></label>
	<input class="monk-input-checkbox" type="checkbox" name="<?php echo 'view-mode' ?>" value="<?php echo esc_attr( 'flag' ); ?>"></br >
	<label><?php esc_attr_e( 'Show Language Names ', 'monk' ); ?></label>
	<input class="monk-input-checkbox" type="checkbox" name="<?php echo 'view-mode' ?>" value="<?php echo esc_attr( 'name' ); ?>" checked="checked"></br >
	<label><?php esc_attr_e( 'Show Native Language Name ', 'monk' ); ?></label>
	<input class="monk-input-radio" type="radio" name="<?php echo 'lang-mode' ?>" value="<?php echo esc_attr( 'native' ); ?>"></br >
	<label><?php esc_attr_e( 'Show English Language Name ', 'monk' ); ?></label>
	<input class="monk-input-radio" type="radio" name="<?php echo 'lang-mode' ?>" value="<?php echo esc_attr( 'english' ); ?>" checked="checked"></br >
</p>
<?php 
