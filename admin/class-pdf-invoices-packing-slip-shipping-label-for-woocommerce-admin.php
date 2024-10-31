<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once(ABSPATH . "wp-admin" . '/includes/image.php');
require_once(ABSPATH . "wp-admin" . '/includes/file.php');
require_once(ABSPATH . "wp-admin" . '/includes/media.php');
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://phoeniixx.com
 * @since      1.0.0
 *
 * @package    Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce
 * @subpackage Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce
 * @subpackage Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce/admin
 * @author     phoeniixx <support@phoeniixx.com>
 */

if(!class_exists( 'Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_Admin') ) {
	class Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_Admin {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;
		
		/**
		 * The setting_menu of this plugin.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      array    $setting_menu    The setting_menu of this plugin.
		 */
		
		public $setting_menu = array();
		
		/**
		 * The plugin_dir_url of this plugin.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string  $plugin_dir_url    The plugin_dir_url of this plugin.
		 */
		
		public $plugin_dir_url = PHOENPDFINVOICEPLUGINURL;
		
		

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string    $plugin_name       The name of this plugin.
		 * @param      string    $version    The version of this plugin.
		 */
		
		public function __construct( $plugin_name, $version) {

			$this->plugin_name = $plugin_name;
			$this->version = $version;
			
			add_action( 'woocommerce_admin_field_file' , __CLASS__ . '::phoen_add_admin_field_file' );
			add_action( 'woocommerce_admin_field_img' , __CLASS__ . '::phoen_add_admin_field_img' );
			add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
			
			add_action( 'woocommerce_sections_invoices', array( $this, 'phoen_settings_pdf_sections' ) );
			add_action( 'woocommerce_settings_tabs_invoices',  __CLASS__ .  '::phoen_settings_tab_invoice_pdf' );
			add_action( 'woocommerce_update_options_invoices',  __CLASS__ .  '::phoen_settings_tab_invoice_pdf_update' );
			if(get_option('woocommerce_invoice_enable_invoice_plugin') == 'yes'){
				add_action( 'manage_edit-shop_order_columns',  __CLASS__ .  '::phoen_pdf_genrator_add_column' );
				if(get_option('woocommerce_invoice_enable_myaccount') == 'yes'){
					add_filter( 'woocommerce_my_account_my_orders_actions',  __CLASS__ .  '::phoen_invoice_account_order_actions', 10, 2 );
				}
				
			}
			add_action( 'manage_shop_order_posts_custom_column',  __CLASS__ .  '::phoen_pdf_genrator_add_new_column_content' );
			if(array_key_exists("order_id", $_GET) && array_key_exists("doc", $_GET)){
			$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : "";
			}
			if($order_id){
				add_action( 'init',  __CLASS__ .  '::phoen_generate_pdf_invoice_woocommerce' );
			}
		}

		public function get_sections() {
			return apply_filters( 'woocommerce_get_sections_' . $this->id, array() );
		}
		
		public function phoen_settings_pdf_sections() {
			global $current_section;
			if(isset($_GET['section'])){//here we get tab reference to open subtab in template setting
				$section = sanitize_text_field($_GET['section']);
			}
			?>
			<ul class="subsubsub">
				<li><a href="admin.php?page=wc-settings&tab=invoices&section=general" class="<?php echo ($section == '' || $section == 'general') ? 'current' : '' ?>"><?php _e("General","phoen-pdf-invoice-for-woocommerce");?></a> <?php _e("|","phoen-pdf-invoice-for-woocommerce");?></li>
				<li><a href="admin.php?page=wc-settings&tab=invoices&section=header" class="<?php echo ($section == 'header') ? 'current' : '' ?>"><?php _e("Header Section","phoen-pdf-invoice-for-woocommerce");?></a> <?php _e("|","phoen-pdf-invoice-for-woocommerce");?></li>
				<li><a href="admin.php?page=wc-settings&tab=invoices&section=document"  class="<?php echo ($section == 'document') ? 'current' : '' ?>"><?php _e("Document & Product Section","phoen-pdf-invoice-for-woocommerce");?></a> <?php _e("|","phoen-pdf-invoice-for-woocommerce");?> </li>
				<li><a href="admin.php?page=wc-settings&tab=invoices&section=template"  class="<?php echo ($section == 'template') ? 'current' : '' ?>"><?php _e("Footer & Total Section","phoen-pdf-invoice-for-woocommerce");?></a> <?php _e("|","phoen-pdf-invoice-for-woocommerce");?></li>
				<li><a href="admin.php?page=wc-settings&tab=invoices&section=packing"  class="<?php echo ($section == 'packing') ? 'current' : '' ?>"><?php _e("Packing Slip","phoen-pdf-invoice-for-woocommerce");?></a> <?php _e("|","phoen-pdf-invoice-for-woocommerce");?></li>
				<li><a href="admin.php?page=wc-settings&tab=invoices&section=proforma"  class="<?php echo ($section == 'proforma') ? 'current' : '' ?>"><?php _e("Proforma Invoice","phoen-pdf-invoice-for-woocommerce");?></a> <?php _e("|","phoen-pdf-invoice-for-woocommerce");?></li>
				<li><a href="admin.php?page=wc-settings&tab=invoices&section=style"  class="<?php echo ($section == 'style') ? 'current' : '' ?>"><?php _e("Invoice & Packing Slip Styling","phoen-pdf-invoice-for-woocommerce");?></a></li>
			</ul>
			<br class="clear"/>
			<?php
		}
		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {

			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */
			wp_register_style( 'select2_css_min', plugin_dir_url( __FILE__ ) . 'css/pdf-invoices-packing-slip-shipping-label-for-woocommerce-select2-min.css', array(), $this->version, 'all' );
			wp_enqueue_style('select2_css_min');
			wp_enqueue_style( 'wp-color-picker' );
			
		}


		
		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {

			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Phoen_Pdf_Invoices_Packing_Slip_Shipping_Label_For_Woocommerce_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */
			wp_register_script( 'select2_js_min', plugin_dir_url( __FILE__ ) . 'js/pdf-invoices-packing-slip-shipping-label-for-woocommerce-select2-min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script('select2_js_min');
			wp_enqueue_script( 'wp-color-picker');
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pdf-invoices-packing-slip-shipping-label-for-woocommerce-admin.js', array( 'jquery' ), $this->version, false );

		}

		public function phoen_add_admin_field_img(){
			$image = wp_get_attachment_image_src( get_option('woocommerce_settings_invoice_pdf_invoice_logo'), 'full' );
			?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
					<?php echo  $description['tooltip_html'];?>
				</th>
				
				<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
					<img src="<?= ($image[0]) ? $image[0] : 'https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcSa_HpDtj8sgDuxM3WyyQr2Tx7zONuRlP0zWtMtrLjq9Ex_8kG2' ?>" id="img-logo"/>
				</td>
			</tr>
		<?php }

		public function phoen_add_admin_field_file( $value ){
			$option_value = (array) WC_Admin_Settings::get_option( $value['id'] );
			$description = WC_Admin_Settings::get_field_description( $value );
			?>
		
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
					<?php echo  $description['tooltip_html'];?>
				</th>
				
				<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
			
					<input
							name ="<?php echo esc_attr( $value['id'] ); ?>"
							id   ="<?php echo esc_attr( $value['id'] ); ?>"
							type ="<?php echo sanitize_title( $value['type'] ) ?>"
							style="<?php echo esc_attr( $value['css'] ); ?>"
							value="<?php echo esc_attr( $value['name'] ); ?>"
							class="<?php echo esc_attr( $value['class'] ); ?>"
					/> 
					<?php echo $description['description']; ?>
				</td>
			</tr>

		<?php       
		}

		public function phoen_invoice_account_order_actions( $actions, $order ) {
			$url = '?order_id='.$order->ID.'&doc=invoice';
			$actions['invoice_name'] = array(
				'url'  => $url,
				'name' => __('Download Invoice','pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
			);
			return $actions;
		}
		
		
		public static function phoen_generate_pdf_invoice_woocommerce() {
			include_once(PHOENPDFINVOICEPLUGINDIRPATH.'admin/pdf_generate.php');
		}
		public static function phoen_pdf_genrator_add_column( $columns ) {
			$columns['phoen-pdf-genrator'] = __('Invoice','pdf-invoices-packing-slip-shipping-label-for-woocommerce');
			return $columns;
		}
		
		public static function phoen_pdf_genrator_add_new_column_content( $column ) {
		
			global $post;
		
			if ( 'phoen-pdf-genrator' === $column ) {
			
				$order = wc_get_order( $post->ID );
				$data_packing_slip_setting = get_option('data_packing_slip_setting');
				?>
				<a href="<?php echo '?post_type=shop_order&order_id='.$post->ID.'&doc=invoice';?>" class="button save_order button-primary" style="margin-bottom:10px; display: inline-block;" <?php if(get_option('woocommerce_invoice_behavior') == 'browser'){?> target="_blank" <?php } ?>><?php _e("Invoice","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?></a><br/>
			<?php
				
			}
		}
		
		public static function add_settings_tab( $settings_tabs ) {
			$settings_tabs['invoices'] = __( 'Invoice', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' );
			return $settings_tabs;
		}
		
		public static function phoen_settings_tab_invoice_pdf() {
			woocommerce_admin_fields( self::phoen_pdf_get_settings_tab_invoice_pdf() );
		}
		
		public static function phoen_settings_tab_invoice_pdf_update() {
			woocommerce_update_options( self::phoen_pdf_get_settings_tab_invoice_pdf() );
		}
		
		public static function phoen_pdf_get_settings_tab_invoice_pdf() {
			if(isset($_GET['section'])){//here we get tab reference to open subtab in template setting
				$section = sanitize_text_field($_GET['section']);
			}
			if (isset($_FILES['woocommerce_settings_invoice_pdf_invoice_logo']) && $_FILES['woocommerce_settings_invoice_pdf_invoice_logo']['error'] == 0) {
				$attachment_id = media_handle_upload( 'woocommerce_settings_invoice_pdf_invoice_logo' , 0);// here we are upload company logo by media_handle_upload
				if ( is_numeric( $attachment_id ) ) {
					update_option('woocommerce_settings_invoice_pdf_invoice_logo', $attachment_id);
				}
			}else{
				$attachment_id = get_option('woocommerce_settings_invoice_pdf_invoice_logo');
			}
			
			if($section == '' || $section == 'general'){
				$settings = array(
					'section_title' => array(
						'name'     => __( 'General Settings', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'phoen_invoce_settings_section_title'
					),
					'enable_plugin' => array(
						'name' => __( 'Enable Plugin', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable invoice plugin','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_invoice_plugin'
					),
					'enable_myaccount' => array(
						'name' => __( 'Enable My Account Page', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Download Invoice from My Account Page' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'id'   => 'woocommerce_invoice_enable_myaccount'
					),
					
					'Invoice Number' => array(
						'name' => __( 'Invoice Number', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable invoice number on invoice pdf','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_invoice_num'
					),
					'invoice_number' => array(
						'name' => __( 'Invoice Number', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('Set intial invoice number for beginning','pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_settings_invoice_pdf_invoice_number'
					),
					'invoice_prefix' => array(
						'name' => __( 'Invoice Prefix', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('Set a text to be used as prefix in invoice number. Leave it blank if no prefix has to be used','pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_settings_invoice_pdf_invoice_prefix'
					),
					'invoice_suffix	' => array(
						'name' => __( 'Invoice Suffix', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('Set a text to be used as suffix in invoice number. Leave it blank if no suffix has to be used','pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_settings_invoice_pdf_invoice_suffix'
					),
					'invoice_number_format' => array(
						'name' => __( 'Invoice Number Format', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => true,
						'desc' => __( 'Set format for invoice number. Use [number], [prefix] and [suffix] as placeholders' ),
						'id'   => 'woocommerce_invoice_number_format'
					),
					'invoice_reset' => array(
						'name' => __( 'Reset on 1st January', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable reset invoice number on 1st janaury every year <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_reset',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'invoice_date_format' => array(
						'name' => __( 'Date Format', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => true,
						'desc' => __( 'Set date format as it should appear on invoices.','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_date_format'
					),
					'invoice_terms_conditions' => array(
						'name' => __( 'Terms & Conditions', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'textarea',
						'desc_tip' => __('Set terms and conditions that will display in the invoice','pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_settings_invoice_pdf_invoice_terms_conditions'
					),
					
					'section_end' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end'
					)
				);
			}
			if($section == 'header'){
				$settings = array(
					'section_title' => array(
						'name'     => __( 'Header Settings', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'phoen_invoce_settings_section_title'
					),
					'invoice_enable_reg_num' => array(
						'name' => __( 'Show company registeration number', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable/Disable company registeration number <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_reg_num',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'company_tax_name' => array(
						'name' => __( 'Tax Name', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => true,
						'desc' => __( 'Set company tax name <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_company_tax_name',
						'custom_attributes'=>array('disabled'=>'disabled')

					),
					'company_tax_number' => array(
						'name' => __( 'Tax Number', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => true,
						'desc' => __( 'Set company tax number <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_company_tax_number',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'invoice_tin_number' => array(
						'name' => __( 'Company TIN Number', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => true,
						'desc' => __( 'Set company TIN number <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'id'   => 'woocommerce_invoice_tin_number',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'Company Name' => array(
						'name' => __( 'Company Name', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Show company name on invoice document' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'id'   => 'woocommerce_invoice_enable_comapny_name'
					),
					'cname' => array(
						'name' => __( 'Company Name', 'woocommerce-settings-tab-demo' ),
						'type' => 'text',
						'desc_tip' => __('Set company name to be shown on invoices','pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_settings_invoice_pdf_cname'
					),
					'Company Logo' => array(
						'name' => __( 'Company Logo', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Show company logo on invoice document' ),
						'id'   => 'woocommerce_invoice_enable_logo'
					),
					'invoice_logo' => array(
						'name' => __( 'Upload Logo', 'pdf-invoices-packing-slip-shipping-label-for-woocommerc' ),
						'type' => 'file',
						'desc_tip' => __('Choose company logo','pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_settings_invoice_pdf_invoice_logo'
					),
					'invoice_img_src' => array(
						'type' => 'img',
						'id'   => 'woocommerce_settings_invoice_pdf_img_src'
					),
					'Email Address' => array(
						'name' => __( 'Email Address', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable email address on invoice pdf','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_email_address'
					),
					'company_email' => array(
						'name' => __( 'Company Email', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => true,
						'desc' => __( 'Set company email will be shown on invoice or packing slip','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_company_email'
					),
					'Phone Number' => array(
						'name' => __( 'Phone Number', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable phone number on invoice pdf','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_phone_number'
					),
					'company_phone' => array(
						'name' => __( 'Company Phone', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => true,
						'desc' => __( 'Set company phone number will be shown on invoice or packing slip','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_company_phone'
					),
					
					'Store Address' => array(
						'name' => __( 'Store Address', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable store address','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_store_address'
					),
					'Billing Address' => array(
						'name' => __( 'Billing Address', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable billing address','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_billing_address'
					),
					'Shipping Address' => array(
						'name' => __( 'Shipping Address', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable shipping address' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'id'   => 'woocommerce_invoice_enable_shipping_address'
					),
					'Order Number' => array(
						'name' => __( 'Order Number', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable order number on invoice pdf','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_order_number'
					),
					'Invoice Date' => array(
						'name' => __( 'Invoice Date', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable invoice date on invoice pdf','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_invoice_date'
					),
					'Order Date' => array(
						'name' => __( 'Order Date', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable order date on invoice pdf','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_order_date'
					),
					'section_end' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end'
					)
				);
			}
			if($section == 'document'){
				$settings = array(
					'section_title_document_settings' => array(
						'name'     => __( 'Document Settings', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'phoen_invoce_settings_section_title'
					),
					'invoice_generate' => array(
						'name' => __( 'Invoice Generate', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'radio',
						'desc_tip' => __('Set invoice generations both automatic and manual','pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						
						'options' => array(
							'automatic_generation' => __( 'Automatic ', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
							'manual_generation' => __( 'Manual ', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						),
						'id'   => 'woocommerce_invoice_generate',
						'default'    => 'automatic_generation',
						

					),
					'set_order_status' => array(
						'name' => __( 'Set Order Status', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'multiselect',
						'desc_tip' => 'Set order status to send an invoice.',
						'desc' => __( '<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'options' => array(
							'woocommerce_invoice_enable_all' => __( 'All', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
							'woocommerce_invoice_enable_pending_order' => __( 'Pending', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
							'woocommerce_invoice_enable_processing_order' => __( 'Processing', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
							'woocommerce_invoice_enable_on_hold_order' => __( 'On Hold', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
							'woocommerce_invoice_enable_completed_order' => __( 'Completed', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
							'woocommerce_invoice_enable_cancelled_order' => __( 'Cancelled', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
							'woocommerce_invoice_enable_refunded_order' => __( 'Refunded', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
							'woocommerce_invoice_enable_failed_order' => __( 'Automatic', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						),
						'id'   => 'woocommerce_invoice_set_status_mail',
						'default'    => 'woocommerce_invoice_enable_completed_order',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'paper_size' => array(
						'name' => __( 'Paper Size', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'select',
						'desc_tip' => __('Set paper size of document','pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'desc' => __( '<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'options' => array(
							'letter' => __( 'Letter', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
							'a4-sheet' => __( 'A4 Sheet', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						),
						'id'   => 'woocommerce_invoice_generate_automatic',
						'default'    => 'letter',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'invoice_behavior' => array(
						'name' => __( 'PDF Invoice Behavior', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'radio',
						'desc_tip' => __('Set invoice document open behavior both download and browser','pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'options' => array(
							'download' => __( 'Download PDF', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
							'browser' => __( 'Open PDF On Browser', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' )
						),
						'id'   => 'woocommerce_invoice_behavior',
						'default'    => 'browser',
						
					),
					'name_format' => array(
						'name' => __( 'Invoice file name format', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => true,
						'desc' => __( 'Set the format for the invoice file name. Use [number], [prefix], [suffix], [year], [month], [day] as placeholders.	The[number] placeholder is necessary.if not specified, it will be queued to the corresponding text.', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_name_format',
						
					),
					'section_end_document_settings' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end_document_settings'
					),
					'section_title_product_list_settings' => array(
						'name'     => __( 'Product List Settings', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'phoen_invoce_settings_section_title_product_list_settings'
					),
					'product_image' => array(
						'name' => __( 'Product Image', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable product image on invoice pdf ','pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'id'   => 'woocommerce_invoice_enable_product_image',
						
					),
					'product_sku' => array(
						'name' => __( 'Product SKU', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable product sku on invoice pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_product_sku',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'product_variation' => array(
						'name' => __( 'Product Variation', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( ' Enable product variation on invoice pdf ' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'id'   => 'woocommerce_invoice_enable_product_variation',
						
					),
					'product_description' => array(
						'name' => __( 'Product Description', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( ' Enable short description on invoice pdf ','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_product_description',
						
					),
					
					'product_price' => array(
						'name' => __( 'Product Price', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable particular product price on invoice pdf ','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_product_price',
						
					),
					'product_sale_price' => array(
						'name' => __( 'Sale Price', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable sales price on invoice pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_sale_price',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'product_regular_price' => array(
						'name' => __( 'Regular Price', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable regular price on invoice pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_regular_price',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'product_quantity' => array(
						'name' => __( 'Product Quantity', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable product quantity on invoice pdf ','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_product_quantity',
						
					),
					'product_line_total' => array(
						'name' => __( 'Line Total', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable line total on invoice pdf ','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_product_line_total',
						
					),
					'product_line_tax' => array(
						'name' => __( 'Line Tax', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable line tax on invoice pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_product_line_tax',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'section_end_product_list_settings' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end_product_list_settings'
					)
				);
			}
			if($section == 'template'){
				$settings = array(
					'section_title_footer_settings' => array(
						'name'     => __( 'Invoice Footer Settings', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'phoen_invoce_settings_section_title_footer_settings'
					),
					'Show Company Details' => array(
						'name' => __( 'Show Company Details', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Show company details on invoice document','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_company_details'
					),
					'Show Notes' => array(
						'name' => __( 'Show Notes', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Show notes before the footer' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_invoice_notes'
					),
					'Show Footer' => array(
						'name' => __( 'Show Footer', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Show footer on invoice document','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_footer'
					),
					'invoice_cdetails' => array(
						'name' => __( 'Company Details', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'textarea',
						'placeholder' => 'Enter company details',
						'desc_tip' => __('Set your company details that will be shown on the invoice document.','pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_cdetails'
					),
					'invoice_notes' => array(
						'name' => __( 'Invoice Notes', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'textarea',
						'desc_tip' => __('Set note that will be shown on the invoice document.','pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_notes'
					),
					'invoice_footer' => array(
						'name' => __( 'Invoice Footer', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'textarea',
						'desc_tip' => __('Set footer content that will be shown on the invoice document.','pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_footer'
					),
					'section_end_footer_settings' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end_footer_settings'
					),
					'section_title_total_settings' => array(
						'name'     => __( 'Invoice Total Settings', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'phoen_invoce_settings_section_title_total_settings'
					),
				
					'Show Discount' => array(
						'name' => __( 'Show Discount', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable discount on invoice pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_discount',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'Total Tax' => array(
						'name' => __( 'Total Tax', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable total tax on invoice pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_total_tax',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'Total Shipping' => array(
						'name' => __( 'Total Shipping', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable total shipping on invoice pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_total_shipping',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'Shipping Method' => array(
						'name' => __( 'Shipping Method', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable shipping method on invoice pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_shipping_method',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'Payment Method' => array(
						'name' => __( 'Payment Method', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable payment method on invoice pdf<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a> ' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_payment_method',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'Subtotal' => array(
						'name' => __( 'Subtotal', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable subtotal on invoice pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_subtotal'	,
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'Invoice Total' => array(
						'name' => __( 'Invoice Total', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable total on invoice pdf ','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_invoice_total',
							
					),
					'section_end_total_settings' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end_total_settings'
					)
				);
			}
			if($section == 'packing'){
				$settings = array(
					'document_pck_setting' => array(
						'name'     => __( 'Document Settings', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'wc_settings_tab_document_pck_setting'
					),
					'enable_packing_slip' => array(
						'name' => __( 'Packing Slip', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable Packing Slip <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_packing_slip',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_packing_notes' => array(
						'name' => __( 'Packing Notes', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable notes before the footer <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_packing_notes',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_packing_footer' => array(
						'name' => __( 'Packing Footer', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable Packing Footer <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_packing_footer',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'packing_notes' => array(
						'name' => __( 'Packing Notes', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'textarea',
						'desc_tip' => __(' Set notes that will be shown on the packing document','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_packing_notes',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'packing_footer' => array(
						'name' => __( 'Packing Footer', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'textarea',
						'desc_tip' => __('Set footer content that will be shown on the packing document','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_packing_footer',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_order_total' => array(
						'name' => __( 'Order Total', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable Order Total <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_order_total',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'section_end' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end'
					),
					'header_pck_setting' => array(
						'name'     => __( 'Packing Header Settings', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>',
						'id'       => 'wc_settings_tab_header_pck_setting',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_pck_logo' => array(
						'name' => __( 'Company Logo', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Show company logo on packing document <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_pck_logo',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_pck_email_address' => array(
						'name' => __( 'Email Address', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable email address on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_pck_email_address',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_pck_phone_number' => array(
						'name' => __( 'Phone Number', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable phone number on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_pck_phone_number',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_pck_company_name' => array(
						'name' => __( 'Company Name', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Show company name on packing document <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_pck_comapny_name',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_pck_billing_address' => array(
						'name' => __( 'Billing Address', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable billing address <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_pck_billing_address',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_pck_shipping_address' => array(
						'name' => __( 'Shipping Address', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable shipping address <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_pck_shipping_address',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_pck_store_address' => array(
						'name' => __( 'Store Address', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable Store address on <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_pck_store_address',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_pck_order_number' => array(
						'name' => __( 'Order Number', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable order number on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_pck_order_number',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_pck_order_date' => array(
						'name' => __( 'Order Date', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable order date on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_pck_order_date',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'section_end2' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end2'
					),
					'product_pck_setting' => array(
						'name'     => __( 'Product list Settings', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>',
						'id'       => 'wc_settings_tab_product_pck_setting',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'product_pck_image' => array(
						'name' => __( 'Product Image', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable product image on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_product_pck_image',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'product_pck_sku' => array(
						'name' => __( 'Product SKU', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable product sku on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_product_pck_sku',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'product_pck_variation' => array(
						'name' => __( 'Product Variation', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( ' Enable product variation on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_product_pck_variation',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'product_pck_description' => array(
						'name' => __( 'Product Description', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( ' Enable short description on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_product_pck_description',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'product_pck_price' => array(
						'name' => __( 'Product Price', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable particular product price on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce'),
						'id'   => 'woocommerce_invoice_enable_product_pck_price',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'product_pck_sale_price' => array(
						'name' => __( 'Sale Price', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable sales price on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_pck_sale_price',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'product_pck_regular_price' => array(
						'name' => __( 'Regular Price', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable regular price on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_pck_regular_price',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'product_quantity' => array(
						'name' => __( 'Product Quantity', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable product quantity on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_product_pck_quantity',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'product_line_total' => array(
						'name' => __( 'Line Total', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable line total on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_product_pck_line_total',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'product_line_tax' => array(
						'name' => __( 'Line Tax', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable line tax on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_product_pck_line_tax',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'section_end3' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end3'
					),
					'total_pck_setting' => array(
						'name'     => __( 'Order total Settings', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>',
						'id'       => 'wc_settings_tab_total_pck_setting',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_pck_shipping' => array(
						'name' => __( 'Total Shipping', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable total shipping on packing slip <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_pck_total_shipping',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_discount' => array(
						'name' => __( 'Show Discount', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable discount on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_pck_discount',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_total_tax' => array(
						'name' => __( 'Total Tax', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable total tax on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_pck_total_tax',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_subtotal' => array(
						'name' => __( 'Subtotal', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable subtotal on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_invoice_enable_pck_subtotal'	,
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_invoice_pck_total' => array(
						'name' => __( 'Invoice Total', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable total on packing pdf <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_invoice_enable_pck_invoice_total',
						'custom_attributes'=>array('disabled'=>'disabled')	
					),
					'section_end4' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end4'
					)
				);
			}
			if($section == 'proforma'){
				$settings = array(
					'section_title_proforma' => array(
						'name'     => __( 'Proforma Invoice Settings', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'phoen_invoce_settings_section_title_header',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_plugin' => array(
						'name' => __( 'Enable Proforma', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable proforma invoice <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_proforma_enable_invoice_plugin',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_myaccount' => array(
						'name' => __( 'Enable My Account Page', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Download Invoice from My Account Page <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>','pdf-invoices-packing-slip-shipping-label-for-woocommerce'  ),
						'id'   => 'woocommerce_proforma_enable_myaccount',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'enable_proforma' => array(
						'name' => __( 'Enable proforma notes', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable proforma notes <a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>' ,'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_enable_proforma_notes',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'proforma_notes' => array(
						'name' => __( 'Proforma Notes', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'textarea',
						'desc_tip' => __('Set note that will be shown on the proforma document.','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'id'   => 'woocommerce_proforma_notes',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'set_order_status_proforma' => array(
						'name' => __( 'Set Order Status', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'multiselect',
						'desc_tip' => __('Set order status to send a performa invoice.','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'options' => array(
							'woocommerce_proforma_enable_all' => __( 'All', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
							'woocommerce_proforma_enable_pending_order' => __( 'Pending', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
							'woocommerce_proforma_enable_processing_order' => __( 'Processing', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
							'woocommerce_proforma_enable_on_hold_order' => __( 'On Hold', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
							'woocommerce_proforma_enable_completed_order' => __( 'Completed', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
							'woocommerce_proforma_enable_cancelled_order' => __( 'Cancelled', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
							'woocommerce_proforma_enable_refunded_order' => __( 'Refunded', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
							'woocommerce_proforma_enable_failed_order' => __( 'Failed', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' )
						),
						'id'   => 'woocommerce_proforma_set_status_mail',
						'default'    => 'woocommerce_proforma_enable_processing_order',
						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'section_end_proforma' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end_proforma'
					)
				);
			}	
			if($section == 'style'){
				$settings = array(
					'section_title_header' => array(
						'name'     => __( 'Header Styling', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'phoen_invoce_settings_section_title_header'
					),
					'header_background_color' => array(
						'name'     => __( 'Header Background Color', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('This controls the invoice header background color','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'class' => 'colorpick1',
						'id'   => 'woocommerce_invoice_header_background_color'
					),
					'header_text_color' => array(
						'name'     => __( 'Header Text Color', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('This controls the invoice text color','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'class' => 'colorpick2',
						'id'   => 'woocommerce_invoice_header_text_color'
					),
					'section_end_header' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end_header'
					),
					'section_title_product_list' => array(
						'name'     => __( 'Product List Styling', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'phoen_invoce_settings_section_title_product_list'
					),
					'product_list_header_background_color' => array(
						'name'     => __( 'Product List Header Background Color', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('This controls the invoice product list header background color','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'class' => 'colorpick3',
						'id'   => 'woocommerce_invoice_product_list_header_background_color'
					),
					'product_text_color' => array(
						'name'     => __( 'Product List Text Color', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('This controls the product list text color','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'class' => 'colorpick4',
						'id'   => 'woocommerce_invoice_product_list_header_text_color'
					),
					'section_end_product_list' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end_product_list'
					),
					'section_title_price_total' => array(
						'name'     => __( 'Price Total Styling', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'phoen_invoce_settings_section_title_price_total'
					),
					'price_total_background_color' => array(
						'name'     => __( 'Price Total Background Color', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('This controls the invoice price total background color','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'class' => 'colorpick5',
						'id'   => 'woocommerce_invoice_price_total_background_color'
					),
					'price_total_text_color' => array(
						'name'     => __( 'Price Total Text Color', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('This controls the invoice price total text color','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'class' => 'colorpick6',
						'id'   => 'woocommerce_invoice_price_total_text_color'
					),
					'section_end_price_total' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end_price_total'
					)
					,
					'section_title_footer' => array(
						'name'     => __( 'Footer Styling', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'phoen_invoce_settings_section_title_footer'
					),
					'footer_background_color' => array(
						'name'     => __( 'Footer Background Color', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('This controls the invoice footer background color','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'class' => 'colorpick7',
						'id'   => 'woocommerce_invoice_footer_background_color'
					),
					'footer_text_color' => array(
						'name'     => __( 'Footer Text Color', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('This controls the invoice foote text color','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'class' => 'colorpick8',
						'id'   => 'woocommerce_invoice_footer_text_color'
					),
					'section_end_footer' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end_footer'
					),

					'section_title_packing_main' => array(
						'name'     => __( 'Packing Slip Styling', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'phoen_packing_settings_section_title_header'
					),
					'section_end_packing_main' => array(
						'type' => 'sectionend',
						'id' => 'phoen_packing_settings_section_end_packing_main'
					),


					'section_title_header_packing' => array(
						'name'     => __( 'Header Styling', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'phoen_invoce_settings_section_title_header_packing',

						'custom_attributes'=>array('disabled'=>'disabled')

					),
					'header_background_color_packing' => array(
						'name'     => __( 'Header Background Color', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('This controls the packing slip header background color','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'class' => 'colorpick9',
						'id'   => 'woocommerce_invoice_header_background_color_packing',

						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'header_text_color_packing' => array(
						'name'     => __( 'Header Text Color', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('This controls the packing slip text color','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'class' => 'colorpick10',
						'id'   => 'woocommerce_invoice_header_text_color_packing',

						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'section_end_header_packing' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end_header_packing'
					),
					'section_title_product_list_packing' => array(
						'name'     => __( 'Product List Styling', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'phoen_invoce_settings_section_title_product_list_packing',

						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'product_list_header_background_color_packing' => array(
						'name'     => __( 'Product List Header Background Color', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('This controls the packing slip product list header background color','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'class' => 'colorpick11',
						'id'   => 'woocommerce_invoice_product_list_header_background_color_packing',

						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'product_text_color_packing' => array(
						'name'     => __( 'Product List Text Color', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('This controls the packing slip product list text color','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'class' => 'colorpick12',
						'id'   => 'woocommerce_invoice_product_list_header_text_color_packing',

						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'section_end_product_list_packing' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end_product_list_packing'
					),
					'section_title_price_total_packing' => array(
						'name'     => __( 'Price Total Styling', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'phoen_invoce_settings_section_title_price_total_packing'
					),
					'price_total_background_color_packing' => array(
						'name'     => __( 'Price Total Background Color', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('This controls the packing slip price total background color','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'class' => 'colorpick13',
						'id'   => 'woocommerce_invoice_price_total_background_color_packing',

						'custom_attributes'=>array('disabled'=>'disabled')
					),
					'price_total_text_color_packing' => array(
						'name'     => __( 'Price Total Text Color', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('This controls the packing slip price total text color','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'class' => 'colorpick14',
						'id'   => 'woocommerce_invoice_price_total_text_color_packing',
						'custom_attributes'=>array('disabled'=>'disabled')

					),
					'section_end_price_total_packing' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end_price_total_packing'
					)
					,
					'section_title_footer_packing' => array(
						'name'     => __( 'Footer Styling', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'phoen_invoce_settings_section_title_footer_packing',
						'custom_attributes'=>array('disabled'=>'disabled')


					),
					'footer_background_color_packing' => array(
						'name'     => __( 'Footer Background Color', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('This controls the packing slip footer background color','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'class' => 'colorpick15',
						'id'   => 'woocommerce_invoice_footer_background_color_packing',
						'custom_attributes'=>array('disabled'=>'disabled')

					),
					'footer_text_color_packing' => array(
						'name'     => __( 'Footer Text Color', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'type' => 'text',
						'desc_tip' => __('This controls the packing slip foote text color','pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'desc' => __( '<a href="https://phoeniixx.com/product/pdf-invoicespacking-slip-and-shipping-label-for-woocommerce/?utm_source=Wordpress&utm_medium=cpc&utm_campaign=Free%20PDF%20Invoices%2CPacking%20slip&utm_term=Free%20PDF%20Invoices%2CPacking%20slip&utm_content=Free%20PDF%20Invoices%2CPacking%20slip">Go To Premium</a>', 'pdf-invoices-packing-slip-shipping-label-for-woocommerce' ),
						'class' => 'colorpick16',
						'id'   => 'woocommerce_invoice_footer_text_color_packing',
						'custom_attributes'=>array('disabled'=>'disabled')

					),
					'section_end_footer_packing' => array(
						'type' => 'sectionend',
						'id' => 'phoen_invoce_settings_section_end_footer_packing'
					),
				);
			}
			return apply_filters( 'wc_settings_tab_settings_tab_invoice_pdf', $settings );	
		}
	}
}

