<?php
/**
 * Monk Language Switcher.
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
<div id="monk-language-switcher">
	<div class="monk-current-lang">
		<span class="monk-dropdown-arrow"></span>
		<span class="monk-current-lang-name">
			<?php if ( ! $flag ) : ?>
			<span class="monk-language-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $current_slug ); ?>"></span>
			<?php endif; ?>
			<span class="monk-language-name"><?php echo esc_html( $monk_languages[ $current_language ]['native_name'] ); ?></span>
		</span>
	</div>
	<ul class="monk-language-dropdown">
		<?php foreach ( $switchable_languages as $code => $url ) : ?>
			<?php if ( $code !== $monk_languages[ $current_language ]['slug'] ) : ?>
				<li class="monk-lang">
					<a class="monk-language-link" href="<?php echo esc_url( trailingslashit( $url ) ); ?>">
						<?php if ( ! $flag ) : ?>
						<span class="monk-language-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $monk_languages_reverted[ $code ]['slug'] ) ?>"></span>
						<?php endif; ?>
						<span class="monk-language-name"><?php echo esc_html( $monk_languages_reverted[ $code ]['native_name'] ); ?></span>
					</a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
	<?php if ( get_option( 'appreciate_monk', null ) ) : ?>
		<div class="monk-love">
			<?php
				printf(
					__( 'Made with %1$s by %2$s', 'monk' ),
					'<span class="dashicons dashicons-heart monk-heart" aria-hidden="true"></span>',
					'<a href="https://github.com/brenoalvs/monk" title="Monk">Monk</a>'
				);
			?>
		</div>
	<?php endif; ?>
</div>
