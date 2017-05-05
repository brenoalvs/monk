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
 * the posts have all the internationalization
 * features provided by Monk
 *
 * @since      0.4.0
 *
 * @package    Monk
 * @subpackage Monk/Post Translation
 */
class Monk_Post_Translation {

	/**
	 * The post id to be used inside the class.
	 *
	 * @since    0.4.0
	 *
	 * @access   private
	 * @var      string    $post_id    The post id.
	 */
	private $post_id;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.4.0
	 *
	 * @param    integer $post_id    The id to be used across the class.
	 * @return void
	 */
	public function __construct( $post_id ) {

		$this->post_id = $post_id;

	}

	/**
	 * Returns the post id.
	 *
	 * @since    0.4.0
	 *
	 * @return integer $post_id    The post object id.
	 */
	public function get_the_post_id() {
		return $this->post_id;
	}

	/**
	 * Defines the post language.
	 *
	 * @since    0.4.0
	 *
	 * @param    string $language    The language defined fot the post.
	 * @return void
	 */
	public function set_language( $language ) {
		add_post_meta( $this->post_id, '_monk_post_language', $language, true );
	}

	/**
	 * Gets the post language.
	 *
	 * @since    0.4.0
	 *
	 * @return string $language    The language from the meta data.
	 */
	public function get_language() {
		$language = get_post_meta( $this->post_id, '_monk_post_language', true );
		return $language;
	}

	/**
	 * Gets the post translations option id.
	 *
	 * @since    0.4.0
	 *
	 * @return integer $id The id to reference the option holding the post translations.
	 */
	public function get_translation_group_id() {
		$id = get_post_meta( $this->post_id, '_monk_post_translations_id', true );

		if ( empty( $id ) ) {
			$id = $this->post_id;
		}
		return $id;
	}

	/**
	 * Sets the post translations option id.
	 *
	 * @since    0.4.0
	 *
	 * @param    integer $monk_id    The reference to the option holding the post translations.
	 * @return void
	 */
	public function set_translation_group_id( $monk_id ) {

		if ( empty( $monk_id ) ) {
			$monk_id = filter_input( INPUT_GET, 'monk_id' );
		} elseif ( empty( $monk_id ) ) {
			$monk_id = $this->get_translation_group_id( $this->post_id );
		}

		add_post_meta( $this->post_id, '_monk_post_translations_id', $monk_id, true );
	}

	/**
	 * Retrieves the post translations option.
	 *
	 * @since    0.4.0
	 *
	 * @param    integer $monk_id    The post object id.
	 * @return array $translations The option holding the post translations.
	 */
	public function get_translation_group( $monk_id ) {
		$translations = get_option( 'monk_post_translations_' . $monk_id, false );

		return $translations;
	}

	/**
	 * Saves the new post entry in the correct translations option.
	 *
	 * @since    0.4.0
	 *
	 * @param    string $language   The post language.
	 * @return void
	 */
	public function save_translation_group( $language ) {
		$monk_id           = $this->get_translation_group_id( $this->post_id );
		$post_translations = $this->get_translation_group( $monk_id );

		if ( empty( $post_translations ) ) {
			$data = array(
				$language => $this->post_id,
			);
		} else {
			$post_translations[ $language ] = $this->post_id;
			$data                           = $post_translations;
		}

		update_option( 'monk_post_translations_' . $monk_id, $data );
	}
}
