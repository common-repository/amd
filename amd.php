<?php
/**
 * Plugin Name: Amd
 * Plugin URI: https://github.com/adpu/amd
 * Description: Add meta description field inside post and page editor.
 * Version: 1.05
 * Author: Jordi Verdaguer
 * Author URI: http://adpu.net
 * Requires at least: 4.0
 * Tested up to: 4.7.5
 *
 * Text Domain: amd
 * Domain Path: /languages/
 */

/**
 *
 * @link              http://adpu.net.com
 * @since             1.0.0
 * @package           amd
 *
 * @wordpress-plugin
 * Plugin Name:       Jordi Verdaguer
 * Plugin URI:        https://github.com/adpu/amd
 * Description:       Add meta description field inside post and page editor.
 * Version:           1.05
 * Author:            Jordi Verdaguer
 * Author URI:        http://adpu.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       amd
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-amd-activator.php
 */
function activate_amd() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-amd-activator.php';
	Amd_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-amd-deactivator.php
 */
function deactivate_amd() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-amd-deactivator.php';
	Amd_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_amd' );
register_deactivation_hook( __FILE__, 'deactivate_amd' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-amd.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_amd() {

	$plugin = new Amd();
	$plugin->run();

}
run_amd();