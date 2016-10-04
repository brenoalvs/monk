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
	$select_value = $_GET['lang'];
} else {
	$select_value = 'en_EN';
}

$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'monk' );
$flag = ! empty( $instance['flag'] ) ? true : false;
$name = ! empty( $instance['name'] ) ? true : false;
$lang = ! empty( $instance['lang-native'] ) ? true : false;

if ( $lang ){
	$languages = array(
		'en_EN' => __( 'English', 'monk' ),
		'es_ES' => __( 'Español', 'monk' ),
		'fr_FR' => __( 'Français', 'monk' ),
		'pt_BR' => __( 'Português', 'monk'),
	);
} else {
	$languages = array(
		'en_EN' => __( 'English', 'monk' ),
		'es_ES' => __( 'Spanish', 'monk' ),
		'fr_FR' => __( 'French', 'monk' ),
		'pt_BR' => __( 'Portuguese', 'monk' ),
	);
}

$languages_flag = array(
	'en_EN' => 'http://image.flaticon.com/icons/svg/206/206626.svg',
	'es_ES' => 'http://image.flaticon.com/icons/svg/206/206724.svg',
	'fr_FR' => 'http://image.flaticon.com/icons/svg/206/206657.svg',
	'pt_BR' => 'http://image.flaticon.com/icons/svg/206/206597.svg',
);
?>
<section id="<?php echo $this->id; ?>" class="widget <?php echo $this->id_base; ?>">
	<h2 class="widget_title"><?php esc_html_e( $title ); ?></h2>
	<ul id="monk-selector">
		<?php foreach ( $languages as $key => $value ) : ?>
			<?php if ( strcmp( $key, $select_value ) == 0 ) : ?>
				<li class="monk-active-lang">
					<span class="monk-active-lang-name">
						<?php if ( $flag ) : ?>
							<img class="monk-selector-flag" src="<?php esc_attr_e( $languages_flag[$key] ) ?>" alt="">
						<?php endif; ?>
						<?php if ( $name ) : ?>
							<span><?php esc_html_e( $value ); ?></span>
						<?php else : ?>
							<span class="screen-reader-text"><?php esc_html_e( $value ); ?></span>
						<?php endif; ?>
					</span>
				</li>
			<?php else : ?>
				<li class="monk-lang">
					<a class="monk-selector-link" href="?lang=<?php esc_attr_e( $key, 'monk' ); ?>">
						<?php if ( $flag ) : ?>
							<img class="monk-selector-flag" src="<?php esc_attr_e( $languages_flag[$key] ) ?>" alt="">
						<?php endif; ?>
						<?php if ( $name ) : ?>
							<span><?php esc_html_e( $value ); ?></span>
						<?php else : ?>
							<span class="screen-reader-text"><?php esc_html_e( $value ); ?></span>
						<?php endif; ?>
					</a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
		<span class="dashicons dashicons-arrow-down monk-selector-arrow"></span>
	</ul>
</section>
