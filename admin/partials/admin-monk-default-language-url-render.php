<?php
/**
 * Provide the view for the monk_default_language_url_render function
 *
 * @since      0.4.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
$default_language_url = get_option( 'monk_default_language_url', false ) ? true : false;
?>
<input type="checkbox" name="monk_default_language_url" <?php checked( $default_language_url, true ); ?>>
