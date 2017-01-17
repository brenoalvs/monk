<?php
/**
 * The engine to change links according to language
 *
 * @since      0.2.0
 *
 * @package    Monk
 * @subpackage Monk/Links
 */

/**
 * Class to hold all functions related to links
 * the permalinks are filtered to return content due to languages
 *
 * @package    Monk
 * @subpackage Monk/Links
 */
class Monk_Links_Controller {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $monk    The ID of this plugin.
	 */
	private $monk;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $monk       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $monk, $version ) {

		$this->plugin_name = $monk;
		$this->version = $version;

	}

	
}
