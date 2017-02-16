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

?>
<?php if ( ! $language ) : ?>
	<?php $language = get_post_meta( $_REQUEST['post_id'], '_monk_post_language', true ); ?>
<?php endif; ?>
<?php echo esc_html( $monk_languages[ $language ]['name'] ); ?>
<input type="hidden" name="<?php echo sprintf( 'attachments[%d][language]', $post_id ); ?>" id="<?php echo sprintf( 'attachments[%d][language]', $post_id ); ?>" value="<?php echo esc_attr( $language ); ?>">
