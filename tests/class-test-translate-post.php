<?php
/**
 * The test class for the translate post processes
 *
 * @package    Monk
 * @subpackage Monk/Post Translation Tests
 * @since      0.4.0
 */

/**
 * Tests the methods related to the post creation and translation
 *
 * @since      0.4.0
 *
 * @package    Monk
 * @subpackage Monk/Post Translation Tests
 */
class Test_Translate_Post extends WP_UnitTestCase {

	/**
	 * The post translation object.
	 *
	 * @since    0.4.0
	 *
	 * @access   private
	 * @var      class    $post_translation    A reference for the Monk_Post_Translation class.
	 */
	private $post_translation;

	/**
	 * The WordPress test factory object.
	 *
	 * @since    0.4.0
	 *
	 * @access   private
	 * @var      class    $factory    A reference for the WP_UnitTest_Factory class.
	 */
	private $factory;

	/**
	 * Initializes the test and handles the class instances.
	 *
	 * @since    0.4.0
	 *
	 * @return void
	 */
	function setUp() {
		require_once( '../../includes/class-monk-post-translation.php' );
		require_once( 'wptests/lib/factory.php' );

		parent::setUp();
		$this->factory             = new WP_UnitTest_Factory;
		$this->post_translation    = new Monk_Post_Translation( 'monk', '0.3.0' );

	} // end setUp

	/**
	 * Tests the creation process of a post with its language.
	 *
	 * @since    0.4.0
	 *
	 * @return void
	 */
	function test_add_language_to_post() {

		// Use the factory to create a new post and then test it.
		$post_id = $this->factory->post->create();
		$this->assertNotEmpty( $post_id );

		// Simulates the language from a form.
		$_POST['monk_post_language'] = 'en_US';

		// Set a language for this post.
		$this->post_translation->monk_set_post_language( $post_id, $_POST['monk_post_language'] );

		// get and test if the language was set correctly.
		$language = $this->post_translation->monk_get_post_language( $post_id );
		$this->assertEquals( 'en_US', $language );

		// inserts the monk_id into the post.
		$this->post_translation->monk_set_post_translations_id( $post_id, '' );

		// tests if the monk_id is the post_id, in this case.
		$monk_id = $this->post_translation->monk_get_post_translations_id( $post_id );
		$this->assertEquals( $post_id, $monk_id );

		// saves the translations option.
		$this->post_translation->monk_save_post_translations_option( $post_id, $language );

		// tests the translations option.
		$option = $this->post_translation->monk_get_post_translations_option( $post_id );
		$this->assertArrayHasKey( $language, $option );
		$this->assertContains( $monk_id, $option );

	} // end test_add_language_to_post.

	/**
	 * Tests the single post translation process.
	 *
	 * @since    0.4.0
	 *
	 * @return void
	 */
	function test_post_translation() {
		// Creates the original post.
		$original_post_id = $this->factory->post->create();
		$this->post_translation->monk_set_post_language( $original_post_id, 'en_US' );
		$this->post_translation->monk_set_post_translations_id( $original_post_id, '' );
		$this->post_translation->monk_save_post_translations_option( $original_post_id, 'en_US' );

		// tests the translations option before adding a new post.
		$option = $this->post_translation->monk_get_post_translations_option( $original_post_id );
		$this->assertArrayHasKey( 'en_US', $option );

		$new_post_id = $this->factory->post->create();
		$this->assertNotEmpty( $new_post_id );

		// The language comes from the POST variable.
		$_POST['monk_post_language'] = 'pt_BR';

		$this->post_translation->monk_set_post_language( $new_post_id, $_POST['monk_post_language'] );

		// Test the new post language.
		$language = $this->post_translation->monk_get_post_language( $new_post_id );
		$this->assertEquals( 'pt_BR', $language );

		// Gets the translation group id from the original post.
		$original_monk_id = $this->post_translation->monk_get_post_translations_id( $original_post_id );

		// Adds the meta_value to the new post.
		$this->post_translation->monk_set_post_translations_id( $new_post_id, $original_monk_id );

		// Tests if the new meta is equals to the $original_post_id meta.
		$monk_id = $this->post_translation->monk_get_post_translations_id( $new_post_id );
		$this->assertEquals( $monk_id, $original_monk_id );

		// Adds the new entry in the option.
		$this->post_translation->monk_save_post_translations_option( $new_post_id, $language );
		$option = $this->post_translation->monk_get_post_translations_option( $monk_id );

		$this->assertArrayHasKey( $language, $option );
		$this->assertContains( $monk_id, $option );

	} // end test_post_translation.

} // end class.
