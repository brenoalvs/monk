<?php
/**
 * Provides the view for monk_add_commentt_language_selector function.
 *
 * @since      0.7.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<div class="monk-language-filter-elements alignleft actions">
	<select name="lang" id="monk-language-filter">
		<option value="all"><?php esc_html_e( 'All Languages', 'monk' ); ?></option>
		<?php foreach ( $languages as $language ) : ?>
			<option value="<?php echo esc_attr( $language ); ?>"
				<?php
				if ( isset( $url_language ) && ! empty( $url_language ) ) {
					$monk_language_filter = sanitize_text_field( wp_unslash( $url_language ) );
					selected( $monk_language_filter, $language );
				} elseif ( ! isset( $url_language ) ) {
					selected( $this->default_language, $language );
				}
				?>
			>
				<?php
				echo esc_html( $monk_languages[ $language ]['english_name'] );
				?>
			</option>
		<?php endforeach; ?>
	</select>
</div>
