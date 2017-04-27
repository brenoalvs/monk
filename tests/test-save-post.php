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

	function testSavePost() {

		// Use the factory to create a new post and then test it
		$post_id = $this->factory->post->create();
		$this->assertNotEmpty( $post_id );

		// simulates the language from a form
		$_POST['monk_post_language'] = 'en';

		// monk_set_post_language( $id, $language )
		$this->monk->monk_set_post_language( $post_id, $_POST['monk_post_language'] );

		// test if the language was set correctly
		$language = $this->monk->monk_get_post_language( $post_id, '_monk_post_language' );
		$this->assertEquals( 'en', $language );

	} // end testSavePost

} // end class
