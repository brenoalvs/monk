<?php

/**
 * Provide the view for the monk_translation_list_render function
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

	$options = get_option( 'monk_translation_list' );
?>
		<fieldset>
			<label for="monk-ptbr">
				<?php esc_html_e( 'Portuguese( Brazil )', 'monk' ); ?>
			</label>
			<input type="checkbox" name="monk_translation_list[pt_BR]" id="monk-ptbr" value="1"<?php
				if ( isset( $options['pt_BR'] ) ) {
					checked( $options['pt_BR'] == '1' );
				} else {
					$options['pt_BR'] = '0';
				}
				?>
			/>

			<label for="monk-enus">
				<?php esc_html_e( 'English', 'monk' ); ?>
			</label>
			<input type="checkbox" name="monk_translation_list[en_US]" id="monk-enus" value="1"<?php
				if ( isset( $options['en_US'] ) ) {
					checked( $options['en_US'] == '1' );
				} else {
					$options['en_US'] = '0';
				}
				?>
			/>

			<label for="monk-eses">
				<?php esc_html_e( 'Spanish', 'monk' ); ?>
			</label>
			<input type="checkbox" name="monk_translation_list[es_ES]" id="monk-eses" value="1"<?php
				if ( isset( $options['es_ES'] ) ) {
					checked( $options['es_ES'] == '1' );
				} else {
					$options['es_ES'] = '0';
				}
				?>
			/>

			<label for="monk-frfr">
				<?php esc_html_e( 'French', 'monk' ); ?>
			</label>
			<input type="checkbox" name="monk_translation_list[fr_FR]" id="monk-frfr" value="1"<?php
				if ( isset( $options['fr_FR'] ) ) {
					checked( $options['fr_FR'] == '1' );
				} else {
					$options['fr_FR'] = '0';
				}
				?>
			/>

			<label for="monk-itit">
				<?php esc_html_e( 'Italian', 'monk' ); ?>
			</label>
			<input type="checkbox" name="monk_translation_list[it_IT]" id="monk-itit" value="1"<?php
				if ( isset( $options['it_IT'] ) ) {
					checked( $options['it_IT'] == '1' );
				} else {
					$options['it_IT'] = '0';
				}
				?>
			/>
		</fieldset>
