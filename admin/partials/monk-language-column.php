<?php
/**
 * Show flags in Languages column on posts list.
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Widgets/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
	<span class="hide-if-no-js dashicons dashicons-edit"></span>
	<div class="monk-hide monk-column-translations-arrow"></div>
	<div class="hide-if-js monk-column-translations">
<?php if ( $monk_translations ) : ?>
	<a class="monk-translation-link monk-language" href="<?php echo esc_url( $post_url ); ?>">
		<span class="monk-language-name"><?php echo esc_html( $monk_languages[ $monk_language ]['name'] ); ?></span>
		<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $monk_language ); ?>"></span>
	</a>
	<?php foreach ( $active_languages as $lang ) :
		foreach ( $monk_translations as $lang_code => $post_id ) :
			if ( $lang === $lang_code && $lang_code !== $monk_language ) :
				$post_url = add_query_arg( array(
					'post' => $post_id,
				), $base_url );
				?>
				<a class="monk-translation-link <?php if ( $lang_code === $monk_language ) : ?>monk-language<?php endif; ?>" href="<?php echo esc_url( $post_url ); ?>">
					<span class="monk-language-name"><?php echo esc_html( $monk_languages[ $lang ]['name'] ); ?></span>
					<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $lang ); ?>"></span>
				</a>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endforeach; ?>
	<?php if ( $available_languages ) : ?>
		<?php $is_attachment = 'attachment' === $post_type ? 'monk-attach' : ''; ?>
		<?php if ( 'monk-attach' === $is_attachment ) : ?>
			<input type="hidden" id="monk-id" value="<?php echo esc_attr( $monk_translations_id ); ?>">
		<?php endif; ?>
		<a class="monk-new-translation-link" id="<?php echo esc_attr( $is_attachment ); ?>" href="<?php echo esc_url( $new_post_url ); ?>"><?php esc_html_e( 'Add+', 'monk' ) ?></a>
	<?php endif; ?>
<?php else : ?>
	<span class="dashicons dashicons-minus"></span>
<?php endif; ?>
</div>
