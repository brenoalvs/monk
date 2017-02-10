<?php
/**
 * Show flags in Languages column on posts list.
 *
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Widgets/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$attach = 'monk-attach';
?>
<select name="monk_post_translation_id">
<?php
foreach ( $active_languages as $lang_code ) {
	if ( array_key_exists( $lang_code, $monk_languages ) && ! array_key_exists( $lang_code, $post_translations ) ) {
		if ( $lang_code === $language ) :
			?>
			<option value='<?php echo esc_attr( $lang_code ); ?>' selected="selected">
				<?php echo esc_html( $monk_languages[ $lang_code ]['name'] ); ?>
			</option>
			<?php
		else :
			?>
			<option value="<?php echo esc_attr( $lang_code ); ?>">
				<?php echo esc_html( $monk_languages[ $lang_code ]['name'] )?>
			</option>;
			<?php
		endif;
	}
}
?>
</select>
<input type="hidden" name="monk_id" id="monk-id" value="<?php echo esc_attr( $monk_id ); ?>">
<input type="hidden" id="previous-post-id" value="<?php echo esc_attr( $post_id ); ?>">
<button class="button" id="<?php echo esc_attr( $attach ); ?>">
	<?php echo esc_html__( 'Ok', 'monk' ); ?>
</button>
