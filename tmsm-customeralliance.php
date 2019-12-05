<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/nicomollet
 * @since             1.0.0
 * @package           Tmsm_Customeralliance
 *
 * @wordpress-plugin
 * Plugin Name:       TMSM Customer Alliance
 * Plugin URI:        https://github.com/thermesmarins/tmsm-customeralliance
 * Description:       Shortcodes for displaying a badge and a reviews page from Customer Alliance reviews
 * Version:           1.2.1
 * Author:            Nicolas Mollet
 * Author URI:        https://github.com/nicomollet
 * Requires PHP:      5.6
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tmsm-customeralliance
 * Domain Path:       /languages
 * Github Plugin URI: https://github.com/thermesmarins/tmsm-customeralliance
 * Github Branch:     master
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently pligin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TMSM_CUSTOMERALLIANCE_VERSION', '1.2.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tmsm-customeralliance-activator.php
 */
function activate_tmsm_customeralliance() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tmsm-customeralliance-activator.php';
	Tmsm_Customeralliance_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tmsm-customeralliance-deactivator.php
 */
function deactivate_tmsm_customeralliance() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tmsm-customeralliance-deactivator.php';
	Tmsm_Customeralliance_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tmsm_customeralliance' );
register_deactivation_hook( __FILE__, 'deactivate_tmsm_customeralliance' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tmsm-customeralliance.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tmsm_customeralliance() {

	$plugin = new Tmsm_Customeralliance();
	$plugin->run();

}
run_tmsm_customeralliance();
