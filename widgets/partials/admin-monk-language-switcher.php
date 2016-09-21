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

$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
?>
<p class="monk-widgets-form">
	<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title: ' ) ); ?></label> 
	<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">

	<label><?php _e( esc_attr( 'Show Flags ' ) ); ?></label>
	<input class="input-checkbox" type="checkbox" name="<?php echo esc_attr( 'view-mode' ); ?>" value="<?php echo esc_attr( 'flag' ); ?>"><br />
	<label><?php _e( esc_attr( 'Show Language Names ' ) ); ?></label>
	<input class="input-checkbox" type="checkbox" name="<?php echo esc_attr( 'view-mode' ); ?>" value="<?php echo esc_attr( 'name' ); ?>" checked="checked"><br />
	<label><?php _e( esc_attr( 'Show Native Language Name ' ) ); ?></label>
	<input class="input-radio" type="radio" name="<?php echo esc_attr( 'lang-mode' ); ?>" value="<?php echo esc_attr( 'native' ); ?>"><br />
	<label><?php _e( esc_attr( 'Show English Language Name ' ) ); ?></label>
	<input class="input-radio" type="radio" name="<?php echo esc_attr( 'lang-mode' ); ?>" value="<?php echo esc_attr( 'english' ); ?>" checked="checked"><br />
</p>
<?php 
