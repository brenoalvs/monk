<?php
/**
 * Show flags in Languages column on posts list.
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
$monk_satan_id    = get_post_meta( $post_id, '_monk_post_translations_id', true );
$monk_satan       = get_option( 'monk_post_translations_' . $monk_satan_id );
$default_language = get_option( 'monk_default_language' );
$base_url         = admin_url( 'post.php?action=edit' );
$active_languages = get_option( 'monk_active_languages' );

if ( $monk_satan ) :
	foreach ( $active_languages as $lang ) :
		foreach ( $monk_satan as $lang_code => $post_id ) :
			if ( $lang === $lang_code && $lang_code === $default_language ) :
				$post_url = add_query_arg( array(
					'post' => $post_id,
				), $base_url );
				?>
				<a href="<?php echo esc_url( $post_url ); ?>">
					<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $lang_code ); ?>"></span>
				</a>
			<?php elseif ( $lang === $lang_code && $lang_code !== $default_language ) :
				$post_url = add_query_arg( array(
					'post' => $post_id,
				), $base_url );
				?>
				<a href="<?php echo esc_url( $post_url ); ?>">
					<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $lang_code ); ?>"></span>
				</a>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endforeach; ?>
<?php
	else :
		$post_url = add_query_arg( array(
				'post' => $post_id,
		), $base_url );
?>
	<a href="<?php echo esc_url( $post_url ); ?>">
		<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $default_language ); ?>"></span>
	</a>
<?php endif; ?>
