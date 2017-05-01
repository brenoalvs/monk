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
	 * @param    string $monk       The name of this plugin.
	 * @param    string $version    The version of this plugin.
	 * @return  void
	 */
	public function __construct( $monk, $version ) {

		$this->monk = $monk;
		$this->version = $version;

	}

	/**
	 * Defines the post language.
	 *
	 * @since    0.4.0
	 * @param    integer $post_id    The post object id.
	 * @param    string $language    The language defined fot the post.
	 * @return   void
	 */
	public function monk_set_post_language( $post_id, $language ) {
		add_post_meta( $post_id, '_monk_post_language', $language, true );
	}

	/**
	 * Gets the post language.
	 *
	 * @since    0.4.0
	 * @param    integer $post_id    The post object id.
	 * @return   string $language    The language from the meta data.
	 */
	public function monk_get_post_language( $post_id ) {
		$language = get_post_meta( $post_id, '_monk_post_language', true );
		return $language;
	}
}