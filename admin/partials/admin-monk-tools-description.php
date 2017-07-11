<?php
/**
 * Provide the view for the monk_options function
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
?>
<p><?php esc_html_e( 'Here you can option to set default language to all posts and terms without language.', 'monk' ); ?><br />
<?php esc_html_e( 'Check the checkbox to confirm.', 'monk' ); ?></p>
<?php
