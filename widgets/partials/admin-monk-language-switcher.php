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

$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Languages', 'monk' );
$flag = isset( $instance['flag'] ) ? ( bool ) $instance['flag'] : false;
$name = isset( $instance['name'] ) ? ( bool ) $instance['name'] : false;
$lang = isset( $instance['lang-native'] ) ? ( bool ) $instance['lang-native'] : false;
?>
<p class="monk-widgets-form">
	<label for="monk-title"><?php esc_html_e( 'Title: ', 'monk' ); ?></label> 
	<input class="widefat monk-input-text" id="monk-title" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
	
	<label class="monk-label-block" for="<?php echo esc_attr( $this->get_field_id( 'flag' ) ); ?>">
		<input class="monk-input-checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'flag' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'flag' ) ); ?>"<?php checked( $flag ); ?> />
		<?php esc_html_e( 'Show Flags', 'monk' ); ?>
	</label>

	<label class="monk-label-block" for="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>">
		<input class="monk-input-checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'name' ) ); ?>" <?php checked( $name ); ?> />
		<?php esc_html_e( 'Show Language Names', 'monk' ); ?>
	</label>

	<label class="monk-label-block" for="<?php echo esc_attr( $this->get_field_id( 'lang-native' ) ); ?>">
		<input class="monk-input-checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'lang-native' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'lang-native' ) ); ?>" <?php checked( $lang ); ?> />
		<?php esc_html_e( 'Show Native Language Name', 'monk' ); ?><small><?php esc_html_e( '(English is default)', 'monk' ); ?> </small>		
	</label>
</p>
