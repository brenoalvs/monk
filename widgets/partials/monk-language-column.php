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
$post_url = add_query_arg( array(
	'post' => $post_ID,
	'lang' => $language,
), $base_url );
?>
<a href="<?php echo esc_url( $post_url ); ?>">
	<span class="monk-selector-flag flag-icon <?php echo esc_attr( $languages_flag[$language] ); ?>"></span>
</a>