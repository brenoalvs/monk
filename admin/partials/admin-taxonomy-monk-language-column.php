<?php
/**
 * Show flags in Languages column on taxonomies list.
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
	<span class="hide-if-no-js dashicons dashicons-edit"></span>
	<div class="monk-hide monk-column-translations-arrow"></div>
	<div class="hide-if-js monk-column-translations monk-column-translations-terms">
<?php
foreach ( $taxonomies as $taxonomy ) {
	if ( isset( $_GET['taxonomy'] ) ) {
		if ( $_GET['taxonomy'] === $taxonomy ) {
			$base_url     = admin_url( 'term.php?taxonomy=' . $taxonomy );
			$new_term_url = add_query_arg( array(
					'monk_term_id' => $monk_term_translations_id,
			), admin_url( 'edit-tags.php?taxonomy=' . $taxonomy ) );
		}
	}
}

if ( $monk_term_translations ) :
	$translation_term_url = add_query_arg( array(
		'tag_ID' => $term_id,
	), $base_url );
?>
	<a class="monk-translation-link monk-language" href="<?php echo esc_url( $translation_term_url ); ?>">
		<span class="monk-language-name"><?php echo esc_html( $monk_languages[ $monk_language ]['name'] ); ?></span>
		<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $monk_language ); ?>"></span>
	</a>
	<?php foreach ( $languages as $language ) :
		foreach ( $monk_term_translations as $translation_code => $translation_id ) :
			if ( $language === $translation_code && $translation_code !== $monk_language ) :
				$translation_term_url = add_query_arg( array(
					'tag_ID' => $translation_id,
				), $base_url );
				?>
					<a class="monk-translation-link <?php if ( $translation_code === $monk_language ) : ?>monk-language<?php endif; ?>" href="<?php echo esc_url( $translation_term_url ); ?>">
						<span class="monk-language-name"><?php echo esc_html( $monk_languages[ $translation_code ]['name'] ); ?></span>
						<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $translation_code ); ?>"></span>
					</a>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endforeach; ?>
	<a class="monk-new-post-link" href="<?php echo esc_url( $new_term_url ); ?>">Add +</a>
<?php
	else : ?>
	<span class="dashicons dashicons-minus"></span>
<?php endif; ?>
</div>
