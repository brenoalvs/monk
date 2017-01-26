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
	<p><strong><?php esc_html_e( 'Monk: ', 'monk' ); ?></strong><?php esc_html_e( 'You need to ', 'monk' ); ?>
	<a href="<?php echo esc_html( esc_url( admin_url( 'admin.php?page=monk' ) ) ); ?>"><?php esc_html_e( 'configure your language preferences', 'monk' ); ?></a>.
	</p>
</div>
