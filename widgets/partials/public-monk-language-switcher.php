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
	$select_value = 'en_US';
}

$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'monk' );
$flag  = ! empty( $instance['flag'] ) ? true : false;
$name  = ! empty( $instance['name'] ) ? true : false;
$lang  = ! empty( $instance['lang-native'] ) ? true : false;

if ( $lang ){
	$languages = array(
		'da_DK' => __( 'Dansk', 'monk' ),
		'en_US' => __( 'English', 'monk' ),
		'fr_FR' => __( 'Français', 'monk' ),
		'de_DE' => __( 'Deutsch', 'monk' ),
		'it_IT' => __( 'Italiano', 'monk' ),
		'ja'    => __( '日本の', 'monk' ),
		'pt_BR' => __( 'Português (Brasil)', 'monk' ),
		'ru_RU' => __( 'Pусский', 'monk' ),
		'es_ES' => __( 'Español', 'monk' ),
	);
} else {
	$languages = array(
		'da_DK' => __( 'Danish', 'monk' ),
		'en_US' => __( 'English', 'monk' ),
		'fr_FR' => __( 'French', 'monk' ),
		'de_DE' => __( 'German', 'monk' ),
		'it_IT' => __( 'Italian', 'monk' ),
		'ja'    => __( 'Japanese', 'monk' ),
		'pt_BR' => __( 'Portuguese (Brazil)', 'monk' ),
		'ru_RU' => __( 'Russian', 'monk' ),
		'es_ES' => __( 'Spanish', 'monk' ),
	);
}

$languages_flag = array(
	'da_DK' => 'flag-icon-dk',
	'en_US' => 'flag-icon-us',
	'fr_FR' => 'flag-icon-fr',
	'de_DE' => 'flag-icon-de',
	'it_IT' => 'flag-icon-it',
	'ja'    => 'flag-icon-jp',
	'pt_BR' => 'flag-icon-br',
	'ru_RU' => 'flag-icon-ru',
	'es_ES' => 'flag-icon-es',
);

$index = get_option( 'monk_active_languages' );
?>
<section id="<?php echo $this->id; ?>" class="widget <?php echo $this->id_base; ?>">
	<h2 class="widget-title"><?php echo esc_html( $title ); ?></h2>
	<ul id="monk-selector">
		<?php foreach ( $index as $value ) : ?>
			<?php if ( strcmp( $value, $select_value ) == 0 ) : ?>
				<li class="monk-active-lang">
					<span class="monk-active-lang-name">
						<?php if ( $flag ) : ?>
							<span class="monk-selector-flag flag-icon <?php echo esc_attr( $languages_flag[$value] ); ?>"></span>
						<?php endif; ?>
						<?php if (  $languages[$value] ) : ?>
							<span><?php echo esc_html( $languages[$value] ); ?></span>
						<?php else : ?>
							<span class="screen-reader-text"><?php echo esc_html( $languages[$value] ); ?></span>
						<?php endif; ?>
					</span>
				</li>
			<?php else : ?>
				<li class="monk-lang">
					<a class="monk-selector-link" href="<?php echo add_query_arg( 'lang', esc_attr( $value, 'monk' ), home_url() ); ?>">
						<?php if ( $flag ) : ?>
							<span class="monk-selector-flag flag-icon <?php echo esc_attr( $languages_flag[$value] ); ?>"></span>
						<?php endif; ?>
						<?php if ( $languages[$value] ) : ?>
							<span><?php echo esc_html( $languages[$value] ); ?></span>
						<?php else : ?>
							<span class="screen-reader-text"><?php echo esc_html( $languages[$value] ); ?></span>
						<?php endif; ?>
					</a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
		<span class="dashicons dashicons-arrow-down monk-selector-arrow"></span>
	</ul>
</section>
