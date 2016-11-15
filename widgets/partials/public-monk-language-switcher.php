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
$name             = ! empty( $instance['name'] ) ? true : false;
$lang             = ! empty( $instance['lang-native'] ) ? true : false;
$lang_type        = $lang ? 'native_name' : 'name';
$active_languages = get_option( 'monk_active_languages' );

?>
<section id="<?php echo esc_attr( $this->id ); ?>" class="widget <?php echo esc_attr( $this->id_base ); ?>">
	<h2 class="widget-title"><?php echo esc_html( $title ); ?></h2>
	<ul id="monk-selector">
		<?php foreach ( $active_languages as $lang_code ) : ?>
			<?php if ( strcmp( $lang_code, $select_value ) == 0 ) : ?>
				<li class="monk-active-lang">
					<span class="monk-active-lang-name">
						<?php if ( $flag ) : ?>
							<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $lang_code ); ?>"></span>
						<?php endif; ?>
						<?php if (  $monk_languages[ $lang_code ] ) : ?>
							<span><?php echo esc_html( $monk_languages[ $lang_code ][ $lang_type ] ); ?></span>
						<?php else : ?>
							<span class="screen-reader-text"><?php echo esc_html( $monk_languages[ $lang_code ][ $lang_type ] ); ?></span>
						<?php endif; ?>
					</span>
				</li>
			<?php else : ?>
				<li class="monk-lang">
					<a class="monk-selector-link" href="<?php echo esc_url( add_query_arg( 'lang', esc_attr( $lang_code, 'monk' ), home_url() ) ); ?>">
						<?php if ( $flag ) : ?>
							<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $lang_code ); ?>"></span>
						<?php endif; ?>
						<?php if ( $monk_languages[ $lang_code ] ) : ?>
							<span><?php echo esc_html( $monk_languages[ $lang_code ][ $lang_type ] ); ?></span>
						<?php else : ?>
							<span class="screen-reader-text"><?php echo esc_html( $monk_languages[ $lang_code ][ $lang_type ] ); ?></span>
						<?php endif; ?>
					</a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
		<span class="dashicons dashicons-arrow-down monk-selector-arrow"></span>
	</ul>
</section>
