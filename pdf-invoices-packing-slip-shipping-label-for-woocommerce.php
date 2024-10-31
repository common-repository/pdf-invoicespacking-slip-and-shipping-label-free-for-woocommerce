<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://phoeniixx.com
 * @since             1.0.0
 * @package           Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       PDF Invoices,Packing slip and Shipping Label Free for Woocommerce
 * Plugin URI:        https://phoeniixx.com/product/Pdf-Invoices-Packing-Slip-Shipping-Label-for-Woocommerce
 * Description:       The plugin helps you to create customized PDF invoices and packing slips for your customers orders. You can send the invoices to your clients automatically in the mail attached as a PDF.
 * Version:           1.0.0
 * Author:            phoeniixx
 * Author URI:        https://phoeniixx.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pdf-invoices-packing-slip-shipping-label-for-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PHOEN_PDF_INVOICES_PACKING_SLIP_SHIPPING_LABEL_FOR_WOOCOMMERCE_VERSION', '1.0.5' );

/**
 * The define plugin_dir_url 
 * The define plugin_dir_path 
 */
 define('PHOENPDFINVOICEPLUGINURL',plugin_dir_url(__FILE__));
 define('PHOENPDFINVOICEPLUGINDIRPATH',plugin_dir_path(__FILE__));
 
 /**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pdf-invoices-packing-slip-shipping-label-for-woocommerce-activator.php
 */
if(!function_exists('phoen_pdf_activate_pdf_invoices_packing_slip_shipping_label_for_woocommerce')){
	
	function phoen_pdf_activate_pdf_invoices_packing_slip_shipping_label_for_woocommerce() {
		require_once PHOENPDFINVOICEPLUGINDIRPATH . 'includes/class-pdf-invoices-packing-slip-shipping-label-for-woocommerce-activator.php';
		Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_Activator::activate();
	}

}


register_activation_hook( __FILE__, 'phoen_pdf_activate_pdf_invoices_packing_slip_shipping_label_for_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require PHOENPDFINVOICEPLUGINDIRPATH . 'includes/class-pdf-invoices-packing-slip-shipping-label-for-woocommerce.php';
if ( is_admin() ) {
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'phoen_pdf_action_links', 10, 4 );
}
/**
 * Action Links
 *
 * add the action links to plugin admin page
 *
 * @param $links | links plugin array
 *
 * @return   mixed Array
 * @since    1.0
 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
 * @return mixed
 * @use plugin_action_links_{$plugin_file_name}
 */
if(!function_exists('phoen_pdf_action_links')){

	function phoen_pdf_action_links( $actions, $plugin_file, $plugin_data, $context ) {

		// add a 'Configure' link to the front of the actions list for this plugin

		return array_merge( array( 'settings' => '' . sprintf( '<a href="admin.php?page=wc-settings&tab=invoices">%s</a>', __( 'Settings','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ) )),$actions );

	}
}


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
if(!function_exists('phoen_pdf_run_pdf_invoices_packing_slip_shipping_label_for_woocommerce')){

	function phoen_pdf_run_pdf_invoices_packing_slip_shipping_label_for_woocommerce() {

		$plugin = new Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce();
		$plugin->run();

	}
}
phoen_pdf_run_pdf_invoices_packing_slip_shipping_label_for_woocommerce();
