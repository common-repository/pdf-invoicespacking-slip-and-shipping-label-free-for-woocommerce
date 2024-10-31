<?php 
if ( ! defined( 'WPINC' ) ) {
	die;
}
	
	$Invoice_data_unserialized = unserialize(INVOICEDATA);
	$get_invoice_header = get_option('invoice_header_data');
	$data_invoice_document = get_option('data_invoice_document');
    $data_invoice_template = get_option('data_invoice_template');
    $header_width = 72;
    $column = 1;
    if(get_option('woocommerce_invoice_enable_product_price') == 'yes') {
        $column = $column+1;
    }
    
    if(get_option('woocommerce_invoice_enable_product_quantity') == 'yes') {
        $column = $column+1;
    }
    
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php _e("Invoice PDF","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family: Arial; padding: 20px 0px;">
        <!--thead Start-->
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" 
                        style="padding: 0 30px; background-color: <?= get_option('woocommerce_invoice_header_background_color')?>; color: <?= get_option('woocommerce_invoice_header_text_color')?>;">
                        <tbody>
                            <tr>
                                <td style="padding: 0 18px;  vertical-align:top; padding-right:18px;">
                                    <?php if(get_option('woocommerce_invoice_enable_logo') == 'yes') { ?><img
                                        width="250" src="<?= $Invoice_data_unserialized['image_path']?>"
                                        alt="Phoeniixx Designs" /><?php } ?>
                                </td>
                                <td style="padding: 0 0px; vertical-align:top;">
                                    <?php if(get_option('woocommerce_invoice_enable_comapny_name') == 'yes' && get_option('woocommerce_settings_invoice_pdf_cname')) { ?>
										<table width="100%" style="margin-bottom: 10px;" cellspacing="0" cellpadding="0">
											<tr>
												<td>
													<h4 style="font-size: 21px; margin: 0 0 10px; ">
														<?php _e("Company Name","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?>
													</h4>
													<p style="font-size: 19px; margin: 0 0 10px;">
														<?= get_option('woocommerce_settings_invoice_pdf_cname'); ?></p>
													
												</td>
											</tr>
										</table>
								<?php } ?>
								<?php if(get_option('woocommerce_invoice_enable_phone_number') == 'yes') { ?>
										<table width="100%" style="margin-bottom: 10px;" cellspacing="0" cellpadding="0">
											<tr>
												<td>
													<h4 style="font-size: 21px; margin: 0 0 5px; ">
														<?php _e("Phone Number","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?>
													</h4>
													<p style="font-size: 19px; margin: 0 0 10px;">
														<?= get_option('woocommerce_invoice_company_phone') ?></p>
												</td>
											</tr>
										</table>

                                    <?php } ?>
                                    <?php if(get_option('woocommerce_invoice_enable_email_address') == 'yes') { ?>
                                    <h4 style="font-size: 21px; margin: 0 0 5px;">
                                        <?php _e("Email Address:","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?>
                                    </h4>
                                    <p style="font-size: 19px;  margin: 0;">
                                        <?= get_option('woocommerce_invoice_company_email') ?></p>
                                    <?php } ?>
                                </td>
                                <td style="padding: 0 15px;  vertical-align:top;">
                                    
                                </td>
                                <td style="padding: 0 15px; vertical-align: top;">
                                    <?php if(get_option('woocommerce_invoice_enable_store_address') == 'yes') { ?>
                                    <h4 style="font-size: 21px; margin: 0 0 5px;">
                                        <?php _e("Address:","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?>
                                    </h4>
                                    <p style="font-size: 19px; margin: 0;">
                                        <?= $Invoice_data_unserialized['store_address'] ?></p>
                                    <?php } ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        <!--thead End-->
        <!--tbody Start-->
			<!-- start  billing section -->
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding: 0 30px; ">
                        <tbody>
                            <tr>
                                <?php if(get_option('woocommerce_invoice_enable_billing_address') == 'yes') { ?>
                                <td style="padding: 80px 15px 0 0; width: 33.33%; vertical-align:top; ">
                                    <table width="100%" style="margin-bottom: 15px;" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td>
                                                <p style="margin: 0; font-size: 22px;">
                                                    <?php _e("Billed To:","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?>
                                                </p>
                                                <h4
                                                    style="font-size: 22px; margin: 0 0 10px; text-transform: uppercase;">
                                                    <?= $Invoice_data_unserialized['order_billing_first_name'].' '.$Invoice_data_unserialized['order_billing_last_name']?>
                                                </h4>
                                            </td>
                                        </tr>
                                    </table>
									<table width="100%" cellspacing="0" cellpadding="0">
										<tr>
											<td>
												<p style="margin: 0; font-size: 19px;">
													<?php if($get_invoice_header['show_company_name'] == 'true') { 
														echo  $Invoice_data_unserialized['order_billing_company'].'</br>';
													}?>
															<?= $Invoice_data_unserialized['order_billing_address_1'].'<br>'.$Invoice_data_unserialized['order_billing_address_2'].'</br>'.
														$Invoice_data_unserialized['order_billing_city']. ','. $Invoice_data_unserialized['order_billing_state']. ','. $Invoice_data_unserialized['order_billing_country'].'</br>'.
														$Invoice_data_unserialized['order_billing_postcode']
													?>
												</p>
											</td>
										</tr>
									</table>
                                </td>
                            <?php } ?>	
							<?php if(get_option('woocommerce_invoice_enable_shipping_address') == 'yes' && $Invoice_data_unserialized['order_shipping_address_1']) { ?>	
                                <td style="width: 33.33%; padding: 80px 15px 0; vertical-align:top;" >

                                    <table width="100%" style="margin-bottom: 15px;" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td>
                                                <p style="margin: 0; font-size: 22px;">
                                                    <?php _e("Shipping To:","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?>
                                                </p>
                                                <h4
                                                    style="font-size: 22px; margin: 0 0 10px; text-transform: uppercase;">
                                                    <?= $Invoice_data_unserialized['order_shiping_first_name'].' '.$Invoice_data_unserialized['order_shipping_last_name']?>
                                                </h4>
                                            </td>
                                        </tr>
                                    </table>

									<table width="100%" cellspacing="0" cellpadding="0">
											<tr>
													<td>
														<p style="margin: 0; font-size: 19px;">
															<?= $Invoice_data_unserialized['order_shipping_address_1'].'<br>'.$Invoice_data_unserialized['order_shipping_address_2'].'</br>'.
																$Invoice_data_unserialized['order_shipping_city']. ','. $Invoice_data_unserialized['order_shipping_state']. ','. $Invoice_data_unserialized['order_shipping_country'].'</br>'.
																$Invoice_data_unserialized['order_shipping_postcode']
															?>
														</p>
													</td>
											</tr>
									</table>
                                </td>
                            <?php } ?>
                                <td style="width: 33.33%; padding: 80px 0 0 15px; vertical-align:top; ">
                                    <?php if($Invoice_data_unserialized['order_billing_phone']) { ?>

                                    <table width="100%" style="margin-bottom: 15px;" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td>
                                                <h4 style="margin: 0; font-size: 22px;">
                                                    <?php _e("Phone Number:","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?>
                                                </h4>
                                                <p style="margin: 0 0 10px; font-size: 19px;">
                                                    <?= $Invoice_data_unserialized['order_billing_phone'] ?>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>

                                    <?php } ?>
									
                                    <?php if($Invoice_data_unserialized['order_billing_email']) { ?>
										
										<table width="100%" style="margin-bottom: 15px;" cellspacing="0" cellpadding="0">
												<tr>
													  <td>
														   <h4 style="margin: 0; font-size: 22px;">
															<?php _e("Email Address:","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?>
														</h4>
														<p style="margin: 0; font-size: 19px;">
															<?= $Invoice_data_unserialized['order_billing_email'] ?></p>
													  </td>
												 </tr>
										 </table>
										
										<?php } ?>
										
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
			</tr>
			<!-- end   billing section -->	
			
			
			
			<!-- start   invoice number section-->
            <tr>
                <td style="padding-bottom: 40px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding: 0 30px;  margin-top:20px;">
                        <tbody>
                            <tr>
                                <?php if(get_option('woocommerce_invoice_enable_order_number') == 'yes') { ?>
                                <td style=" width: 30.55%; padding: 30px 15px 0 0; ">
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td>
													<h4 style="margin: 0; font-size: 22px;">
														<?php _e("Order Number:","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?>
													</h4>
													<p style="margin: 0; font-size: 19px;">
														<?= $Invoice_data_unserialized['order_numbner']?>
													</p>
												</td>
											</tr>
										</table>
                                    
                                </td>
                                <?php } ?>
                                <?php if(get_option('woocommerce_invoice_enable_order_date') == 'yes') { ?>
                                <td style="padding: 30px 15px 0; width: 33.33%; ">
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td>
												<h4 style="margin: 0; font-size: 22px;">
													<?php _e("Order Date:","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?>
												</h4>
												<p style="margin: 0; font-size: 19px;">
													<?= $Invoice_data_unserialized['order_created_date']?>
												</p>
											</td>
										</tr>
									</table>
                                </td>
                                <?php } ?>
                                <?php if(get_option('woocommerce_invoice_enable_invoice_date') == 'yes') { ?>
                                <td style="padding: 30px 0 0 15px; width: 33.33%; ">
                                   	<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td>
													<h4 style="margin: 0; font-size: 22px;">
														<?php _e("Date of Issue","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?>
													</h4>
													<p style="margin: 0; font-size: 19px;">
														<?= $Invoice_data_unserialized['invoice_date']?>
													</p>
											</td>
										</tr>
									</table>
                                </td>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
			
			<!-- end   invoice number section -->	

            <tr align="center">
                <td style="padding: 20px 30px; border-top: 1px solid #eee;"></td>
            </tr>
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding: 0 30px;">
                        <tbody>
                            <tr>
                                <td style="width:69%;">
                                    <?php if(get_option('woocommerce_invoice_enable_invoice_num') == 'yes') { 
                                    $invoice_number_fomat = get_option('woocommerce_invoice_number_format');
                                    $invoice_number_fomat = str_replace('[number]',$Invoice_data_unserialized['invoice_number'],$invoice_number_fomat);
                                    $invoice_number_fomat = str_replace('[prefix]',$Invoice_data_unserialized['invoice_prefix'],$invoice_number_fomat);
                                    $invoice_number_fomat = str_replace('[suffix]',$Invoice_data_unserialized['invoice_suffix'],$invoice_number_fomat);
                                    ?>
                                    <p style="margin: 0; font-size: 22px; font-weight: 600;">
                                        <?php _e("Invoice No.","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?>
                                    </p>
                                    <h4 style="font-size: 30px; margin: 5px 0 0;">
                                        <?= ($invoice_number_fomat) ? $invoice_number_fomat : $Invoice_data_unserialized['invoice_number'] ?>
                                    </h4>
                                    <?php } ?>
                                </td>
                                <td style="width:45%;">&nbsp;</td>
                                <td style="width:30%;">
                                    <p style="margin: 0; font-size: 22px; font-weight: 600;">
                                        <?php _e("Total Amount:","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?>
                                    </p>
                                    <h4 style="font-size: 35px; margin: 5px 0 0; color: #fd4e23">
                                        <?php echo get_woocommerce_currency_symbol(get_option('woocommerce_currency'))."".$Invoice_data_unserialized['invoice_total_amount'] ?>
                                    </h4>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 60px; background-color: <?= get_option('woocommerce_invoice_product_list_header_background_color')?>; padding: 20px 30px;">
                        <tbody>
                            <tr>
                                <td style="width: 28%;">
                                    <p style="margin: 0; font-size: 18px; color:<?= get_option('woocommerce_invoice_product_list_header_text_color')?>;">
                                        <?php _e("Product","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?>
                                    </p>
                                </td>
                                <?php if(get_option('woocommerce_invoice_enable_product_price') == 'yes') { ?>
                                    <td style="width:<?= $header_width/$column?>%; text-align:center;">
                                        <p style="margin: 0; font-size: 18px; color:<?= get_option('woocommerce_invoice_product_list_header_text_color')?>;">
                                            <?php _e("Unit Cost","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?>
                                        </p>
                                    </td>
                                <?php } ?>
                                
                                <?php if(get_option('woocommerce_invoice_enable_product_quantity') == 'yes') { ?>
                                    <td style="width:<?= $header_width/$column?>%;  text-align: center;">
                                        <p style="margin: 0; padding-left:0px; font-size: 18px; color:<?= get_option('woocommerce_invoice_product_list_header_text_color')?>;">
                                            <?php _e("Qty","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?>
                                        </p>
                                    </td>
                                <?php } ?>
                                
                                <?php if(get_option('woocommerce_invoice_enable_product_line_total') == 'yes') { ?>
                                <td style="width:<?= $header_width/$column?>%;  text-align:right;">
                                    <p style="margin: 0; font-size: 18px; color:<?= get_option('woocommerce_invoice_product_list_header_text_color')?>;">
                                        <?php _e("Amount","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?>
                                    </p>
                                </td>
                                <?php } ?>
                            </tr>
							
							<!--section-formate change-->
																	
							<!--section-formate change end-->
						
							
                        </tbody>
                    </table>
                </td>
            </tr>
            <?php
                $i=0;
                foreach($Invoice_data_unserialized['items_list'] as $item){
                    $product_id = $item->get_product_id();
                    $item_data  = $item->get_data();
                    $response_attributes = array();
                    
                    foreach ($item->get_meta_data() as $metaData) {
                        $attribute = $metaData->get_data();
                        $pos = strpos($attribute['key'], 'pa_');
                        if ($pos !== false) {
                            $response_attributes[$attribute['key']] = $attribute['value'];
                        }
                    }
                    $product = wc_get_product( $product_id );
                    if( $product->is_type( 'variable' ) ){
                        // a variable product
                    
                        $product_variation = new WC_Product_Variation( $item_data['variation_id'] );
                        $_product_sku = $product_variation->get_sku();
                        $_product_description = $product_variation->get_description();
                    
                    } else if( $product->is_type( 'simple' ) ){
                        // a simple product
                        $_product_sku = $product->get_sku();
                        $_product_description = $product->get_short_description();
                        
                    }
            ?>
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0"  style="padding: 15px 30px; <?php if($i%2 != 0){?> background-color: #e8e8ea;<?php } ?>">
                        <tbody>
                            <tr>
                                <td style="width: 28%" >
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td style="width: 30%;">
                                                    <?php if(get_option('woocommerce_invoice_enable_product_image') == 'yes') { ?>
                                                    <?= ( $product->is_type( 'variable' ) ) ? $product_variation->get_image( array( 100,100 ) ) : $product->get_image( array( 100,100 ) )?>
                                                    <?php }else{?>
															<img src="<?= PHOENPDFINVOICEPLUGINDIRPATH.'admin/icon/Blank.jpg' ?>" style="height:100px; width:100px;"/>
													<?php } ?>
                                                </td>
												
                                                <td style="vertical-align: top;<?php if(get_option('woocommerce_invoice_enable_product_image') == 'yes') { ?> width: 70%; <?php } ?> padding-left: 15px;">
                                                    <p style="font-size: 18px; font-weight: 600; "><?= ( $product->is_type( 'variable' ) ) ? ' '.$product_variation->get_title() : ' '.$product->get_title()?>
                                                       
                                                    </p>
                                                    <?php if(get_option('woocommerce_invoice_enable_product_variation') == 'yes' && $response_attributes) { 
                                                        foreach($response_attributes as $key_attr => $attribute){?>
                                                            <p><span
                                                                style="font-weight: 600;"><?= _e( str_replace('pa_','',$key_attr).":","pdf-invoices-packing-slip-shipping-label-for-woocommerce")?></span>
                                                            <?= $attribute ?></p>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="padding-left: 0; ">
                                                    <?php if(get_option('woocommerce_invoice_enable_product_description') == 'yes' && $_product_description) { ?>
                                                    <p><span
                                                            style="font-weight: 600;"><?php _e("Description:","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?></span>
                                                    </p>
                                                    <p><?= $_product_description ?></p>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <?php if(get_option('woocommerce_invoice_enable_product_price') == 'yes') { ?>
                                    <td style="width:<?= $header_width/$column?>%;  vertical-align: top; text-align:center; padding-right:0;">
                                        <p style="font-size: 18px; font-weight: 600;">
                                            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo wc_price($item_data['subtotal']/$item_data['quantity'])?>
                                        </p>
                                    </td>
                                <?php } ?>
                                
                                <?php if(get_option('woocommerce_invoice_enable_product_quantity') == 'yes') { ?>
                                    <td  style="width:<?= $header_width/$column?>%; vertical-align: top; text-align:center;">
                                        <p style="font-size: 18px; font-weight: 600;">&nbsp; &nbsp; &nbsp; &nbsp;<?= $item_data['quantity'] ?></p>
                                    </td>
                                <?php } ?>
                                
                                <?php if(get_option('woocommerce_invoice_enable_product_line_total') == 'yes') { ?>
                                    <td style="width:<?= $header_width/$column?>%;  vertical-align: top; text-align:right;">
                                        <p style="font-size: 18px; font-weight: 600;">
                                            <?= wc_price($item_data['subtotal']) ?>
                                        </p>
                                    </td>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
			
            <?php $i++; } ?>
            <tr align="center">
                <td style="padding: 20px 30px; border-top: 1px solid #eee;"></td>
            </tr>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" style="padding: 0 0px 0 30px;">
                        <tbody>
                            <tr>
                                <td style="width: 60%;"></td>
                                <td style="width: 40%;">
                                    <table width="100%" cellspacing="0" cellpadding="0" style="text-align: right;">
                                        <tbody>
                                            <?php if(get_option('woocommerce_invoice_enable_invoice_total') == 'yes') { ?>
                                            <tr>
												<td colspan="2" >
                                                    <table style="margin-top:20px;">	
                                                        <tr>
                                                            <td colspan="2" style="width: 100%; margin-top:70px; background-color: <?= get_option('woocommerce_invoice_price_total_background_color')?>;  color: <?= get_option('woocommerce_invoice_price_total_text_color')?>;  padding: 15px 20px; font-size: 25px; text-align: center; color:#fff;">
                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                        <td>
                                                                            <span style=" font-size: 25px;"><?php _e("Total Amount : ","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?></span>
                                                                            <span style=" font-size: 25px; color:#fff; text-align: left; padding-left:5px; font-weight: bold;"><?= wc_price($Invoice_data_unserialized['invoice_total_amount'])?></span>
                                                                        </td>
                                                                        
                                                                    </tr>
                                                                </table> 
                                                            </td>
                                                        </tr>
                                                    </table>
												</td>
												
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
							
						
							
							
                        </tbody>
                    </table>
					
					
					
                </td>
            </tr>
            <tr align="center">
                <td style="padding: 20px 30px; border-top: 1px solid #eee;"></td>
            </tr>
            <tr align="center">
                <td>
                    <table cellspacing="0" cellpadding="0" class="bottom-matter">
                        <tr>
                            <?php if(get_option('woocommerce_invoice_enable_invoice_notes') == 'yes' && get_option('woocommerce_invoice_notes')) { ?>
                            <td>
                                <table style="margin-bottom:18px;" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <strong style="font-size:21px;  display:inline-block;"><?php _e("Invoice Note:","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?></strong>
                                            <span style="font-size:18px; display:inline-block;"><?php echo get_option('woocommerce_invoice_notes');?></span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <?php }?>
                        </tr>
						<tr>
                            <td>
                                <table style="margin-bottom:18px;" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <p style="display:inline-block;"><?php if(get_option('woocommerce_invoice_enable_company_details') == 'yes' && get_option('woocommerce_invoice_cdetails')) { ?>
                                            <td><strong style="font-size:21px; display:inline-block;"><?php _e("Company Details:","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?></strong>
                                            <span style="font-size:18px; display:inline-block;"><?php echo get_option('woocommerce_invoice_cdetails');?></span>
                                            </td>
                                            <?php }?></p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
						</tr>
						
						<tr>
							<p style="font-size:21px;"><?php if(get_option('woocommerce_settings_invoice_pdf_invoice_terms_conditions')) { ?></p>
						</tr>
                        
                        <tr>
						
                            <td><strong  style="font-size:21px; display:inline-block;"><?php _e("Terms & Conditions:","pdf-invoices-packing-slip-shipping-label-for-woocommerce");?></strong>
							<span style="font-size:18px; display:inline-block;"><?php echo get_option('woocommerce_settings_invoice_pdf_invoice_terms_conditions') ;?></span>
                            </td>
                        </tr>
						
                        <?php } ?>
                      

                    </table>
                </td>
            </tr>
        <!--tbody End-->
    </table>
    
	<div style="position:absolute; bottom:0px; text-align:center; left:0px;width:100%; background-color: <?= get_option('woocommerce_invoice_footer_background_color')?>; color: <?= get_option('woocommerce_invoice_footer_text_color')?>;">
        <div style="padding:10px 20px;">
            <p style="font-size:10px;"><?php echo get_option('woocommerce_invoice_footer');?></p>
        </div>
	</div>
</body>


</html>