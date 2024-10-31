<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://phoeniixx.com
 * @since      1.0.0
 *
 * @package    Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce
 * @subpackage Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce
 * @subpackage Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce/includes
 * @author     phoeniixx <support@phoeniixx.com>
 */
if(!class_exists('Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce')){

	class Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce {

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $version    The current version of the plugin.
		 */
		protected $version;

		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
			if ( defined( 'PHOEN_PDF_INVOICES_PACKING_SLIP_SHIPPING_LABEL_FOR_WOOCOMMERCE_VERSION' ) ) {
				$this->version = PHOEN_PDF_INVOICES_PACKING_SLIP_SHIPPING_LABEL_FOR_WOOCOMMERCE_VERSION;
			} else {
				$this->version = '1.0.0';
			}
			$this->plugin_name = 'pdf-invoices-packing-slip-shipping-label-for-woocommerce';

			$this->load_dependencies();
			$this->set_locale();
			$this->define_admin_hooks();
			$this->define_public_hooks();

		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
		 * - Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_i18n. Defines internationalization functionality.
		 * - Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_Admin. Defines all hooks for the admin area.
		 * - Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_Public. Defines all hooks for the public side of the site.
		 *
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */
			require_once PHOENPDFINVOICEPLUGINDIRPATH . 'includes/class-pdf-invoices-packing-slip-shipping-label-for-woocommerce-loader.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once PHOENPDFINVOICEPLUGINDIRPATH . 'includes/class-pdf-invoices-packing-slip-shipping-label-for-woocommerce-i18n.php';

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once PHOENPDFINVOICEPLUGINDIRPATH . 'admin/class-pdf-invoices-packing-slip-shipping-label-for-woocommerce-admin.php';

			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once PHOENPDFINVOICEPLUGINDIRPATH . 'public/class-pdf-invoices-packing-slip-shipping-label-for-woocommerce-public.php';

			$this->loader = new Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_Loader();

		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_i18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function set_locale() {

			$plugin_i18n = new Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_i18n();

			$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_admin_hooks() {

			$plugin_admin = new Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_Admin( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_public_hooks() {

			$plugin_public = new Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_Public( $this->get_plugin_name(), $this->get_version() );

		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    1.0.0
		 */
		public function run() {
			$this->loader->run();
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since     1.0.0
		 * @return    string    The name of the plugin.
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @since     1.0.0
		 * @return    Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
		 */
		public function get_loader() {
			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @since     1.0.0
		 * @return    string    The version number of the plugin.
		 */
		public function get_version() {
			return $this->version;
		}

	}
}
