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
<div class="monk-column-translations">
	<?php if ( $monk_language ) : ?>
		<div class="monk-flag-wrapper">
			<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $monk_languages[ $monk_language ]['slug'] ); ?>"></span>
		</div>
		<?php if ( $available_languages ) : ?>
			<div class="monk-button-wrapper">
				<?php $is_attachment = 'attachment' === $post_type ? 'monk-attach' : ''; ?>
				<?php if ( 'monk-attach' === $is_attachment ) : ?>
					<input type="hidden" class="monk-id" value="<?php echo esc_attr( $monk_translations_id ); ?>">
					<input type="hidden" class="current-post-id" value="<?php echo esc_attr( $post_id ); ?>">
				<?php endif; ?>
				<a class="monk-new-translation-link button <?php echo esc_attr( $is_attachment ); ?>" href="<?php echo esc_url( $new_url ); ?>"><?php esc_html_e( 'Add+', 'monk' ); ?></a>
			</div>
		<?php endif; ?>
	<?php else : ?>
		<span aria-hidden="true">—</span>
		<span class="screen-reader-text"><?php esc_html_e( 'No language', 'monk' ); ?></span>
	<?php endif; ?>
</div>
