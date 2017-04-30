<?php
require_once( '../../admin/class-monk-admin.php' );
require_once( 'wptests/lib/factory.php' );

class Save_Post_Test extends WP_UnitTestCase {

	private $monk;
	private $factory;

	function setUp() {

		parent::setUp();
		$this->monk    = new Monk_Admin( 'monk', '0.3.0' );
		$this->factory = new WP_UnitTest_Factory;

	} // end setUp

	function test_add_language_to_post() {

		// Use the factory to create a new post and then test it.
		$post_id = $this->factory->post->create();
		$this->assertNotEmpty( $post_id );

		// Simulates the language from a form.
		$_POST['monk_post_language'] = 'en_US';

		// Set a language for this post.
		$this->monk->monk_set_post_language( $post_id, $_POST['monk_post_language'] );

		// get and test if the language was set correctly.
		$language = $this->monk->monk_get_post_language( $post_id );
		$this->assertEquals( 'en_US', $language );

		// inserts the monk_id into the post.
		$this->monk->monk_set_post_translations_id( $post_id, '' );

		// tests if the monk_id is the post_id, in this case.
		$monk_id = $this->monk->monk_get_post_translations_id( $post_id );
		$this->assertEquals( $post_id, $monk_id );
		
		// saves the translations option.
		$this->monk->monk_save_post_translations_option( $post_id, $language );

		// tests the translations option.
		$option = $this->monk->monk_get_post_translations_option( $post_id );
		$this->assertArrayHasKey( $language, $option );
		$this->assertContains( $monk_id, $option );

	} // end test_add_language_to_post.

	function test_post_translation() {
		// Creates the original post.
		$original_post_id = $this->factory->post->create();
		$this->monk->monk_set_post_language( $original_post_id, 'en_US' );
		$this->monk->monk_set_post_translations_id( $original_post_id, '' );
		$this->monk->monk_save_post_translations_option( $original_post_id, 'en_US' );

		// tests the translations option before adding a new post.
		$option = $this->monk->monk_get_post_translations_option( $original_post_id );
		$this->assertArrayHasKey( 'en_US', $option );

		$new_post_id = $this->factory->post->create();
		$this->assertNotEmpty( $new_post_id );

		// The language comes from the POST variable.
		$_POST['monk_post_language'] = 'pt_BR';

		$this->monk->monk_set_post_language( $new_post_id, $_POST['monk_post_language'] );

		// Test the new post language.
		$language = $this->monk->monk_get_post_language( $new_post_id );
		$this->assertEquals( 'pt_BR', $language );

		// Simulates the monk_id in the url.
		$original_monk_id = $this->monk->monk_get_post_translations_id( $original_post_id );

		// Adds the meta_value to the new post.
		$this->monk->monk_set_post_translations_id( $new_post_id, $original_monk_id );

		// Tests if the new meta is equals to the $original_post_id meta.
		$monk_id = $this->monk->monk_get_post_translations_id( $new_post_id );
		$this->assertEquals( $monk_id, $original_monk_id );

		// Adds the new entry in the option.
		$this->monk->monk_save_post_translations_option( $new_post_id, $language );
		$option = $this->monk->monk_get_post_translations_option( $monk_id );

		$this->assertArrayHasKey( $language, $option );
		$this->assertContains( $monk_id, $option );

	} // end test_post_translation.

} // end class.
