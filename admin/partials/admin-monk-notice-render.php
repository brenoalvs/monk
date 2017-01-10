<?php
/**
 * Provide the view for the monk_display_activation_notice function
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Includes
 */

?>
<div class="updated notice is-dismissible monk-banner">
	<p><?php printf( __( '<strong>Monk:</strong> You need to <a href="%s">configure your language preferences</a>.', 'monk' ), admin_url( 'admin.php?page=monk' ) ); ?></p>
</div>
