<?php
/**
 * Show flags in Languages column on posts list.
 *
 * @since      0.1.0
 *
 * @package    Monk
 * @subpackage Monk/Widgets/Partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<div class="monk-column-translations">
<?php if ( $available_languages ) : ?>
	<a class="monk-new-translation-link button" href="<?php echo esc_url( $new_url ); ?>"><?php esc_html_e( 'Add+', 'monk' ) ?></a>
<?php endif; ?>
<?php if ( $monk_language ) : ?>
	<div class="monk-flag-wrapper">
		<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $monk_languages[ $monk_language ]['slug'] ); ?>"></span>
	</div>
<?php endif; ?>
</div>
