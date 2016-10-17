<?php
/**
 * Monk Language Filter.
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Widgets/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

$languages = get_option( 'monk_active_languages' );
?>
<select name="monk_language_filter" id="monk-language">
	<option value="">Languages</option>
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
			echo $language;
			?>
		</option>
	<?php endforeach; ?>
</select>
