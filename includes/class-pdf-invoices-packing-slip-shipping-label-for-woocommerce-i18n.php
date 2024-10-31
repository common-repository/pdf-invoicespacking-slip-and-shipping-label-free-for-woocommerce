<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://phoeniixx.com
 * @since      1.0.0
 *
 * @package    Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce
 * @subpackage Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce
 * @subpackage Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce/includes
 * @author     phoeniixx <support@phoeniixx.com>
 */
if(!class_exists('Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_i18n')){
	class Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_i18n {


		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since    1.0.0
		 */
		public function load_plugin_textdomain() {
	
			load_plugin_textdomain(
				'pdf-invoices-packing-slip-shipping-label-for-woocommerce',
				false,
				dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);
	
		}
	
	
	
	}

}

