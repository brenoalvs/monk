<?php
/**
 * Provide the view for the monk_site_name_render function
 *
 * @since      0.5.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<input type="text" name="blogname" value="<?php echo esc_attr( $site_name ); ?>" class="regular-text"><span class="monk-language-flag flag-icon flag-icon-<?php echo esc_attr( $default_slug ); ?>" aria-label="<?php echo esc_attr( $monk_languages[ $default_language ]['native_name'] ); ?>" title="<?php echo esc_attr( $monk_languages[ $default_language ]['native_name'] ); ?>"></span><br>
<?php foreach ( $active_languages as $lang ) : ?>
	<?php if ( $lang !== $default_language ) : ?>
		<?php $site_name = get_option( 'monk_' . $lang . '_blogname', '' ); ?>
		<input type="text" name="<?php echo esc_attr( 'monk_' . $lang . '_blogname' ); ?>" value="<?php echo esc_attr( $site_name ); ?>" placeholder="<?php echo esc_attr( $monk_languages[ $lang ]['native_name'] ); ?>" class="regular-text"><span class="monk-language-flag flag-icon flag-icon-<?php echo esc_attr( $monk_languages[ $lang ]['slug'] ); ?>" aria-label="<?php echo esc_attr( $monk_languages[ $lang ]['native_name'] ); ?>" title="<?php echo esc_attr( $monk_languages[ $lang ]['native_name'] ); ?>"></span><br>
	<?php endif; ?>
<?php endforeach; ?>
