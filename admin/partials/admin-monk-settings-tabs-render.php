<?php
/**
 * Provide the view for the monk_setting_tabs function
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
	<h2 class="nav-tab-wrapper">
		<a href="<?php echo esc_url( $url . '&action=monk_general' ); ?>" class="nav-tab <?php echo esc_attr( $general ); ?>"><?php esc_html_e( 'General', 'monk' ); ?></a>
		<a href="<?php echo esc_url( $url . '&action=monk_tools' ); ?>" class="nav-tab <?php echo esc_attr( $tools ); ?>"><?php esc_html_e( 'Tools', 'monk' ); ?></a>
	</h2>
