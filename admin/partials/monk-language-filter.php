<?php
/**
 * Monk Language Filter.
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

$monk_languages = monk_get_available_languages();
$languages      = $this->active_languages;
$url_language   = filter_input( INPUT_GET, 'monk_language_filter' );
?>
<select name="monk_language_filter" id="monk-language-filter">
	<option value=""><?php esc_html_e( 'All Languages', 'monk' ); ?></option>
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
