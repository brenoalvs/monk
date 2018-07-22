<?php
/**
 * Field to translate the Term.
 *
 * @since      0.1.0
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
				<?php if ( $monk_language ) : ?>
					<ul>
						<li><?php echo esc_html( $monk_languages[ $monk_language ]['english_name'] ); ?></li>
						<?php foreach ( $monk_term_translations as $translation_code => $translation_id ) : ?>
							<?php if ( in_array( $translation_code, $languages, true ) && $monk_language !== $translation_code ) : ?>
							<li>
								<?php $translation_term_url = add_query_arg( 'tag_ID', $translation_id, $base_url_translation ); ?>
								<a href="<?php echo esc_url( $translation_term_url ); ?>">
									<?php echo esc_html( $monk_languages[ $translation_code ]['english_name'] ); ?>
								</a>
							</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>

					<?php if ( $available_languages ) : ?>
						<?php $new_term_url = add_query_arg( 'monk_id', $monk_term_translations_id, $base_url ); ?>
						<a href="<?php echo esc_url( $new_term_url ); ?>" class="button"><?php esc_html_e( 'Add a translation +', 'monk' ); ?></a>
					<?php endif; ?>
				<?php else : ?>
					<p><?php esc_html_e( 'You must set a language before add translations.', 'monk' ); ?></p>
				<?php endif; ?>
			</td>
		</tr>
	</tbody>
</table>
