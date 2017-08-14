<?php
/**
 * Provide the view for the monk_site_description_render function
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
<input type="text" name="blogdescription" value="<?php echo esc_attr( $site_description ); ?>" class="regular-text"><span class="monk-language-flag flag-icon <?php echo esc_attr( 'flag-icon-' . strtolower( $default_language ) ); ?>"></span><br>
<?php foreach ( $active_languages as $lang ) : ?>
	<?php if ( $lang !== $default_language ) : ?>
		<?php $site_description = get_option( 'monk_' . $lang . '_blogdescription', '' ); ?>
		<input type="text" name="<?php echo esc_attr( 'monk_' . $lang . '_blogdescription' ); ?>" value="<?php echo esc_attr( $site_description ); ?>" placeholder="<?php echo esc_attr( $monk_languages[ $lang ]['native_name'] ); ?>" class="regular-text"><span class="monk-language-flag flag-icon <?php echo esc_attr( 'flag-icon-' . strtolower( $lang ) ); ?>"></span><br>
	<?php endif; ?>
<?php endforeach; ?>
