<?php
/**
 * Field to translate the Term.
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
<table class="form-table">
	<tbody>
		<tr class="form-field term-language">
			<th scope="row">
				<label for="monk-language"><?php esc_html_e( 'Translations', 'monk' ); ?></label>
			</th>
			<td>
				<ul>
<?php foreach ( $languages as $language ) :
	foreach ( $monk_term_translations as $translation_code => $translation_id ) :
		$translation_term_url = add_query_arg( array(
			'tag_ID'     => $translation_id,
		), $base_url_translation );

		if ( $translation_code === $language && $monk_language !== $language ) :
			?> <li>
				<a href="<?php echo esc_url( $translation_term_url ); ?>">
					<?php echo esc_html( $monk_languages[ $language ]['name'] ); ?>
				</a>
			</li>
		<?php
		endif;
	endforeach;
endforeach;
				?>
				</ul>
				<?php
				if ( $avaliable_languages ) :
					$new_term_url = add_query_arg( array(
						'monk_term_id'     => $monk_term_translations_id,
					), $base_url );
				?>
					<a href="<?php echo esc_url( $new_term_url ); ?>" class="button">Add a translation +</a>
				<?php else : ?>
					<p><?php esc_html_e( 'No Avaliable Translations!', 'monk' ); ?></p>
				<?php endif; ?>
			</td>
		</tr>
	</tbody>
</table>
