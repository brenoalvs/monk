<?php
/**
 * Monk Language Column.
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

$languages        = get_post_meta( $post_ID, '_monk_post_language' );
$monk_satan_id    = get_post_meta( $post_ID, '_monk_post_translations_id', true );
$monk_satan       = get_option( 'monk_post_translations_' . $monk_satan_id );
$default_language = get_option( 'monk_default_language' );
$base_url         = admin_url( 'post.php?action=edit' );

if ( $monk_satan ) :
?>
	<?php
		foreach ( $monk_satan as $lang_code => $id ) :
			$post_url = add_query_arg( array(
				'post' => $id,
			), $base_url );
	?>
	<a href="<?php echo esc_url( $post_url ); ?>">
		<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $lang_code ); ?>"></span>
	</a>
	<?php endforeach; ?>
<?php 
	else: 
		$post_url = add_query_arg( array(
				'post' => $post_ID,
			), $base_url );
?>
	<a href="<?php echo esc_url( $post_url ); ?>">
		<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $default_language ); ?>"></span>
	</a>
<?php endif; ?>