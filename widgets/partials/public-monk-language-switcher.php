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
 * Show the flag in monk language switcher on public view
 *
 * @param bool  $flag
 * @param array $flag_list
 */
function insert_flags( $flag, $flag_list ) {
	if ( $flag ) {
		return 'data-style="background-image: url( ' . $flag_list . ' );"';
	} else {
		return 'data-style="background-image: none;';
	}
};

/**
 * Show the name of language in monk language switcher on public view
 *
 * @param bool  $name
 */
function insert_names( $name ) {
	if ( ! $name ) {
		return 'screen-reader-text';	
	}
};
?>
<form name="form-language" id="monk-form-language" method="get" action="<?php home_url(); ?>">
	<select id="monk-widget-language-selector" name="lang" value="<?php echo $select_value; ?>">
		<?php if ( isset( $select_value ) ) : ?>
			<option data-class="monk-widget-option <?php echo insert_names( $name ); ?>" <?php echo insert_flags( $flag, $languages_flag[$select_value] ); ?> value="<?php echo esc_attr_e( $select_value, 'monk' ); ?>"><?php echo $languages[$select_value]; ?></option>
		<?php endif; ?>
	<?php foreach ( $languages as $key => $value ) : ?>
		<?php if ( strcmp( $key, $select_value ) != 0 ) : ?>
			<option data-class="monk-widget-option <?php echo insert_names( $name ); ?>" <?php echo insert_flags( $flag, $languages_flag[$key] ); ?> value="<?php echo esc_attr_e( $key, 'monk' ); ?>"><?php echo $value; ?></option>
		<?php endif; ?>
	<?php endforeach; ?>
	</select>
</form>
