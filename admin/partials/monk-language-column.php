<?php
/**
 * Show flags in Languages column on posts list.
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
?>
	<span class="hide-if-no-js flag-icon flag-icon-monk-pencil"></span>
	<div class="hide-if-js monk-column-translations">
<?php
if ( $monk_satan ) :
	foreach ( $active_languages as $lang ) :
		foreach ( $monk_satan as $lang_code => $post_id ) :
			if ( $lang === $lang_code ) :
				$post_url = add_query_arg( array(
					'post' => $post_id,
				), $base_url );
				?>
				<a class="monk-translation-link" href="<?php echo esc_url( $post_url ); ?>">
					<span class="monk-language-name"><?php echo esc_html( $monk_languages[ $lang ]['name'] ); ?></span>
					<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $lang_code ); ?>"></span>
				</a>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endforeach; ?>
<?php
	else :
		$post_url = add_query_arg( array(
				'post' => $post_id,
		), $base_url );
?>
	<a class="monk-translation-link" href="<?php echo esc_url( $post_url ); ?>">
		<span class="monk-language-name"><?php echo esc_html( $monk_languages[ $default_language ]['name'] ); ?></span>
		<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $default_language ); ?>"></span>
	</a>
<?php endif; ?>
		<a class="monk-new-post-link" href="<?php echo esc_url( $new_post_url ); ?>">Add +</a>
	</div>
