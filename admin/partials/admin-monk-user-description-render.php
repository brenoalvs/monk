<?php
/**
 * Provide the view for the monk_user_description_render function
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
<span class="monk-language-flag flag-icon flag-icon-<?php echo esc_attr( $default_slug ); ?>"></span><textarea name="description" rows="5" cols="30"><?php echo esc_html( $user_description ); ?></textarea><br>
<?php foreach ( $active_languages as $lang ) : ?>
	<?php if ( $lang !== $default_language ) : ?>
		<?php $user_description = get_option( 'monk_' . $lang . '_description', '' ); ?>
		<span class="monk-language-flag flag-icon flag-icon-<?php echo esc_attr( $monk_languages[ $lang ]['slug'] ); ?>"></span><textarea name="<?php echo esc_attr( 'monk_' . $lang . '_description' ); ?>" placeholder="<?php echo esc_attr( $monk_languages[ $lang ]['native_name'] ); ?>" rows="5" cols="30"><?php echo esc_html( $user_description ); ?></textarea><br>
	<?php endif; ?>
<?php endforeach; ?>
