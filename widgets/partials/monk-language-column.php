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

$languages        = get_post_meta( $post_ID, '_monk_languages' );
$default_language = get_option( 'monk_default_language' );

$languages_flag = array(
	'da_DK' => 'flag-icon-dk',
	'en_US' => 'flag-icon-us',
	'fr_FR' => 'flag-icon-fr',
	'de_DE' => 'flag-icon-de',
	'it_IT' => 'flag-icon-it',
	'ja'    => 'flag-icon-jp',
	'pt_BR' => 'flag-icon-br',
	'ru_RU' => 'flag-icon-ru',
	'es_ES' => 'flag-icon-es',
);

$base_url = admin_url( 'post.php?action=edit' );

if ( $languages ) :
?>
	<?php
		foreach ( $languages as $language ) :
			$post_url = add_query_arg( array(
				'post' => $post_ID,
				'lang' => $language,
			), $base_url );
	?>
	<a href="<?php echo esc_url( $post_url ); ?>">
		<span class="monk-selector-flag flag-icon <?php echo esc_attr( $languages_flag[$language] ); ?>"></span>
	</a>
	<?php endforeach; ?>
<?php 
	else: 
		$post_url = add_query_arg( array(
				'post' => $post_ID,
				'lang' => $default_language,
			), $base_url );
?>
	<a href="<?php echo esc_url( $post_url ); ?>">
		<span class="monk-selector-flag flag-icon <?php echo esc_attr( $languages_flag[$default_language] ); ?>"></span>
	</a>
<?php endif; ?>
