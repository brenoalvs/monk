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
		$this->factory              = new WP_UnitTest_Factory;
		$this->translation_id       = $this->factory->post->create();
		$this->translation_object   = new Monk_Post_Translation( $this->translation_id );

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
		$original_post_id     = $this->factory->post->create();
		$original_post_object = new Monk_Post_Translation( $original_post_id );

		// Verifies the new instance
		$this->assertInstanceOf( 'Monk_Post_Translation', $original_post_object );

		// Sets the object properties
		$original_post_object->set_language( 'en_US' );
		$original_post_object->set_translation_group_id( '' );
		$original_post_object->save_translation_group( 'en_US' );

		// Tests the translations option before adding a new post.
		$option = $original_post_object->get_translation_group( $original_post_object->get_the_post_id() );
		$this->assertArrayHasKey( 'en_US', $option );
		$this->assertContains( $original_post_object->get_the_post_id(), $option );

		return $original_post_object;

	} // end test_original_post

	/**
	 * Creates the post that will be the translation.
	 *
	 * @since    0.4.0
	 *
	 * @return void
	 */
	function test_translation_post() {

		// Creates the post to be the translation
		$translation_id       = $this->factory->post->create();
		$translation_object   = new Monk_Post_Translation( $translation_id );

		// Tests the object.
		$this->assertInstanceOf( 'Monk_Post_Translation', $translation_object );

		return $translation_object;

	}

	/**
	 * Tests the single post translation process.
	 *
	 * @since    0.4.0
	 *
	 * @depends test_translation_post
	 * @return void
	 */
	function test_post_translation_language( $translation_object ) {

		$translation_object->set_language( 'pt_BR' );

		// Test the new post language.
		$language = $translation_object->get_language();
		$this->assertEquals( 'pt_BR', $language );

		return $translation_object;

	} // end test_post_translation.

	/**
	 * Tests the new post group id.
	 *
	 * @since    0.4.0
	 *
	 * @depends test_post_translation_language
	 * @depends test_original_post
	 * @return void
	 */
	function test_post_translation_group_id( $original_post_object, $translation_object ) {

		// Gets the translation group id from the original post.
		$original_monk_id = $original_post_object->get_translation_group_id();

		// Adds the meta_value to the new post.
		$translation_object->set_translation_group_id( $original_monk_id );

		// Tests if the new meta is equals to the $original_post_object meta.
		$monk_id = $translation_object->get_translation_group_id();
		$this->assertEquals( $monk_id, $original_monk_id );

		return $translation_object;

	}

	/**
	 * Tests the new post translation group.
	 *
	 * @since    0.4.0
	 *
	 * @depends test_post_translation_group_id
	 * @return void
	 */
	function test_post_translation_group( $translation_object ) {

		// Gets the group id.
		$monk_id  = $translation_object->get_translation_group_id();

		// Gets the language.
		$language = $translation_object->get_language();

		// Adds the new entry in the option.
		$translation_object->save_translation_group( $language );
		$option = $translation_object->get_translation_group( $monk_id );

		$this->assertArrayHasKey( $language, $option );
		$this->assertContains( $translation_object->get_the_post_id(), $option );

	}

} // end class.
