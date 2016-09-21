<?php
$languages_nat = array(
	'portuguese'	=> 'Português',
	'english'		=> 'English',
	'spanish'		=> 'Español',
	'french'		=> 'Français',
);
$languages_eng = array(
	'portuguese'	=> 'Portuguese',
	'english'		=> 'English',
	'spanish'		=> 'Spanish',
	'french'		=> 'French',
);
$languages_flag = array(
	'portuguese'	=> 'http://image.flaticon.com/icons/svg/206/206597.svg',
	'english'		=> 'http://image.flaticon.com/icons/svg/206/206626.svg',
	'spanish'		=> 'http://image.flaticon.com/icons/svg/206/206724.svg',
	'french'		=> 'http://image.flaticon.com/icons/svg/206/206657.svg',
);
?>
<form name="">
	<select id="widget-language-select">
	<?php foreach ($languages_eng as $key => $value) : ?>
		<option data-class="<?php echo esc_attr( 'widget-option' ); ?>" data-style="background-image: url( '<?php echo $languages_flag[$key]; ?>' );"><?php echo _e( $value ); ?></option>
	<?php endforeach; ?>
	</select>
</form>
