<?php
/**
 * Provide the view for the monk_default_language_render function
 *
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
$default_language_url = get_option( 'monk_default_language_url', false ) ? 'checked=checked' : '';
?>
<input type="checkbox" name="monk_default_language_url" <?php echo esc_attr( $default_language_url ); ?>>
