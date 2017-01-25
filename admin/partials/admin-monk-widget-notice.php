<?php
/**
 * Show flags in Languages column on posts list.
 *
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Widgets/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( isset( $_REQUEST['page'] ) ) {
	$page = sanitize_text_field( wp_unslash( $_REQUEST['page'] ) );
} else {
	$page = false;
}

if ( 'monk' === $page && ! is_active_widget( false, false, 'monk_language_switcher' ) ) {
	?>
	<div class="notice notice-success is-dismissible">
		<p><?php esc_html_e( 'Remember to activate the ', 'monk' ); ?> <a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php esc_html_e( 'language switcher.', 'monk' ); ?></a></p>
	</div>
	<?php
}
