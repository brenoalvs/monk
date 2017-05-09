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
		$this->factory = new WP_UnitTest_Factory;

	} // end setUp

	/**
	 * Tests the single post translation process.
	 *
	 * @since    0.4.0
	 *
	 * @return void
	 */
	function test_post_translation() {
		// Creates the original post.
		$original_post_id = new Monk_Post_Translation( $this->factory->post->create() );
		$original_post_id->set_language( 'en_US' );
		$original_post_id->set_translation_group_id( '' );
		$original_post_id->save_translation_group( 'en_US' );

		// tests the translations option before adding a new post.
		$option = $original_post_id->get_translation_group( $original_post_id->get_the_post_id() );
		$this->assertArrayHasKey( 'en_US', $option );

		$new_post_id = new Monk_Post_Translation( $this->factory->post->create() );
		$this->assertNotEmpty( $new_post_id );

		$new_post_id->set_language( 'pt_BR' );

		// Test the new post language.
		$language = $new_post_id->get_language();
		$this->assertEquals( 'pt_BR', $language );

		// Gets the translation group id from the original post.
		$original_monk_id = $original_post_id->get_translation_group_id();

		// Adds the meta_value to the new post.
		$new_post_id->set_translation_group_id( $original_monk_id );

		// Tests if the new meta is equals to the $original_post_id meta.
		$monk_id = $new_post_id->get_translation_group_id();
		$this->assertEquals( $monk_id, $original_monk_id );

		// Adds the new entry in the option.
		$new_post_id->save_translation_group( $language );
		$option = $new_post_id->get_translation_group( $monk_id );

		$this->assertArrayHasKey( $language, $option );
		$this->assertContains( $new_post_id->get_the_post_id(), $option );

	} // end test_post_translation.

} // end class.
