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

$languages_nat = array(
	'pt-br' => 'Português',
	'en'    => 'English',
	'es'    => 'Español',
	'fr'    => 'Français',
);

$languages_eng = array(
	'pt-br' => 'Portuguese',
	'en'    => 'English',
	'es'    => 'Spanish',
	'fr'    => 'French',
);

$languages_flag = array(
	'pt-br' => 'http://image.flaticon.com/icons/svg/206/206597.svg',
	'en'    => 'http://image.flaticon.com/icons/svg/206/206626.svg',
	'es'    => 'http://image.flaticon.com/icons/svg/206/206724.svg',
	'fr'    => 'http://image.flaticon.com/icons/svg/206/206657.svg',
);

$select_value = $_GET['lang'];
$check = ! empty( $instance['flag'] ) ? '1' : '0';

function insert_flags( $checkbox, $array, $i ) {
	if ( $checkbox ) {
		return 'data-style="background-image: url( ' . $array[$i] . ' );"';
	} else {
		return 'data-style="background-image: none;';
	}
}; 
?>
<form name="form-language" id="monk-form-language" method="get" action="<?php home_url(); ?>">
	<select id="monk-widget-language-selector" name="lang" value="<?php echo $select_value; ?>">
		<?php if ( isset( $select_value ) ) : ?>
			<option data-class="monk-widget-option" <?php echo insert_flags( $check, $languages_flag, $select_value ); ?> value="<?php echo esc_attr_e( $languages_eng[$select_value], 'monk' ); ?>"><?php echo _e( $languages_eng[$select_value], 'monk' ); ?></option>
		<?php endif; ?>
	<?php foreach ( $languages_eng as $key => $value ) : ?>
		<?php if ( strcmp( $key, $select_value ) != 0 ) : ?>
			<option data-class="monk-widget-option" <?php echo insert_flags( $check, $languages_flag, $key ); ?> value="<?php echo esc_attr_e( $key, 'monk' ); ?>"><?php echo _e( $value, 'monk' ); ?></option>
		<?php endif; ?>
	<?php endforeach; ?>
	</select>
</form>
