<?php

/**
 * Fired during plugin activation
 *
 * @link       https://phoeniixx.com
 * @since      1.0.0
 *
 * @package    Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce
 * @subpackage Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce
 * @subpackage Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce/includes
 * @author     phoeniixx <support@phoeniixx.com>
 */
if(!class_exists('Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_Activator')){

	class Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_Activator {

		/**
		 * Short Description. (use period)
		 *
		 * Long Description.
		 *
		 * @since    1.0.0
		 */
		public static function activate() {
			
			if(!get_option('woocommerce_invoice_enable_invoice_plugin')){

				update_option('woocommerce_invoice_enable_invoice_plugin', 'yes');
				update_option('woocommerce_invoice_enable_myaccount', 'yes');
				update_option('woocommerce_settings_invoice_pdf_cname', '');
				update_option('woocommerce_settings_invoice_pdf_invoice_number', 1);
				update_option('woocommerce_settings_invoice_pdf_invoice_prefix', date('d'));
				update_option('woocommerce_settings_invoice_pdf_invoice_suffix', date('Y'));
				update_option('woocommerce_settings_invoice_pdf_invoice_caddress', '');
				update_option('woocommerce_settings_invoice_pdf_invoice_terms_conditions', '');
		
				// plugin activation code here for header setting...
				update_option('woocommerce_invoice_enable_reg_num', 'yes');
				update_option('woocommerce_company_tax_name', 'GST');
				update_option('woocommerce_company_tax_number', '123456');
				update_option('woocommerce_invoice_tin_number', '987654');
				update_option('woocommerce_invoice_number_format', '[prefix]/[number]/[suffix]');
				update_option('woocommerce_invoice_reset', 'yes');
				update_option('woocommerce_invoice_date_format', 'd/m/Y');
				update_option('woocommerce_invoice_enable_logo', 'yes');
				update_option('woocommerce_invoice_enable_invoice_num', 'yes');
				update_option('woocommerce_invoice_enable_email_address', 'yes');
				update_option('woocommerce_invoice_enable_comapny_name', 'yes');
				update_option('woocommerce_invoice_enable_store_address', 'yes');
				update_option('woocommerce_invoice_enable_billing_address', 'yes');
				update_option('woocommerce_invoice_enable_shipping_address', 'yes');
				update_option('woocommerce_invoice_enable_order_number', 'yes');
				update_option('woocommerce_invoice_enable_invoice_date', 'yes');
				update_option('woocommerce_invoice_enable_order_date', 'yes');
		
				// plugin activation code here for Documnet & Product list setting...
				update_option('woocommerce_invoice_generate', 'manual_generation');
				update_option('woocommerce_invoice_enable_pending_order', '');
				update_option('woocommerce_invoice_enable_processing_order', '');
				update_option('woocommerce_invoice_enable_on_hold_order', '');
				update_option('woocommerce_invoice_enable_completed_order', 'yes');
				update_option('woocommerce_invoice_enable_cancelled_order', '');
				update_option('woocommerce_invoice_enable_refunded_order', '');
				update_option('woocommerce_invoice_enable_failed_order', '');
				update_option('woocommerce_invoice_generate_automatic', 'a4-sheet');
				update_option('woocommerce_invoice_behavior', 'browser');
				update_option('woocommerce_invoice_name_format', 'Invoice_[number]');
				update_option('woocommerce_invoice_enable_product_image', 'yes');
				update_option('woocommerce_invoice_enable_product_sku', 'yes');
				update_option('woocommerce_invoice_enable_product_variation', 'yes');
				update_option('woocommerce_invoice_enable_product_description', 'yes');
				update_option('woocommerce_invoice_enable_product_price', 'yes');
				update_option('woocommerce_invoice_enable_sale_price', 'yes');
				update_option('woocommerce_invoice_enable_regular_price', 'yes');
				update_option('woocommerce_invoice_enable_product_quantity', 'yes');
				update_option('woocommerce_invoice_enable_currency_symbol', 'yes');
				update_option('woocommerce_invoice_enable_product_line_total', 'yes');
				update_option('woocommerce_invoice_enable_product_line_tax', 'yes');
				// plugin activation code here for Documnet & Product list setting...
				update_option('woocommerce_invoice_cdetails', 'Put here your company details...');
				update_option('woocommerce_invoice_notes', 'Put here your  invoice notes');
				update_option('woocommerce_invoice_footer', 'Put here your invoice footer content');
				update_option('woocommerce_invoice_enable_company_details', 'yes');
				update_option('woocommerce_invoice_enable_invoice_notes', 'yes');
				update_option('woocommerce_invoice_enable_footer', 'yes');
				update_option('woocommerce_invoice_enable_inclusive_tax', 'yes');
				
				update_option('woocommerce_invoice_enable_total_tax', 'yes');
				update_option('woocommerce_invoice_enable_total_shipping', 'yes');
				update_option('woocommerce_invoice_enable_shipping_method', 'yes');
				update_option('woocommerce_invoice_enable_payment_method', 'yes');
				update_option('woocommerce_invoice_enable_subtotal', 'yes');
				update_option('woocommerce_invoice_enable_invoice_total', 'yes');
		
				// plugin activation code here for Packing Slip setting...
				update_option('woocommerce_packing_notes', 'Put here packing slip notes');
				update_option('woocommerce_packing_footer', 'Put here packing slip footer details');
				update_option('woocommerce_invoice_enable_packing_slip', 'yes');
				update_option('woocommerce_invoice_enable_order_total', 'yes');
				update_option('woocommerce_invoice_enable_packing_notes', 'yes');
				update_option('woocommerce_invoice_enable_packing_footer', 'yes');
		
				update_option('woocommerce_invoice_enable_pck_logo', 'yes');
				update_option('woocommerce_invoice_enable_pck_email_address', 'yes');
				update_option('woocommerce_invoice_enable_pck_phone_number', 'yes');
				update_option('woocommerce_invoice_enable_pck_comapny_name', 'yes');
				update_option('woocommerce_invoice_enable_pck_billing_address', 'yes');
				update_option('woocommerce_invoice_enable_pck_store_address', 'yes');
				update_option('woocommerce_invoice_enable_pck_order_number', 'yes');
				update_option('woocommerce_invoice_enable_pck_order_date', 'yes');
		
				update_option('woocommerce_invoice_enable_product_pck_image', 'yes');
				update_option('woocommerce_invoice_enable_product_pck_sku', 'yes');
				update_option('woocommerce_invoice_enable_product_pck_variation', 'yes');
				update_option('woocommerce_invoice_enable_product_pck_description', 'yes');
				update_option('woocommerce_invoice_enable_product_pck_price', 'yes');
				update_option('woocommerce_invoice_enable_pck_sale_price', 'yes');
				update_option('woocommerce_invoice_enable_pck_regular_price', 'yes');
				update_option('woocommerce_invoice_enable_product_pck_quantity', 'yes');
				update_option('woocommerce_invoice_enable_product_pck_line_total', 'yes');
				update_option('woocommerce_invoice_enable_product_pck_line_tax', 'yes');
		
				update_option('woocommerce_invoice_enable_pck_inc_tax', 'yes');
				update_option('woocommerce_invoice_enable_pck_discount', 'yes');
				update_option('woocommerce_invoice_enable_pck_total_tax', 'yes');
				update_option('woocommerce_invoice_enable_pck_subtotal', 'yes');
				update_option('woocommerce_invoice_enable_pck_invoice_total', 'yes');
		
				// plugin activation code here for Proforma Invoice setting...
				update_option('woocommerce_proforma_enable_invoice_plugin', 'yes');
				update_option('woocommerce_proforma_enable_myaccount', 'yes');
				
				// plugin activation code here for Style setting...
				update_option('woocommerce_invoice_header_background_color', '#fff');
				update_option('woocommerce_invoice_header_text_color', '#111111');
				update_option('woocommerce_invoice_product_list_header_background_color', '#fd4e23');
				update_option('woocommerce_invoice_product_list_header_text_color', '#fff');
				update_option('woocommerce_invoice_price_total_background_color', '#fd4e23');
				update_option('woocommerce_invoice_price_total_text_color', '#fff');
				update_option('woocommerce_invoice_footer_background_color', '#fff');
				update_option('woocommerce_invoice_footer_text_color', '#111111111');
			}

		}

	}
}
