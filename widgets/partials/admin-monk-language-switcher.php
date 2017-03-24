<?php
/**
 * Monk Language Switcher.
 *
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Widgets/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$title     = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Languages', 'monk' );
$flag      = isset( $instance['flag'] ) ? (bool) $instance['flag'] : false;
$monk_love = isset( $instance['appretiation'] ) ? (bool) $instance['appretiation'] : false;
?>
<p class="monk-widgets-form">
	<label for="monk-title"><?php esc_html_e( 'Title: ', 'monk' ); ?></label> 
	<input class="widefat monk-input-text" id="monk-title" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
	<label class="monk-label-block" for="<?php echo esc_attr( $this->get_field_id( 'flag' ) ); ?>">
		<input class="monk-input-checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'flag' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'flag' ) ); ?>"<?php checked( $flag ); ?> />
		<?php esc_html_e( 'Hide Flags', 'monk' ); ?>
	</label>
	<label class="monk-label-block" for="<?php echo esc_attr( $this->get_field_id( 'appretiation' ) ); ?>">
		<input class="monk-input-checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'appretiation' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'appretiation' ) ); ?>"<?php checked( $monk_love ); ?> />
		<?php esc_html_e( 'Appretiate Monk', 'monk' ); ?>
	</label>
</p>
