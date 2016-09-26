<?php

/**
 * Provide the view for the monk_options function
 *
 * @link       https://github.com/brenoalvs/monk
 * @since      1.0.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */
?>
	<div class='wrap'>
		<h2>Monk</h2>
		<form action="options.php" method="POST">
			<?php
			settings_fields( 'generalOptions' );
			do_settings_sections( 'generalOptions' );
			submit_button();
			?>
		</form>
	</div>
<?php
