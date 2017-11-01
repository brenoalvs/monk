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

foreach ( $monk_languages as $lang_code => $list ) {
	if ( $list['slug'] === $current_language ) {
		$current_language = $lang_code;
		$current_slug = $list['slug'];
	}
	$monk_languages_reverted[ $list['slug'] ] = $list;
}
var_dump( $default_language );

echo $args['before_widget'];
?>
	<?php echo $args['before_title'] . esc_html( $title ) . $args['after_title']; ?>
	<div id="monk-language-switcher">
		<div class="monk-current-lang">
			<span class="monk-dropdown-arrow"></span>
			<span class="monk-current-lang-name">
					<?php if ( ! $flag ) : ?>
						<span class="monk-language-flag flag-icon <?php echo esc_attr( 'flag-icon-' . strtolower( $current_language ) ); ?>"></span>
					<?php endif; ?>
						<span class="monk-language-name"><?php echo esc_html( $monk_languages[ $current_language ]['native_name'] ); ?></span>
			</span>
		</div>
		<ul class="monk-language-dropdown">
			<?php if ( empty( $switchable_languages ) ) : ?>
				<li class="monk-lang">
					<option>
					<?php
						/* translators: This is a message that says a content has no translations */
						esc_html_e( 'No other translations', 'monk' );
					?>
					</option>
				</li>
			<?php else : ?>
				<?php foreach ( $switchable_languages as $code => $url ) : ?>
					<?php if ( $code !== $monk_languages[ $current_language ]['slug'] ) : ?>
						<?php $locale = monk_get_locale_by_slug( $code ); ?>
						<li class="monk-lang">
							<a class="monk-language-link" href="<?php echo esc_url( $url ); ?>">
								<?php if ( ! $flag ) : ?>
									<span class="monk-language-flag flag-icon <?php echo esc_attr( 'flag-icon-' . strtolower( $locale ) ); ?>"></span>
								<?php endif; ?>
									<span class="monk-language-name"><?php echo esc_html( $monk_languages_reverted[ $code ]['native_name'] ); ?></span>
							</a>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
		<?php if ( $monk_love ) : ?>
	</div>
	<div class="monk-love">
		<?php
			/* translators: This is a message to say the user is with us */
			printf( esc_html__( 'Made with %1$s by %2$s', 'monk' ), '<span class="dashicons dashicons-heart monk-heart" aria-hidden="true"></span>', '<a href="https://github.com/brenoalvs/monk" title="Monk">Monk</a>' );
		?>
	</div>
	<?php endif; ?>
<?php echo $args['after_widget']; ?>
