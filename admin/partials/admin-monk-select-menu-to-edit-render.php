<?php
/**
 * Provide the view for the monk_change_nav_menu_fields function
 *
 * @since      0.3.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$response = '';

?>
<div id="monk-select-menu-to-edit-groups">
<?php
foreach ( $monk_ids as $monk_id ) : ?>
	<?php
	$monk_translations = get_option( 'monk_menu_translations_' . $monk_id, array() );
	$monk_id_obj       = get_term( $monk_id );
	if ( array_key_exists( $default_language, $monk_translations ) ) {
		$monk_id_obj = get_term( $monk_translations[ $default_language ] );
	}
	?>
	<?php /* translators: This is a label to display the translations group */ ?>
	<optgroup label="<?php echo esc_attr( sprintf( __( 'Translations of %s', 'monk' ), $monk_id_obj->name ) ); ?>">
		<?php foreach ( $monk_translations as $nav_menu_id ) : ?>
			<?php $nav_menu = get_term( $nav_menu_id ); ?>
			<option value="<?php echo esc_attr( $nav_menu_id ); ?>" <?php selected( $current_id, $nav_menu_id ); ?>><?php echo esc_html( $nav_menu->name ); ?></option>
		<?php endforeach; ?>
	</optgroup>
<?php endforeach; ?>
</div>

<?php if ( 'locations' === filter_input( INPUT_GET, 'action' ) ) : ?>
	<?php foreach ( $current_menus as $location => $current_location_id ) : ?>
		<?php if ( array_key_exists( $location, $registered_menus ) ) : ?>
			<?php $response = $response . 'locations-' . $location . '/'; ?>
			<select id="<?php echo esc_attr( sprintf( 'monk-locations-%s', $location ) ); ?>">
				<option value="0" <?php selected( 0, $menus[ $location ] ); ?>><?php esc_html_e( '— Select a Menu —' ); ?></option>
				<?php foreach ( $monk_ids as $monk_id ) : ?>
					<?php
					$monk_translations = get_option( 'monk_menu_translations_' . $monk_id, array() );
					?>
					<?php foreach ( $monk_translations as $nav_menu_language => $nav_menu_id ) : ?>
						<?php if ( $nav_menu_language === $default_language ) : ?>
							<?php
							$nav_menu = get_term( $nav_menu_id );
							?>
							<option value="<?php echo esc_attr( $nav_menu_id ); ?>" <?php selected( $nav_menu_id, $menus[ $location ] ); ?>><?php echo esc_html( $nav_menu->name ); ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</select>
		<?php endif; ?>
	<?php endforeach; ?>
	<input type="hidden" id="monk-menu-locations" value="<?php echo esc_attr( $response ); ?>">
<?php endif; ?>

<?php
$current_menu_id           = empty( filter_input( INPUT_GET, 'menu' ) ) || 'delete' === filter_input( INPUT_GET, 'action' ) ? get_user_option( 'nav_menu_recently_edited' ) : filter_input( INPUT_GET, 'menu' );
$current_menu_language     = get_term_meta( $current_menu_id, '_monk_menu_language', true );
$current_monk_id           = get_term_meta( $current_menu_id, '_monk_menu_translations_id', true );
$current_menu_translations = get_option( 'monk_menu_translations_' . $current_monk_id, array() );
$current_monk_id           = get_term( $current_monk_id );

if ( array_key_exists( $default_language, $current_menu_translations ) ) {
	$current_monk_id = get_term( $current_menu_translations[ $default_language ] );
}

$current_monk_id_name      = $current_monk_id->name;
?>
<?php if ( array_key_exists( $default_language, $current_menu_translations ) && $current_menu_language !== $default_language ) : ?>
	<?php /* translators: This is a message to display these are translations of the menu beiing edited */ ?>
	<div id="monk-menu-translation-message"><?php echo esc_html( sprintf( __( 'This is a translation of "%s"!', 'monk' ), $current_monk_id_name ) ); ?></div>
<?php
endif;
