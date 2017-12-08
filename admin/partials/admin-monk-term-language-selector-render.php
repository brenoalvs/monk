<?php
/**
 * Provides the view for monk_add_term_language_filter function.
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
$monk_languages = monk_get_available_languages();
$languages      = get_option( 'monk_active_languages' );
$url_language   = filter_input( INPUT_GET, 'lang' );

?>

<div class="monk-language-filter-elements alignleft actions">
	<select name="monk_language_filter" id="monk-language-filter">
		<option value="all"><?php esc_html_e( 'All Languages', 'monk' ); ?></option>
		<?php foreach ( $languages as $language ) : ?>
			<option value="<?php echo esc_attr( $language ); ?>"
				<?php
				if ( isset( $url_language ) && ! empty( $url_language ) ) {
					$monk_language_filter = sanitize_text_field( wp_unslash( $url_language ) );
					selected( $monk_language_filter, $language );
				} elseif ( ! isset( $url_language ) ) {
					selected( get_option( 'monk_default_language' ), $language );
				}
				?>
			>
				<?php
				echo esc_html( $monk_languages[ $language ]['english_name'] );
				?>
			</option>
		<?php endforeach; ?>
	</select>

	<input type="submit" name="filter_term_action" id="term-query-submit" class="button" value="Filter">

</div>

<!-- Necessary hidden fields -->
<input type="hidden" name="hidden_action_url" value="<?php echo esc_attr( $action_url ); ?>">
