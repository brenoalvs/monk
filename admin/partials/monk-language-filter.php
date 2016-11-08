<?php
/**
 * Monk Language Filter.
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

global $monk_languages;
$languages = get_option( 'monk_active_languages' );
?>
<select name="monk_language_filter" id="monk-language-filter">
	<option value=""><?php esc_html_e( 'All Languages', 'monk' ); ?></option>
	<?php foreach ( $languages as $language ) : ?>
		<option value="<?php echo esc_attr( $language ); ?>" 
			<?php 
			if ( isset( $_GET['monk_language_filter'] ) && ! empty( $_GET['monk_language_filter'] ) ) {
				selected( $_GET['monk_language_filter'], $language ); 
			} elseif ( ! isset( $_GET['monk_language_filter'] ) ) {
				selected( get_option( 'monk_default_language' ), $language );
			}
			?>>
			<?php
			echo esc_html( $monk_languages[$language]['name'] );
			?>
		</option>
	<?php endforeach; ?>
</select>
