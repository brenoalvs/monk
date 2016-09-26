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
$flag = isset( $instance['flag'] ) ? ( bool ) $instance['flag'] : false;
$name = isset( $instance['name'] ) ? ( bool ) $instance['name'] : false;
$lang = isset( $instance['lang-native'] ) ? ( bool ) $instance['lang-native'] : false;
?>
<p class="monk-widgets-form">
	<label for="monk-title"><?php _e( 'Title: ', 'monk' ); ?></label> 
	<input class="widefat monk-input-text" id="monk-title" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">

	<label class="monk-label-block" for="monk-flag">
		<input class="monk-input-checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'flag' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'flag' ) ); ?>"<?php checked( $flag ); ?> />
		<?php _e( 'Show Flags', 'monk'  ); ?>
	</label>

	<label class="monk-label-block" for="monk-name">
		<input class="monk-input-checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'name' ) ); ?>" <?php checked( $name ); ?> />
		<?php _e( 'Show Language Names', 'monk' ); ?>
	</label>

	<label class="monk-label-block" for="monk-lang-native">
		<input class="monk-input-checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'lang-native' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'lang-native' ) ); ?>" <?php checked( $lang ); ?> />
		<?php _e( 'Show Native Language Name <small>(English is default)</small>', 'monk' ); ?>		
	</label>
</p>
