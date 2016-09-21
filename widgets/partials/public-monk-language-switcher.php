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
	'portuguese'	=> 'http://www.flaticon.com/free-icon/brazil_206597',
	'english'		=> 'http://www.flaticon.com/free-icon/united-states_206626',
	'spanish'		=> 'http://www.flaticon.com/free-icon/spain_206724',
	'french'		=> 'http://www.flaticon.com/free-icon/france_206657',
);
?>
<select>
<?php foreach ($languages_eng as $key => $value) : ?>
	<option><img src="<?php echo $languages_flag[$key]; ?>"><?php echo $value; ?></option>
<?php endforeach; ?>
</select>
