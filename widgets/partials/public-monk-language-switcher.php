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
	$select_value = sanitize_text_field( wp_unslash( $_GET['lang'] ) );
} else {
	$select_value = get_option( 'monk_default_language' );
}

global $monk_languages;
$title            = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'monk' );
$flag             = ! empty( $instance['flag'] ) ? true : false;
$active_languages = get_option( 'monk_active_languages' );

echo $args['before_widget'];
?>
	<?php echo $args['before_title'] . esc_html( $title ) . $args['after_title']; ?>
	<div id="monk-language-switcher">
		<div class="monk-current-lang">
			<span class="monk-dropdown-arrow"></span>
			<span class="monk-current-lang-name">
				<?php if ( ! $flag ) : ?>
					<span class="monk-language-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $select_value ); ?>"></span>
				<?php endif; ?>
					<span class="monk-language-name"><?php echo esc_html( $monk_languages[ $select_value ]['native_name'] ); ?></span>
			</span>
		</div>
		<ul class="monk-language-dropdown">
			<?php foreach ( $active_languages as $lang_code ) : ?>
				<?php if ( strcmp( $lang_code, $select_value ) !== 0 ) : ?>
					<li class="monk-lang">
						<a class="monk-language-link" href="<?php echo esc_url( add_query_arg( 'lang', esc_attr( $lang_code, 'monk' ), home_url() ) ); ?>">
							<?php if ( ! $flag ) : ?>
								<span class="monk-language-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $lang_code ); ?>"></span>
							<?php endif; ?>
								<span class="monk-language-name"><?php echo esc_html( $monk_languages[ $lang_code ]['native_name'] ); ?></span>
						</a>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</div>
<?php echo $args['after_widget']; ?>
