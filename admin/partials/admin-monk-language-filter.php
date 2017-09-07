<?php
/**
 * Provide the language selector to use in medias grid mode and terms page.
 *
 * @since      0.5.2
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<select class="monk-lang">
<?php
foreach ( $active_languages as $lang_code ) {
	if ( array_key_exists( $lang_code, $monk_languages ) ) {
		if ( $lang_code === $language || ( ! $language && $lang_code === $default_language ) ) :
			?>
			<option value='<?php echo esc_attr( $lang_code ); ?>' selected="selected">
				<?php echo esc_html( $monk_languages[ $lang_code ]['english_name'] ); ?>
			</option>
			<?php
		else :
			?>
			<option value="<?php echo esc_attr( $lang_code ); ?>">
				<?php echo esc_html( $monk_languages[ $lang_code ]['english_name'] ); ?>
			</option>;
			<?php
		endif;
	}
}
?>
</select>
