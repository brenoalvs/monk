<?php
require_once( '../../admin/class-monk-admin.php' );

class Save_Post_Test extends WP_UnitTestCase {

	private $monk;

	function setUp() {

		parent::setUp();
		$this->monk = new Monk_Admin( 'monk', '0.3.0' );

	} // end setup

	function testSavePost() {

		$post = array(
			'ID' => 1,
		);

		$_POST['monk_post_language'] = 'en';

		// monk_set_element_language( $type, $id, $language )
		$this->monk->monk_set_element_language( 'post', 1, $_POST['monk_post_language'] );

		// monk_get_element_language( $type, $id )
		$language = $this->monk->monk_get_element_language( 'post', 1 );

		$this->assertEquals( 'English', $language['name'] );
		$this->assertEquals( 'en', $language['slug'] );

	} // end testSavePost

} // end class
