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
if ( ! $language ) : ?>
	<?php if ( monk_get_url_args( 'lang' ) ) : ?>
		<?php $language = monk_get_url_args( 'lang' ); ?>
	<?php elseif ( $_REQUEST['post_id'] && get_post_meta( $_REQUEST['post_id'], '_monk_post_language', true ) ) : ?>
		<?php $language = get_post_meta( $_REQUEST['post_id'], '_monk_post_language', true ); ?>
	<?php else : ?>
		<?php $language = get_option( 'monk_default_language', false ); ?>
	<?php endif; ?>
<?php endif; ?>
<div class="monk-language-field">
	<span class="monk-language-name"><?php echo esc_html( $monk_languages[ $language ]['english_name'] ); ?></span>
	<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . strtolower( $language ) ); ?>"></span>
</div>
<input type="hidden" name="<?php echo sprintf( 'attachments[%d][language]', $post_id ); ?>" id="<?php echo sprintf( 'attachments[%d][language]', $post_id ); ?>" value="<?php echo esc_attr( $language ); ?>">
