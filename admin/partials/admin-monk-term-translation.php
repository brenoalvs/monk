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
				<label for="monk-language"><?php esc_html_e( 'Translation', 'monk' ); ?></label>
			</th>
			<td>
				<select class="postform" id="monk-term-translation" name="monk-term-translation">
					<?php
					foreach ( $languages as $language ) :
						if ( ! array_key_exists( $language, $monk_term_translations ) ) :
							$new_term_url = add_query_arg( array(
								'monk_id'     => $monk_term_translations_id,
								'translation_lang' => $language,
							), $base_url );
					?>
						<option value="<?php echo esc_url( $new_term_url ); ?>"><?php echo esc_html( $monk_languages[ $language ]['name'] ); ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
				<?php
				foreach ( $languages as $language ) :
					if ( $monk_language === $language ) :
						echo esc_html( $monk_languages[ $language ]['name'] );
					endif;
					foreach ( $monk_term_translations as $translation_code => $translation_id ) :
						$translation_term_url = add_query_arg( array(
							'tag_ID'     => $translation_id,
						), $base_url_translation );

						if ( $translation_code === $language && $monk_language !== $language ) : ?>
							<a href="<?php echo esc_url( $translation_term_url ); ?>">
								<?php echo esc_html( $monk_languages[ $language ]['name'] ); ?>
							</a>
						<?php
						endif;
					endforeach;
				endforeach;
				?>
			</td>
		</tr>
	</tbody>
</table>
