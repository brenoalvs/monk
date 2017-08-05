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
<div class="hide-if-no-js" id="monk-select-menu-to-edit-groups">
<?php foreach ( $monk_ids as $monk_id ) : ?>
	<?php
	$monk_translations = get_option( 'monk_menu_translations_' . $monk_id, array() );
	$monk_id_obj       = get_term( $monk_id );
	if ( empty( $monk_id_obj ) ) {
		if ( array_key_exists( $default_language, $monk_translations ) ) {
			$monk_id_obj = get_term( $monk_translations[ $default_language ] );
		} else {
			$monk_id_obj = get_term( reset( $monk_translations ) );
		}
	}
	$has_language = ! empty( get_term_meta( $monk_id_obj->term_id, '_monk_menu_language', true ) ) ? true : false;
	?>
	<optgroup label="
	<?php
		/* translators: This is a label to display the translations group */
		echo esc_attr( sprintf( __( 'Translations of %s', 'monk' ), $monk_id_obj->name ) );
	?>
	">
		<?php if ( $has_language ) : ?>
			<?php foreach ( $monk_translations as $nav_menu_id ) : ?>
				<?php $nav_menu = get_term( $nav_menu_id ); ?>
				<option value="<?php echo esc_attr( $nav_menu_id ); ?>" <?php selected( $current_id, $nav_menu_id ); ?>><?php echo esc_html( $nav_menu->name ); ?></option>
			<?php endforeach; ?>
		<?php else : ?>
			<option value="<?php echo esc_attr( $monk_id_obj->term_id ); ?>" <?php selected( $monk_id_obj->term_id, get_user_option( 'nav_menu_recently_edited' ) ); ?>><?php echo esc_html( $monk_id_obj->name ); ?></option>
		<?php endif; ?>
	</optgroup>
<?php endforeach; ?>
</div>
<?php $locations_tab = 'locations' === filter_input( INPUT_GET, 'action' ) ? true : false; ?>
<?php if ( $locations_tab ) : ?>
	<?php foreach ( $current_menus as $location => $current_location_id ) : ?>
		<?php if ( array_key_exists( $location, $registered_menus ) ) : ?>
			<?php $response = $response . 'locations-' . $location . '/'; ?>
			<select class="hide-if-no-js" id="<?php echo esc_attr( sprintf( 'monk-locations-%s', $location ) ); ?>">
				<option value="0" <?php selected( 0, $menus[ $location ] ); ?>><?php esc_html_e( '— Select a Menu —' ); ?></option>
				<?php foreach ( $monk_ids as $monk_id ) : ?>
					<?php
					$monk_translations = get_option( 'monk_menu_translations_' . $monk_id, array() );
					if ( ! empty( $monk_translations ) ) :
					?>
						<?php foreach ( $monk_translations as $nav_menu_language => $nav_menu_id ) : ?>
							<?php if ( $nav_menu_language === $default_language ) : ?>
								<?php
								$nav_menu = get_term( $nav_menu_id );
								?>
								<option value="<?php echo esc_attr( $nav_menu_id ); ?>" <?php selected( $nav_menu_id, $menus[ $location ] ); ?>><?php echo esc_html( $nav_menu->name ); ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php else : ?>
						<?php $nav_menu = get_term( $monk_id ); ?>
						<option value="<?php echo esc_attr( $monk_id ); ?>" <?php selected( $monk_id, $menus[ $location ] ); ?>><?php echo esc_html( $nav_menu->name ); ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
		<?php endif; ?>
	<?php endforeach; ?>
	<input class="hide-if-no-js" type="hidden" id="monk-menu-locations" value="<?php echo esc_attr( $response ); ?>">
<?php endif; ?>

<?php
$menu_id           = empty( $menu ) || 'delete' === filter_input( INPUT_GET, 'action' ) ? get_user_option( 'nav_menu_recently_edited' ) : $menu;

if ( is_nav_menu( $menu_id ) ) :
	$menu_language     = get_term_meta( $menu_id, '_monk_menu_language', true );
	$monk_id           = get_term_meta( $menu_id, '_monk_menu_translations_id', true );
	$menu_translations = get_option( 'monk_menu_translations_' . $monk_id, array() );

	if ( array_key_exists( $default_language, $menu_translations ) ) {
		$menu = get_term( $menu_translations[ $default_language ] );
	} else {
		$menu = get_term( $menu_id );
	}

	$menu_name      = $menu->name;
	?>
	<?php if ( array_key_exists( $default_language, $menu_translations ) && $menu_language !== $default_language ) : ?>
		<?php if ( array_key_exists( $default_language, $menu_translations ) ) : ?>
			<?php $menu = $menu_translations[ $default_language ]; ?>
		<?php else : ?>
			<?php $menu = ! empty( get_term_meta( $current_id, '_monk_menu_translations_id', true ) ) ? get_term_meta( $current_id, '_monk_menu_translations_id', true ) : $current_id; ?>
		<?php endif; ?>

		<?php if ( ! $locations_tab ) : ?>
			<div class="hide-if-no-js" id="monk-menu-translation-message">
			<?php $locations = array_keys( $menus, $menu ); ?>
			<?php if ( $locations ) : ?>
				<?php foreach ( $locations as $location ) : ?>
					<?php if ( array_key_exists( $location, $registered_menus ) ) : ?>
						<div><?php echo esc_html( $registered_menus[ $location ] ); ?></div>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php else : ?>
				<div><?php esc_html_e( 'None', 'monk' ); ?></div>
			<?php endif; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
