<?php
/**
 * Provide the view for the monk_display_activation_notice function
 *
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Includes
 */

?>
<div class="updated notice is-dismissible monk-banner">
	<p><?php printf( esc_attr__( '<strong>Monk:</strong> You need to <a href="%s">configure your language preferences</a>.', 'monk' ), esc_url( admin_url( 'admin.php?page=monk' ) ) ); ?></p>
</div>
