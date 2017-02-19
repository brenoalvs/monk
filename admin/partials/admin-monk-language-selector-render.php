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
<select name="monk_post_translation_id" class="monk-lang">
<?php
foreach ( $active_languages as $lang_code ) {
	if ( array_key_exists( $lang_code, $monk_languages ) && $language_code !== $lang_code && ! array_key_exists( $lang_code, $post_translations ) ) {
		if ( $lang_code === $language ) :
			?>
			<option value='<?php echo esc_attr( $lang_code ); ?>' selected="selected">
				<?php echo esc_html( $monk_languages[ $lang_code ]['name'] ); ?>
			</option>
			<?php
		elseif ( ! $language && $lang_code === $default_language ) :
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

<input type="hidden" name="monk_id" class="monk-id" value="<?php echo esc_attr( $monk_id ); ?>">
<input type="hidden" class="previous-post-id" value="<?php echo esc_attr( $post_id ); ?>">
<button class="button <?php echo esc_attr( $attach ); ?>">
	<?php echo esc_html__( 'Ok', 'monk' ); ?>
</button>
