<?php
/**
 * The test class for the singular post creation
 *
 * @package    Monk
 * @subpackage Monk/Post Translation Tests
 * @since      0.4.0
 */

/**
 * Tests the methods related to adding a language to single posts
 *
 * @since      0.4.0
 *
 * @package    Monk
 * @subpackage Monk/Post Translation Tests
 */
class Test_Add_Language_To_Post extends WP_UnitTestCase {

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
		$this->factory     = new WP_UnitTest_Factory;

	} // end setUp

	/**
	 * Tests the creation process of a post with its language.
	 *
	 * @since    0.4.0
	 *
	 * @return Monk_Post_Translation $post_object
	 */
	public function test_object_instance() {

		$post_id     = $this->factory->post->create();
		$post_object = new Monk_Post_Translation( $post_id );

		// Tests if this object is an instance of .
		$this->assertInstanceOf( 'Monk_Post_Translation', $post_object );

		return $post_object;

	} // end test_object_instance.

	/**
	 * Tests the post language.
	 *
	 * @since    0.4.0
	 *
	 * @param    object $post_object    The Monk_Post_Translation object.
	 * @depends test_object_instance
	 * @return Monk_Post_Translation $post_object
	 */
	public function test_post_language( $post_object ) {

		// Sets a language for this post.
		$post_object->set_language( 'en_US' );

		// gets and test if the language was set correctly.
		$language = $post_object->get_language();
		$this->assertEquals( 'en_US', $language );

		return $post_object;

	} // end test_post_language

	/**
	 * Tests the post group id.
	 *
	 * @since    0.4.0
	 *
	 * @param    object $post_object    The Monk_Post_Translation object.
	 * @depends test_post_language
	 * @return Monk_Post_Translation $post_object
	 */
	public function test_translation_group_id( $post_object ) {

		// inserts the monk_id into the post.
		$post_object->set_translation_group_id( '' );

		// tests if the monk_id is the object post_id, in this case.
		$monk_id = $post_object->get_translation_group_id();
		$this->assertEquals( $post_object->get_the_post_id(), $monk_id );

		return $post_object;

	} // end test_translation_group_id

	/**
	 * Tests the post translation group.
	 *
	 * @since    0.4.0
	 *
	 * @param    object $post_object    The Monk_Post_Translation object.
	 * @depends test_translation_group_id
	 * @return void
	 */
	public function test_translation_group( $post_object ) {

		$language = $post_object->get_language();

		// saves the translations group.
		$post_object->save_translation_group( $language );

		// tests the translations group.
		$monk_id = $post_object->get_translation_group_id();
		$option  = $post_object->get_translation_group( $monk_id );
		$this->assertArrayHasKey( $language, $option );
		$this->assertContains( $post_object->get_the_post_id(), $option );

	} // end test_translation_group
}
