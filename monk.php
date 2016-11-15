<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/brenoalvs/monk
 * @since             1.0.0
 * @package           Monk
 *
 * @wordpress-plugin
 * Plugin Name:       Monk
 * Plugin URI:        https://github.com/brenoalvs/monk
 * Description:       An open source WordPress plugin to create multilanguage sites.
 * Version:           1.0.0
 * Author:            Breno Alves
 * Author URI:        http://brenoalves.com.br/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       monk
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-monk-activator.php
 */
function activate_monk() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-monk-activator.php';
	Monk_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-monk-deactivator.php
 */
function deactivate_monk() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-monk-deactivator.php';
	Monk_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_monk' );
register_deactivation_hook( __FILE__, 'deactivate_monk' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-monk.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_monk() {

	$monk = new Monk();
	$monk->run();

}
run_monk();
