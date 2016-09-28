<?php
/**
 * Monk Language Switcher.
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

if ( isset( $_GET['lang'] ) ) {
	$select_value = $_GET['lang'];
}

$flag = ! empty( $instance['flag'] ) ? '1' : '0';
$name = ! empty( $instance['name'] ) ? '1' : '0';
$lang = ! empty( $instance['lang-native'] ) ? '1' : '0';

if ( $lang ){
	$languages = array(
		'pt-br' => __( 'Português', 'monk'),
		'en'    => __( 'English', 'monk' ),
		'es'    => __( 'Español', 'monk' ),
		'fr'    => __( 'Français', 'monk' ),
	);
} else {
	$languages = array(
		'pt-br' => __( 'Portuguese', 'monk' ),
		'en'    => __( 'English', 'monk' ),
		'es'    => __( 'Spanish', 'monk' ),
		'fr'    => __( 'French', 'monk' ),
	);
}

$languages_flag = array(
	'pt-br' => 'http://image.flaticon.com/icons/svg/206/206597.svg',
	'en'    => 'http://image.flaticon.com/icons/svg/206/206626.svg',
	'es'    => 'http://image.flaticon.com/icons/svg/206/206724.svg',
	'fr'    => 'http://image.flaticon.com/icons/svg/206/206657.svg',
);

/**
 * Show the flag, name or flag and name in english or native language in monk language switcher on public view
 *
 * @param bool  $flag
 * @param bool  $name
 * @param array $flag_list
 * @param array $lang_list
 * @param string $key
 */
function test( $flag, $name, $flag_list, $lang_list, $key ) {
	if ( $flag && $name) {
		return '<a class="selector-link" href="?lang=' . esc_attr( $key, 'monk' ) . '">' . $lang_list . '<img class="monk-selector-flag" src="' . $flag_list . '" alt=""></a>';
	} elseif ( $flag && !$name ) {
		return '<a class="selector-link" href="?lang=' . esc_attr( $key, 'monk' ) . '"><span class="screen-reader-text">' . $lang_list . '</span><img class="monk-selector-flag" src="' . $flag_list . '" alt=""></a>';
	} else {
		return '<a class="selector-link" href="?lang=' . esc_attr( $key, 'monk' ) . '">' . $lang_list . '</a>';
	}
}
?>

<ul class="selector">
	<?php if ( isset( $select_value ) ) : ?>
		<li class="options active-lang">
			<?php echo test( $flag, $name, $languages_flag[$select_value], $languages[$select_value], $select_value ); ?>
		</li>
	<?php endif; ?>
	<?php foreach ( $languages as $key => $value ) : ?>
		<?php if ( strcmp( $key, $select_value ) != 0 ) : ?>
			<li class="options">
				<?php echo test( $flag, $name, $languages_flag[$key], $value, $key ); ?>
			</li>
		<?php else : ?>
			<li class="options actual-lang">
				<?php echo test( $flag, $name, $languages_flag[$key], $value, $key ); ?>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
