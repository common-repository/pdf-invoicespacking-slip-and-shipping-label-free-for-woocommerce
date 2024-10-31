(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	/*
	 * Select/Upload image(s) event
	 */

	jQuery(document).ready(function (jQuery) {
		jQuery('.colorpick1').each(function () {
			jQuery(this).wpColorPicker();
		});
		jQuery('.colorpick2').each(function () {
			jQuery(this).wpColorPicker();
		});
		jQuery('.colorpick3').each(function () {
			jQuery(this).wpColorPicker();
		});
		jQuery('.colorpick4').each(function () {
			jQuery(this).wpColorPicker();
		});
		jQuery('.colorpick5').each(function () {
			jQuery(this).wpColorPicker();
		});
		jQuery('.colorpick6').each(function () {
			jQuery(this).wpColorPicker();
		});
		jQuery('.colorpick7').each(function () {
			jQuery(this).wpColorPicker();
		});
		jQuery('.colorpick8').each(function () {
			jQuery(this).wpColorPicker();
		});
		jQuery('.colorpick9').each(function () {
			jQuery(this).wpColorPicker();
		});
		jQuery('.colorpick10').each(function () {
			jQuery(this).wpColorPicker();
		});
		jQuery('.colorpick11').each(function () {
			jQuery(this).wpColorPicker();
		});
		jQuery('.colorpick12').each(function () {
			jQuery(this).wpColorPicker();
		});
		jQuery('.colorpick13').each(function () {
			jQuery(this).wpColorPicker();
		});
		jQuery('.colorpick14').each(function () {
			jQuery(this).wpColorPicker();
		});
		jQuery('.colorpick15').each(function () {
			jQuery(this).wpColorPicker();
		});
		jQuery('.colorpick16').each(function () {
			jQuery(this).wpColorPicker();
		});
	});

	jQuery(document).ready(function () {
		jQuery('#woocommerce_invoice_set_status_mail').select2();
		jQuery('#woocommerce_proforma_set_status_mail').select2();

		if (jQuery("#woocommerce_invoice_enable_email_address").prop('checked') == true) {
			jQuery("#woocommerce_invoice_company_email").parents("tr").show();
		} else {
			jQuery("#woocommerce_invoice_company_email").parents("tr").hide();
		}
		if (jQuery("#woocommerce_invoice_enable_phone_number").prop('checked') == true) {
			jQuery("#woocommerce_invoice_company_phone").parents("tr").show();
		} else {
			jQuery("#woocommerce_invoice_company_phone").parents("tr").hide();
		}

		jQuery('#woocommerce_invoice_enable_email_address').click(function () {
			if (jQuery(this).prop("checked") == true) {
				jQuery("#woocommerce_invoice_company_email").parents("tr").show();
			}
			else if (jQuery(this).prop("checked") == false) {
				jQuery("#woocommerce_invoice_company_email").parents("tr").hide();
			}
		});

		jQuery('#woocommerce_invoice_enable_phone_number').click(function () {
			if (jQuery(this).prop("checked") == true) {
				jQuery("#woocommerce_invoice_company_phone").parents("tr").show();
			}
			else if (jQuery(this).prop("checked") == false) {
				jQuery("#woocommerce_invoice_company_phone").parents("tr").hide();
			}
		});
	});

})(jQuery);
