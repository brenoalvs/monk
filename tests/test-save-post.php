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

	} // end setup

	function test_add_language_to_post() {

		// Use the factory to create a new post and then test it
		$post_id = $this->factory->post->create();
		$this->assertNotEmpty( $post_id );

		// Simulates the language from a form
		$_POST['monk_post_language'] = 'en_US';

		// Set a language for this post
		$this->monk->monk_set_post_language( $post_id, $_POST['monk_post_language'] );

		// get and test if the language was set correctly
		$language = $this->monk->monk_get_post_language( $post_id );
		$this->assertEquals( 'en_US', $language );

		// inserts the monk_id into the post
		$this->monk->monk_set_post_translations_id( $post_id );

		// tests if the monk_id is the post_id, in this case
		$monk_id = $this->monk->monk_get_post_translations_id( $post_id );
		$this->assertEquals( $post_id, $monk_id );
		
		// saves the translations option
		$this->monk->monk_save_post_translations_option( $post_id, $language );

		// tests the translations option
		$option = $this->monk->monk_get_post_translations_option( $post_id );
		$this->assertArrayHasKey( $language, $option );
		$this->assertContains( $monk_id, $option );

	} // end testSavePost

	function test_post_translation() {
		// Creates the original post 
		$original_post_id = $this->factory->post->create();
		$this->monk->monk_set_post_language( $original_post_id, 'en_US' );
		$this->monk->monk_set_post_translations_id( $original_post_id );
		$this->monk->monk_save_post_translations_option( $original_post_id, 'en_US' );

	}

} // end class
