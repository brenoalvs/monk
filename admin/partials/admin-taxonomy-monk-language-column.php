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

foreach ( $taxonomies as $taxonomy ) {
	if ( isset( $_GET['taxonomy'] ) ) {
		if ( $_GET['taxonomy'] === $taxonomy ) {
			$base_url = admin_url( 'term.php?taxonomy=' . $taxonomy );
		}
	}
}

if ( $monk_term_satan ) :
	foreach ( $languages as $language ) :
		foreach ( $monk_term_satan as $translation_code => $translation_id ) :
			if ( $language === $translation_code && $translation_code === $default_language ) :
				$translation_term_url = add_query_arg( array(
					'tag_ID' => $monk_term_satan_id,
				), $base_url );
			?>
				<a href="<?php echo esc_url( $translation_term_url ); ?>">
					<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $default_language ); ?>"></span>
				</a>
			<?php
			elseif ( $language === $translation_code && $translation_code !== $default_language ) :
				$translation_term_url = add_query_arg( array(
					'tag_ID' => $translation_id,
				), $base_url );
				?>
				<a href="<?php echo esc_url( $translation_term_url ); ?>">
					<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $translation_code ); ?>"></span>
				</a>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endforeach; ?>
<?php
	else :
		$translation_term_url = add_query_arg( array(
				'tag_ID' => $term_id,
		), $base_url );
?>
	<a href="<?php echo esc_url( $translation_term_url ); ?>">
		<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $default_language ); ?>"></span>
	</a>
<?php endif; ?>
