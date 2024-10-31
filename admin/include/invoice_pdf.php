<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
    $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : "";
    if($order_id){
   
        //get invoice general setting details
        $get_invoice_general = get_option('invoice_general_table');
        $image = wp_get_attachment_image_src( get_option('woocommerce_settings_invoice_pdf_invoice_logo'), 'full' );
        $terms_condition =  get_option('woocommerce_settings_invoice_pdf_invoice_terms_conditions');
        
        //get invoice header data 
       
        $date_formate = (get_option('woocommerce_invoice_date_format')) ? get_option('woocommerce_invoice_date_format') : 'd-m-Y';
        $invoice_date = date($date_formate);
        $is_downloaded = get_post_meta($order_id, '_is_downloaded', true );
        $file_name_format = get_option('woocommerce_invoice_name_format');

        $file_prefix = get_option('woocommerce_settings_invoice_pdf_invoice_prefix');
        $file_suffix = get_option('woocommerce_settings_invoice_pdf_invoice_suffix');
        
        if($is_downloaded){
            $invoice_number = $is_downloaded;
            //invoice file formate
            
            if($file_name_format!=''){
                
                $file_name_format_name= str_replace('[number]',$invoice_number,$file_name_format);
                $file_name_format_name=str_replace('[prefix]',$file_prefix,$file_name_format_name);
                $file_name_format_name=str_replace('[suffix]',$file_suffix,$file_name_format_name);
                $file_name_format_name=str_replace('[year]',date('Y'),$file_name_format_name);
                $file_name_format_name=str_replace('[month]',date('m'),$file_name_format_name);
                $file_name_format_name=str_replace('[day]',date('d'),$file_name_format_name);
            }else{
                $file_name_format_name=$invoice_number;
            }
        }else{
           
            $invoice_number = get_option('woocommerce_settings_invoice_pdf_invoice_number') + 1;
            
            update_post_meta( $order_id, '_is_downloaded', $invoice_number);
            update_option('woocommerce_settings_invoice_pdf_invoice_number',$invoice_number);
            //invoice file formate
            
            if($file_name_format!=''){
                
                $file_name_format_name= str_replace('[number]',$invoice_number,$file_name_format);
                $file_name_format_name=str_replace('[prefix]',$file_prefix,$file_name_format_name);
                $file_name_format_name=str_replace('[suffix]',$file_suffix,$file_name_format_name);
                $file_name_format_name=str_replace('[year]',date('Y'),$file_name_format_name);
                $file_name_format_name=str_replace('[month]',date('m'),$file_name_format_name);
                $file_name_format_name=str_replace('[day]',date('d'),$file_name_format_name);
            }else{
                $file_name_format_name=$invoice_number;


            }
        }

        //get woocormmerce order details
        $items = array();
        $order = new WC_Order( $order_id );
        $taxes = $order->get_taxes();
        $items = $order->get_items();
        $shipping_method = $order->get_shipping_method();
        $shiping_total = $order->get_shipping_total();
        $customer_note = $order->get_customer_note();
        
        // Get an instance of the WC_Order object
       
        $order_data = $order->get_data(); //get Order data using get_data method
        ## BILLING INFORMATION:
        $order_billing_first_name = $order_data['billing']['first_name'];
        $order_billing_last_name = $order_data['billing']['last_name'];
        $order_billing_company = $order_data['billing']['company'];
        $order_billing_address_1 = $order_data['billing']['address_1'];
        $order_billing_address_2 = $order_data['billing']['address_2'];
        $order_billing_city = $order_data['billing']['city'];
        $order_billing_state = $order_data['billing']['state'];
        $order_billing_postcode = $order_data['billing']['postcode'];
        $order_billing_country = $order_data['billing']['country'];
        $order_billing_email = $order_data['billing']['email'];
        $order_billing_phone = $order_data['billing']['phone'];
        
        ## SHIPPING  INFORMATION:
        $order_shiping_first_name = $order_data['shipping']['first_name'];
        $order_shipping_last_name = $order_data['shipping']['last_name'];
        $order_shipping_company = $order_data['shipping']['company'];
        $order_shipping_address_1 = $order_data['shipping']['address_1'];
        $order_shipping_address_2 = $order_data['shipping']['address_2'];
        $order_shipping_city = $order_data['shipping']['city'];
        $order_shipping_state = $order_data['shipping']['state'];
        $order_shipping_postcode = $order_data['shipping']['postcode'];
        $order_shipping_country = $order_data['shipping']['country'];
        
        ##get woocommerce store infomation
        // The main address pieces:
        $store_address = '';
        $store_address.= get_option( 'woocommerce_store_address').',';
        $store_address .= get_option( 'woocommerce_store_address_2' ).',';
        $store_address.= get_option( 'woocommerce_store_city').',';
        $store_address.= get_option( 'woocommerce_store_postcode' ).',';

        // The country/state
        $store_raw_country= get_option( 'woocommerce_default_country' ).',';

        // Split the country/state
        $split_country = explode( ":", $store_raw_country);

        // Country and state separated:
        $store_address.= $split_country[1];
        $store_address.= $split_country[0];
        $InvoiceData = array(
            'docType' => $_GET['doc'],
            'image_path' => isset($image[0]) ? $image[0] : PHOENPDFINVOICEPLUGINDIRPATH.'admin/icon/logo.jpeg',
            'invoice_number' => $invoice_number,
            'invoice_prefix'=>get_option('woocommerce_settings_invoice_pdf_invoice_prefix'),
            'invoice_suffix'=>get_option('woocommerce_settings_invoice_pdf_invoice_suffix'),
            'vendor_website'=> '',
            //store address
            'store_address' => $store_address,
            //order billing information
            'order_billing_first_name'=> $order_billing_first_name,
            'order_billing_last_name'=> $order_billing_last_name,
            'order_billing_company'=> $order_billing_company,
            'order_billing_email'=> $order_billing_email,
            'order_billing_phone'=> $order_billing_phone,
            'order_billing_address_1'=> $order_billing_address_1,
            'order_billing_address_2'=> $order_billing_address_2,
            'order_billing_city'=> $order_billing_city,
            'order_billing_state'=> $order_billing_state,
            'order_billing_country'=> $order_billing_country,
            'order_billing_postcode'=> $order_billing_postcode,
            //order shipping information
            'order_shiping_first_name'=> $order_shiping_first_name,
            'order_shipping_last_name'=> $order_shipping_last_name,
            'order_shipping_company'=> $order_shipping_company,
            'order_shipping_address_1'=> $order_shipping_address_1,
            'order_shipping_address_2'=> $order_shipping_address_2,
            'order_shipping_city'=> $order_shipping_city,
            'order_shipping_state'=> $order_shipping_state,
            'order_shipping_country'=> $order_shipping_country,
            'order_shipping_postcode'=> $order_shipping_postcode,
            'shipping_method' => $shipping_method,
            'shiping_total' => $shiping_total,
            //invoice general information
            'invoice_date'=> $invoice_date,
            'order_created_date' => $order_data['date_created']->date($date_formate),
            'order_numbner' => $order_id,
            'invoice_total_amount' => $order_data['total'],
            //'currency' => get_woocommerce_currency_symbol(),
            'items_list' => $items,
            'total_discount' => $order_data['discount_total'],
            'payment_method' => $order_data['payment_method_title'],
            'sub_total_amount' => ($order_data['total']+$order_data['discount_total']-$order_data['total_tax']-$shiping_total),
            'taxs' => $taxes,
            'terms_condition' => $terms_condition,
            );
        define ("INVOICEDATA", serialize ($InvoiceData));
        
        ob_start();
       
        wc_get_template( 'admin/include/invoice.php', null, PHOENPDFINVOICEPLUGINDIRPATH, PHOENPDFINVOICEPLUGINDIRPATH); 
         
                
        $html = ob_get_contents();
        
        ob_end_clean();
        // echo $html;
        // die();
        try {
            require PHOENPDFINVOICEPLUGINDIRPATH.'admin/libs/mpdf/vendor/autoload.php';
            $mpdf = new \Mpdf\Mpdf(['default_font' => 'dejavusans']);
            $ignore_table_percents = true;
            $keep_table_proportions = TRUE;
            $mpdf->shrink_tables_to_fit = 1;
            $mpdf->WriteHTML($html);
            
            //call watermark content aand image
            $mpdf->SetWatermarkText(get_option('woocommerce_settings_invoice_pdf_cname'));
            $mpdf->showWatermarkText = true;
            $mpdf->watermarkTextAlpha = 0.1;
            //save the file put which location you need folder/filname
            if(get_option('woocommerce_invoice_behavior') == 'browser'){
                $mpdf->Output($file_name_format_name.".pdf",'I');
            }else{
                $mpdf->Output($file_name_format_name.".pdf",'D');
            }
        } catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
        // Process the exception, log, print etc.
            echo $e->getMessage();
        }
    }
?>