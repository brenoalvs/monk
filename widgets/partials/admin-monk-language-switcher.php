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

$title = $instance['title'];
?>
	<p>
	<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ) ); ?></label> 
	<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
	</p>
	<label>Show Flags</label><input type="checkbox" name="<?php echo esc_attr( 'view-mode' ); ?>" value="<?php echo esc_attr( 'flag' ); ?>"></br >
	<label>Show Language Names</label><input type="checkbox" name="<?php echo esc_attr( 'view-mode' ); ?>" value="<?php echo esc_attr( 'name' ); ?>" checked="checked"></br >
	<label>Show Native Language Name</label><input type="radio" name="<?php echo esc_attr( 'lang-mode' ); ?>" value="<?php echo esc_attr( 'native' ); ?>"></br >
	<label>Show English Language Name</label><input type="radio" name="<?php echo esc_attr( 'lang-mode' ); ?>" value="<?php echo esc_attr( 'english' ); ?>" checked="checked"></br >
	<?php 
