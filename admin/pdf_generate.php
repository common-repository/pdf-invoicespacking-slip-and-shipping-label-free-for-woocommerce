<?php
	
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
	/* include autoloader */
	global $woocommerce;
	$currency = get_option('woocommerce_currency');

	$currency_symbol = get_woocommerce_currency_symbol($currency);	
	require_once(ABSPATH . 'wp-admin/includes/screen.php');
	
	$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : '';
	if($order_id){
		
		include_once(PHOENPDFINVOICEPLUGINDIRPATH.'admin/include/invoice_pdf.php');
		
	}	
	
	exit(0);
?>
