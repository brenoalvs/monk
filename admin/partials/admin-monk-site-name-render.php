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
<input type="text" name="blogname" value="<?php echo esc_attr( $site_name ); ?>" class="regular-text"><span class="monk-language-flag flag-icon flag-icon-<?php echo esc_attr( $default_slug ); ?>"></span><br>
<?php foreach ( $active_languages as $lang ) : ?>
	<?php if ( $lang !== $default_language ) : ?>
		<?php $site_name = get_option( 'blogname_' . $lang, '' ); ?>
		<input type="text" name="<?php echo esc_attr( 'blogname_' . $lang ); ?>" value="<?php echo esc_attr( $site_name ); ?>" placeholder="<?php echo esc_attr( $monk_languages[ $lang ]['native_name'] ); ?>" class="regular-text"><span class="monk-language-flag flag-icon flag-icon-<?php echo esc_attr( $monk_languages[ $lang ]['slug'] ); ?>"></span><br>
	<?php endif; ?>
<?php endforeach; ?>
