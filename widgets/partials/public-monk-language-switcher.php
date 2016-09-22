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
	'portuguese' => 'Português',
	'english'    => 'English',
	'spanish'    => 'Español',
	'french'     => 'Français',
);

$languages_eng = array(
	'portuguese' => 'Portuguese',
	'english'    => 'English',
	'spanish'    => 'Spanish',
	'french'     => 'French',
);

$languages_flag = array(
	'Portuguese' => 'http://image.flaticon.com/icons/svg/206/206597.svg',
	'English'    => 'http://image.flaticon.com/icons/svg/206/206626.svg',
	'Spanish'    => 'http://image.flaticon.com/icons/svg/206/206724.svg',
	'French'     => 'http://image.flaticon.com/icons/svg/206/206657.svg',
);

$select_value = $_GET['lang'];

?>
<form name="form-language" id="form-language" method="get" action="<?php home_url(); ?>">
	<select id="widget-language-select" name="lang" value="<?php echo $select_value; ?>">
		<option data-class="widget-option" data-style="background-image: url( '<?php echo $languages_flag[$select_value]; ?>' );" value="<?php echo esc_attr_e( $select_value, 'monk' ); ?>"><?php echo _e( $select_value, 'monk' ); ?></option>
	<?php foreach ( $languages_eng as $key => $value ) : ?>
		<?php if ( strcmp( $value, $select_value ) != 0 ) : ?>
			<option data-class="widget-option" data-style="background-image: url( '<?php echo $languages_flag[$value]; ?>' );" value="<?php echo esc_attr_e( $value, 'monk' ); ?>"><?php echo _e( $value, 'monk' ); ?></option>
		<?php endif; ?>
	<?php endforeach; ?>
	</select>
</form>
