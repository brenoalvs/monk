<?php
/**
 * The post translation main class
 *
 * @package    Monk
 * @subpackage Monk/Post Translation
 * @since      0.4.0
 */

/**
 * Processes the methods related to the post translation
 *
 * The user defines a language for every post and from this point
 * the posts have all of the internationalization
 * features provided by Monk
 *
 * @since      0.4.0
 *
 * @package    Monk
 * @subpackage Monk/Post Translation
 */
class Monk_Post_Translation {

	/**
	 * The plugin ID.
	 *
	 * @since    0.4.0
	 *
	 * @access   private
	 * @var      string    $monk    The ID of this plugin.
	 */
	private $monk;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.4.0
	 *
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.4.0
	 *
	 * @param    string $monk       The name of this plugin.
	 * @param    string $version    The version of this plugin.
	 * @return void
	 */
	public function __construct( $monk, $version ) {

		$this->monk = $monk;
		$this->version = $version;

	}

	/**
	 * Defines the post language.
	 *
	 * @since    0.4.0
	 *
	 * @param    integer $post_id    The post object id.
	 * @param    string  $language    The language defined fot the post.
	 * @return void
	 */
	public function monk_set_post_language( $post_id, $language ) {
		add_post_meta( $post_id, '_monk_post_language', $language, true );
	}

	/**
	 * Gets the post language.
	 *
	 * @since    0.4.0
	 *
	 * @param    integer $post_id    The post object id.
	 * @return string $language    The language from the meta data.
	 */
	public function monk_get_post_language( $post_id ) {
		$language = get_post_meta( $post_id, '_monk_post_language', true );
		return $language;
	}

	/**
	 * Gets the post translations option id.
	 *
	 * @since    0.4.0
	 *
	 * @param    integer $post_id    The post object id.
	 * @return integer $id         The id to reference the option holding the post translations.
	 */
	public function monk_get_post_translations_id( $post_id ) {
		$id = get_post_meta( $post_id, '_monk_post_translations_id', true );

		if ( empty( $id ) ) {
			$id = $post_id;
		}
		return $id;
	}

	/**
	 * Sets the post translations option id.
	 *
	 * @since    0.4.0
	 *
	 * @param    integer $post_id    The post object id.
	 * @param    integer $monk_id    The reference to the option holding the post translations.
	 * @return void
	 */
	public function monk_set_post_translations_id( $post_id, $monk_id ) {

		if ( empty( $monk_id ) ) {
			$monk_id = filter_input( INPUT_GET, 'monk_id' );
		} elseif ( empty( $monk_id ) ) {
			$monk_id = $this->monk_get_post_translations_id( $post_id );
		}

		add_post_meta( $post_id, '_monk_post_translations_id', $monk_id, true );
	}

	/**
	 * Retrieves the post translations option.
	 *
	 * @since    0.4.0
	 *
	 * @param    integer $monk_id    The post object id.
	 * @return array $translations The option holding the post translations.
	 */
	public function monk_get_post_translations_option( $monk_id ) {
		$translations = get_option( 'monk_post_translations_' . $monk_id, false );

		return $translations;
	}

	/**
	 * Saves the new post entry in the correct translations option.
	 *
	 * @since    0.4.0
	 *
	 * @param    integer $post_id    The post object id.
	 * @param    string  $language   The post language.
	 * @return void
	 */
	public function monk_save_post_translations_option( $post_id, $language ) {
		$monk_id           = $this->monk_get_post_translations_id( $post_id );
		$post_translations = $this->monk_get_post_translations_option( $monk_id );

		if ( empty( $post_translations ) ) {
			$data = array(
				$language => $post_id,
			);
		} else {
			$post_translations[ $language ] = $post_id;
			$data                           = $post_translations;
		}

		update_option( 'monk_post_translations_' . $monk_id, $data );
	}
}
