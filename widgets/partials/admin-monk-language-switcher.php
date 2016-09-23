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
	<label for="monk-language-switcher-title"><?php esc_html_e( 'Title: ', 'monk' ); ?></label> 
	<input class="widefat monk-input-text" id="monk-language-switcher-title" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">

	<label class="monk-label-block" for="monk-language-switcher-flag">
		<input class="monk-input-checkbox" type="checkbox" id="monk-language-switcher-flag" name="view-mode" value="flag">
		<?php esc_html_e( 'Show Flags', 'monk'  ); ?>
	</label>

	<label class="monk-label-block" for="monk-language-switcher-name">
		<input class="monk-input-checkbox" type="checkbox" id="monk-language-switcher-name" name="view-mode" value="name" checked="checked">
		<?php esc_html_e( 'Show Language Names', 'monk' ); ?>
	</label>

	<label class="monk-label-block" for="monk-language-switcher-native">
		<input class="monk-input-radio" type="radio" id="monk-language-switcher-lang-native" name="lang-mode" value="native">
		<?php esc_html_e( 'Show Native Language Name', 'monk' ); ?>		
	</label>

	<label class="monk-label-block" for="monk-language-switcher-english">
		<input class="monk-input-radio" type="radio" id="monk-language-switcher-english" name="lang-mode" value="english" checked="checked">
		<?php esc_html_e( 'Show English Language Name', 'monk' ); ?>		
	</label>
</p>
