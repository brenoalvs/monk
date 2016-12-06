<?php
/**
 * Provide the view for the monk_display_activation_notice function
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Includes
 */

?>
<div class="updated notice is-dismissible monk-banner">
		<h2><?php esc_html_e( 'Monk has been activated, go check the settings', 'monk' ); ?></h2>
	<p>
		<a href="<?php esc_attr_e( 'admin.php?page=monk', 'monk' ); ?>" class="button button-primary"><?php esc_html_e( 'Go to Monk settings', 'monk' ); ?></a>
	</p>
</div>
