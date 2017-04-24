<?php
require_once( '../../admin/class-monk-admin.php' );

class Save_Post_Test extends WP_UnitTestCase {

	private $monk;

	function setUp() {

		parent::setUp();
		$this->monk = new Monk_Admin( 'monk', '0.3.0' );

	} // end setup

	function testClassInitialization() {

		$running = method_exists( $this->monk, '__construct' );
		$this->assertTrue( $running );

	} // end testClassInitialization

	function testSavePost() {

	}

} // end class
