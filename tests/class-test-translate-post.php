<?php
/**
 * The class to test the posts translation groups
 *
 * @package    Monk
 * @subpackage Monk/Post Translation Tests
 * @since      0.4.0
 */

/**
 * Tests the methods related to the posts translation processes
 *
 * @since      0.4.0
 *
 * @package    Monk
 * @subpackage Monk/Post Translation Tests
 */
class Test_Translate_Post extends WP_UnitTestCase {

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
	 * The Monk_Post_Translation object.
	 *
	 * @since    0.4.0
	 *
	 * @access   private
	 * @var      class    $post_object    A reference for the Monk_Post_Translation class.
	 */
	private $post_object;

	/**
	 * The post to use during the tests.
	 *
	 * @since    0.4.0
	 *
	 * @access   private
	 * @var      class    $post_id    The id to use across the class.
	 */
	private $post_id;

	/**
	 * The Monk_Post_Translation object.
	 *
	 * @since    0.4.0
	 *
	 * @access   private
	 * @var      class    $translation_object    A reference for the Monk_Post_Translation class.
	 */
	private $translation_object;

	/**
	 * The second post to use during the tests.
	 *
	 * @since    0.4.0
	 *
	 * @access   private
	 * @var      class    $translation_id    The id to use across the class.
	 */
	private $translation_id;

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
		$this->factory     = new WP_UnitTest_Factory;
		$this->post_id     = $this->factory->post->create();
		$this->original_post_object = new Monk_Post_Translation( $this->post_id );

	} // end setUp

	/**
	 * Creates the post that will be translated.
	 *
	 * @since    0.4.0
	 *
	 * @return void
	 */
	function test_original_post() {

		// Creates the original post.
		$this->original_post_object->set_language( 'en_US' );
		$this->original_post_object->set_translation_group_id( '' );
		$this->original_post_object->save_translation_group( 'en_US' );

		// tests the translations option before adding a new post.
		$option = $this->original_post_object->get_translation_group( $this->original_post_object->get_the_post_id() );
		$this->assertArrayHasKey( 'en_US', $option );

	}

	/**
	 * Tests the single post translation process.
	 *
	 * @since    0.4.0
	 *
	 * @return void
	 */
	function test_post_translation_language() {

		$this->translation_id     = $this->factory->post->create()
		$this->translation_object = new Monk_Post_Translation( $this->translation_id );
		$this->assertNotEmpty( $this->translation_object );

		$this->translation_object->set_language( 'pt_BR' );

		// Test the new post language.
		$language = $this->translation_object->get_language();
		$this->assertEquals( 'pt_BR', $language );

	} // end test_post_translation.

	/**
	 * Tests the new post group id.
	 *
	 * @since    0.4.0
	 *
	 * @return void
	 */
	function test_post_translation_group_id() {

		// Gets the translation group id from the original post.
		$original_monk_id = $this->original_post_object->get_translation_group_id();

		// Adds the meta_value to the new post.
		$new_post_id->set_translation_group_id( $original_monk_id );

		// Tests if the new meta is equals to the $original_post_object meta.
		$monk_id = $new_post_id->get_translation_group_id();
		$this->assertEquals( $monk_id, $original_monk_id );

	}

	/**
	 * Tests the new post translation group.
	 *
	 * @since    0.4.0
	 *
	 * @return void
	 */
	function test_post_translation_group() {

		// Adds the new entry in the option.
		$new_post_id->save_translation_group( $language );
		$option = $new_post_id->get_translation_group( $monk_id );

		$this->assertArrayHasKey( $language, $option );
		$this->assertContains( $new_post_id->get_the_post_id(), $option );

	}

} // end class.
