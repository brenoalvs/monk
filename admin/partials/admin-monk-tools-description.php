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
	exit();
}

?>
<p><?php esc_html_e( 'Here you can set default language to all posts and terms without language.', 'monk' ); ?><br />
<?php esc_html_e( 'Mark the checkbox and click on "Save Changes" to confirm.', 'monk' ); ?></p>
