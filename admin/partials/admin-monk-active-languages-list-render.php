<?php
/**
 * Provide the view for the monk_active_languages_list_render function
 *
 * @since      0.8.0
 *
 * @package    Monk
 * @subpackage Monk/Admin/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$monk_languages = monk_get_available_languages();

$active_languages = $this->active_languages;
$default_language = $this->default_language;
?>
<fieldset>
<?php
foreach ( $active_languages as $lang_code ) :
	$id = sanitize_title( $lang_code );
?>
<label class="monk-label">
	<?php echo esc_html( $monk_languages[ $lang_code ]['english_name'] ); ?>
	<span class="monk-description">
	<?php echo esc_html( $monk_languages[ $lang_code ]['native_name'] ); ?>
	</span>
</label>
<?php endforeach; ?>
</fieldset>
<?php
