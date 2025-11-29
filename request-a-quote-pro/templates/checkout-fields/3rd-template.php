<h4> <?php echo esc_html__('Billing Details' , 'cloud_tech_rfq'); ?> </h4>
<style>
	.third-template .af-rfq-profile-checkout img{
		     width: 31px!important;
    height: 23px!important;
    margin: 0px auto!important;
    object-fit: cover;
	}
	.af-heading-pos i{
		    background: #ffdd44;
    padding: 15px 10px;
    border-radius: 50%;
    position: relative;
	}
	.af-rfq-checkout-form.third-template.template-border{
		position:relative;
	}
	.af-rfq-checkout-form.third-template:before{
		content:"";
		top:0;
		left:22px;
		position: absolute;
		background:#ffdd44;
		width:2px;
		height:100%;
	}
	.af-heading-pos i{
		    margin-right: 16px;
		margin-bottom:0;
	}
	.af-rfq-profile-mode label:first-child{
		border-top-left-radius: 10px;
		border-bottom-left-radius: 10px;
	}
	.af-rfq-profile-mode label:last-child{
		border-top-right-radius: 10px;
		border-bottom-right-radius: 10px;
	}
	.af-heading-pos{
		    display: flex;
    align-items: center;
	}
	.third-template table tr{
    display: flex;
    flex-wrap: wrap;
		justify-content: space-between;
	}
	.third-template table tr td{
		width: 48%;
    padding: 0 0px 5px!important;
	}
	.third-template table tr td:nth-child(5), .third-template table tr td:nth-child(6),
	.third-template table tr td:nth-child(7){
		width: 100%;
	}
	.third-template table tr td label{
		display:block;
		font-size: 13px;
		line-height: 22px;
		font-weight: 600;
		color: #000;
		margin-bottom: 8px;
	}
	.third-template table tr td input,
	.third-template table tr td select,
	.third-template table tr td .selection .select2-selection.select2-selection--single{
		width: 100%;
		height: 46px!important;
		box-shadow: none;
		background: #fff!important;
		border-radius: 10px!important;
		padding: 7px 10px!important;
		font-size: 14px!important;
		border: 1px solid #d3d3d345!important;
		margin-bottom: 20px!important;
		line-height: 24px!important;
	}
	.third-template .select2-container--default .select2-selection--single .select2-selection__arrow {
		top: 9px;
		right: 6px;
	}
	.third-template table tr td textarea{
		    background: #fff;
    border: 1px solid #d3d3d345;
    border-radius: 10px!important;
    box-shadow: none;
    height: 190px;
    font-size: 14px;
    line-height: 24px;
    margin-bottom: 25px;
	}
	
	.third-template table tr td label.radio{
		    border: 1px solid #d3d3d345!important;
		padding: 10px;
		position: relative;
		text-align: center;
		    width: 46%;
	}
	.third-template table tr td label input[type=radio]{
		position: absolute;
		width: 100%;
		height: 100%!important;
		top: 0;
		opacity: 0;
		left: 0;
	}
	.rsq-radio-btn-wrap{
		 display: flex;
		justify-content: space-between;
	}
	.third-template .af-rfq-profile-mode{
		justify-content:flex-start!important;
		margin: 30px 0 30px 55px!important;
		
	}
	.af-rfq-checkout-form.third-template table{
		    margin-left: 55px;
	}
	.third-template .af-rfq-rent-period h3, .third-template .af-rfq-profile-checkout h3{
		font-weight: 700;
		color: #000;
		    font-size: 20px!important;
    line-height: 30px!important;
		margin: 0;
	}
	.third-template .af-rfq-profile-mode label{
		border: 1px solid #d3d3d345;
		padding: 9px;
		display: block;
		width: 120px;
        font-size: 14px;
       line-height: 21px;
		font-weight: 600;
		background: transparent;
		color: #000;
		position: relative;
	}
	.third-template{
		padding: 20px 0;
		border-bottom: 1px solid #d3d3d34d;
	}
	.rqs-shiping{
		border-bottom: none;
		padding-bottom: 0!important;
	}
	.third-template-btn{
		background :#ffdd44!important;
		font-size: 16px;
		line-height: 24px;
		padding: 9px 24px!important;
		border-radius: 10px!important;
		color:#000;
	}
	.third-template{
		padding:0!important;
		border:none!important;
	}
	.template-heading h2{
		    font-size: 30px;
    font-weight: 700;
    line-height: 40px;
    text-align: center;
    margin-bottom: 30px;
	}
</style>
	<?php
	
	$billing_fields 	= get_posts([
						'post_type' 		=> 'ct-rfq-quote-fields',
						'post_status' 		=> 'publish',				
						'posts_per_page' 	=> -1,
						'fields' 			=> 'ids',
						'orderby' 			=> 'menu_order',
						'order' 			=> 'ASC',
						'meta_query' 		=> [
							[
								'key' 	=> 'ct_rfq_quote_fields_show_field_with',
								'value' => 'in_billing_fields',
							]
						]
					]);
	
	$shipping_fields 	= get_posts([
						'post_type' 		=> 'ct-rfq-quote-fields',
						'post_status' 		=> 'publish',				
						'posts_per_page' 	=> -1,
						'fields' 			=> 'ids',
						'orderby' 			=> 'menu_order',
						'order' 			=> 'ASC',
						'meta_query' 		=> [
							[
								'key' 	=> 'ct_rfq_quote_fields_show_field_with',
								'value' => 'in_shipping_fields',
							]
						]
					]);
	?>
	<form method="post" enctype="multipart/form-data" >
			
		<?php if( is_user_logged_in() ){

			$ct_rfq_current_user_email          = wp_get_current_user();
			
			?><input type="email"  style="display:none;" name="current_user_email" value="<?php echo esc_attr( $ct_rfq_current_user_email->user_email ); ?>"><?php 

		} else { ?>
			<table>
				<tr>
					<th><?php echo esc_html__('Email For Conatct','cloud_tech_rfq'); ?></th>
					<td><input type="email" name="current_user_email" value="" required ></td>
				</tr>
			</table>

		<?php }?>
						
		<section class="af-rfq-custom-form">
			<div class="container">
				<div class="template-heading">
					<h2>Checkout</h2>
				</div>
				<div class="rqs-third-template">
					
				<div class="af-rfq-checkout-wrap">
					
					<?php if( count( $billing_fields ) >= 1 ) {?>
					<div class="af-rfq-checkout-form third-template template-border">
						<div class="af-rfq-profile-checkout">
							<div class="af-heading-pos">
								<i class="af-rfq-billing-icon"><?php
									$icon_or_image = !empty( get_option('ct_rfq_billing_logos')) ? '<img style="width:40px;height:40px;" src="'. esc_url(get_option('ct_rfq_billing_logos')) .'" srcset="'.esc_url(get_option('ct_rfq_billing_logos')).'" />' : '<i class="fa fa-solid fa fa-user"></i>';	
								 echo wp_kses_post( $icon_or_image ); ?></i>
								<h3><?php echo esc_html__('Billing Detail','cloud_tech_rfq'); ?></h3>
							</div>
							<div class="af-rfq-profile-mode">
								<label><input type="radio" name="billing-profile" class="billing-profile billing-profile-private" value="private"><?php echo esc_html__('Private','cloud_tech_rfq'); ?></label>
								<label><input type="radio" name="billing-profile" class="billing-profile billing-profile-company" value="company"><?php echo esc_html__('Company','cloud_tech_rfq'); ?></label>
							</div>
						</div>
						<table>
							<tr>
							<?php foreach ($billing_fields as $current_billing_post_id) { 
								$show_with 	=  '';
								if( 'company' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_show_field_in_private_company',true) ){
									$show_with 	= 'ct-rfq-billing-fields-with-company';
								}
								if( 'private' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_show_field_in_private_company',true) ){
									$show_with 	= 'ct-rfq-billing-fields-with-private';
								}
								$default_value 				= get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_default_value',true);
								$placeholder_holder_value 	= get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_placeholder',true); 
								$additonal_classes 			= get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_additonal_class',true); 
								?>
									
								<td class="<?php echo esc_attr( $show_with ); ?>">
									<label for="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field"><?php echo esc_attr( get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_label',true) ); ?></label>
										<?php
										if('date' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?>
											<input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="date" name="<?php echo esc_attr( $current_billing_post_id ); ?>">
										<?php
										}
										if('time' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?>
											<input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="time" name="<?php echo esc_attr( $current_billing_post_id ); ?>">
											<?php
										}
										if('file_upload' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?>
											<input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="file" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field">
											<?php
										}
										if('select' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											
											?><select  class="ct-rfq-live-search <?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field"><?php 

											foreach ( (array) get_post_meta( $current_billing_post_id , 'ct_rfq_request_a_quote_options_value_and_label' , true ) as $key => $value) {
												if( is_array( $value ) ){
													?><option <?php selected( isset($value['option_value']) ? $value['option_value'] : '' , $default_value) ?> value="<?php echo esc_attr( isset($value['option_value']) ? $value['option_value'] : ''); ?>"><?php echo esc_attr( isset($value['option_label']) ? $value['option_label'] : ''); ?></option><?php
												}
											}                
											?></select><?php

											
										}
										if('multi_select' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?><select multiple class="ct-rfq-live-search <?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" name="<?php echo esc_attr( $current_billing_post_id ); ?>[]" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field"><?php 

											foreach ( (array) get_post_meta( $current_billing_post_id , 'ct_rfq_request_a_quote_options_value_and_label' , true ) as $key => $value) {
												if( is_array( $value ) ){
													?><option <?php selected( isset($value['option_value']) ? $value['option_value'] : '' , $default_value) ?> value="<?php echo esc_attr( isset($value['option_value']) ? $value['option_value'] : ''); ?>"><?php echo esc_attr( isset($value['option_label']) ? $value['option_label'] : ''); ?></option><?php
												}
											}                
											?></select><?php
										}
										if('radio' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){ ?>
									<div class="rsq-radio-btn-wrap">
										<?php
											foreach ( (array) get_post_meta( $current_billing_post_id , 'ct_rfq_request_a_quote_options_value_and_label' , true ) as $key => $value) {
												if( is_array( $value ) ){
													?>
									<label class="radio">
													<input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" <?php checked( $default_value , isset($value['option_value']) ? $value['option_value'] : '' ); ?> type="radio" name="<?php echo esc_attr( $current_billing_post_id ); ?>" value="<?php echo esc_attr( isset($value['option_value']) ? $value['option_value'] : ''); ?>">
													<?php echo esc_attr( isset($value['option_label']) ? $value['option_label'] : ''); ?>
										</label>
													<?php
												}
											} ?>
									</div>
									<?php
										}
										if('checkbox' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?>
											<input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" type="checkbox" value="<?php echo esc_attr( $current_billing_post_id ); ?>" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field">
											<?php
										}
										if('multi_checkbox' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											
											foreach ( (array) get_post_meta( $current_billing_post_id , 'ct_rfq_request_a_quote_options_value_and_label' , true ) as $key => $value) {
												if( is_array( $value ) ){
													?>
													<input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" <?php checked( $default_value , isset($value['option_value']) ? $value['option_value'] : '' ); ?> type="checkbox" value="<?php echo esc_attr( isset( $value['option_value'] ) ? $value['option_value'] : ''); ?>" name="<?php echo esc_attr( $current_billing_post_id ); ?>[]" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field" >
													<?php echo esc_attr( isset($value['option_label']) ? $value['option_label'] : ''); ?>
													<br>
													<?php
												}
											}
										}
										if('countries' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											$countries 	= new WC_Countries(); 
											?>
											<select class="ct-rfq-live-search <?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>"  class="ct-rfq-live-search" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field">
												<?php foreach ( $countries->get_countries() as $key => $value) { ?>

													<option <?php selected( $default_value , isset($value['option_value']) ? $value['option_value'] : '' ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value ); ?></option>
												
												<?php } ?>
											</select>
											<?php
										}
										if('input' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?><input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="text" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field"><?php
										}
										if('textarea' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?><textarea class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field" cols="30" rows="10"><?php echo esc_attr( $default_value ); ?></textarea><?php
										}
										if('number' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?><input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="number" min="1" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field">
											<?php
										}
										if('color' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?><input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="color" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field">
											<?php
										}
										if('email' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?><input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="email" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field">
											<?php
										}
										if('password' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?><input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="password" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field">
											<?php
										}
										if('telephone' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?><input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="tel" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field">
											<?php
										}
									?>
								</td>
								

							<?php  } ?>
								</tr>
						</table>

					</div>
					<?php } ?>
				</div>
				<div class="af-rfq-checkout-wrap">
				<?php if( count( $shipping_fields ) >= 1 ) { ?>

					<div class="af-rfq-checkout-form third-template rqs-shiping">
						<div class="af-rfq-profile-checkout">
							<div class="af-heading-pos">
								<i class="af-rfq-shipping-icon"><?php
										$icon_or_image = !empty( get_option('ct_rfq_shipping_logos')) ? '<img style="width:40px;height:40px;" src="'. esc_url(get_option('ct_rfq_shipping_logos')) .'" srcset="'.esc_url(get_option('ct_rfq_billing_logos')).'" />' : '<i class="fa fa-solid fa fa-user"></i>';	
									 echo wp_kses_post( $icon_or_image ); ?></i>
									<h3><?php echo esc_html__('Shipping Detail','cloud_tech_rfq'); ?></h3>
							</div>
							<div class="af-rfq-profile-mode">
								<label><input type="radio" name="shipping-profile" class="shipping-profile shipping-profile-private" value="private"><?php echo esc_html__('Private','cloud_tech_rfq'); ?></label>
								<label><input type="radio" name="shipping-profile" class="shipping-profile shipping-profile-company" value="company"><?php echo esc_html__('Delivery','cloud_tech_rfq'); ?></label>
							</div>
						</div>
						<table>
							<tr class="<?php echo esc_attr( $show_with ); ?>">
							<?php foreach ($shipping_fields as $current_billing_post_id) { 
								$show_with 			=  '';
								if( 'company' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_show_field_in_private_company',true) ){
									$show_with 		= 'ct-rfq-shipping-fields-with-company';
								}
								if( 'private' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_show_field_in_private_company',true) ){
									$show_with 		= 'ct-rfq-shipping-fields-with-private';
								}
								$default_value 				= get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_default_value',true);
								$placeholder_holder_value 	= get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_placeholder',true); 
								$additonal_classes 			= get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_additonal_class',true); 

								?>
								
								<td class="<?php echo esc_attr( $show_with ); ?>">

										<label for="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field"><?php echo esc_attr( get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_label',true) ); ?></label>
										<?php
										if('date' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?>
											<input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="date" name="<?php echo esc_attr( $current_billing_post_id ); ?>">
											<?php
										}
										if('time' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?>
											<input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="time" name="<?php echo esc_attr( $current_billing_post_id ); ?>">
											<?php
										}
										if('file_upload' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?>
											<input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="file" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field">
											<?php
										}
										if('select' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											
											?><select  class="ct-rfq-live-search <?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field"><?php 

											foreach ( (array) get_post_meta( $current_billing_post_id , 'ct_rfq_request_a_quote_options_value_and_label' , true ) as $key => $value) {
												if( is_array( $value ) ){
													?><option <?php selected( isset($value['option_value']) ? $value['option_value'] : '' , $default_value) ?> value="<?php echo esc_attr( isset($value['option_value']) ? $value['option_value'] : ''); ?>"><?php echo esc_attr( isset($value['option_label']) ? $value['option_label'] : ''); ?></option><?php
												}
											}                
											?></select><?php

											
										}
										if('multi_select' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?><select multiple class="ct-rfq-live-search <?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" name="<?php echo esc_attr( $current_billing_post_id ); ?>[]" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field"><?php 

											foreach ( (array) get_post_meta( $current_billing_post_id , 'ct_rfq_request_a_quote_options_value_and_label' , true ) as $key => $value) {
												if( is_array( $value ) ){
													?><option <?php selected( isset($value['option_value']) ? $value['option_value'] : '' , $default_value) ?> value="<?php echo esc_attr( isset($value['option_value']) ? $value['option_value'] : ''); ?>"><?php echo esc_attr( isset($value['option_label']) ? $value['option_label'] : ''); ?></option><?php
												}
											}                
											?></select><?php
										}
										if('radio' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											
											foreach ( (array) get_post_meta( $current_billing_post_id , 'ct_rfq_request_a_quote_options_value_and_label' , true ) as $key => $value) {
												if( is_array( $value ) ){
													?>
													<input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" <?php checked( $default_value , isset($value['option_value']) ? $value['option_value'] : '' ); ?> type="radio" name="<?php echo esc_attr( $current_billing_post_id ); ?>" value="<?php echo esc_attr( isset($value['option_value']) ? $value['option_value'] : ''); ?>">
													<?php echo esc_attr( isset($value['option_label']) ? $value['option_label'] : ''); ?>
													<?php
												}
											}                

										}
										if('checkbox' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?>
											<input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" type="checkbox" value="<?php echo esc_attr( $current_billing_post_id ); ?>" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field">
											<?php
										}
										if('multi_checkbox' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											
											foreach ( (array) get_post_meta( $current_billing_post_id , 'ct_rfq_request_a_quote_options_value_and_label' , true ) as $key => $value) {
												if( is_array( $value ) ){
													?>
													<input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" <?php checked( $default_value , isset($value['option_value']) ? $value['option_value'] : '' ); ?> type="checkbox" value="<?php echo esc_attr( isset( $value['option_value'] ) ? $value['option_value'] : ''); ?>" name="<?php echo esc_attr( $current_billing_post_id ); ?>[]" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field" >
													<?php echo esc_attr( isset($value['option_label']) ? $value['option_label'] : ''); ?>
													<br>
													<?php
												}
											}
										}
										if('countries' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											$countries 	= new WC_Countries(); 
											?>
											<select class=" ct-rfq-live-search <?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>"  class="ct-rfq-live-search" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field">
												<?php foreach ( $countries->get_countries() as $key => $value) { ?>

													<option <?php selected( $default_value , isset($value['option_value']) ? $value['option_value'] : '' ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value ); ?></option>
												
												<?php } ?>
											</select>
											<?php
										}
										if('input' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?><input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="text" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field"><?php
										}
										if('textarea' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?><textarea class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field" cols="30" rows="10"><?php echo esc_attr( $default_value ); ?></textarea><?php
										}
										if('number' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?><input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="number" min="1" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field">
											<?php
										}
										if('color' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?><input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="color" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field">
											<?php
										}
										if('email' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?><input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="email" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field">
											<?php
										}
										if('password' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?><input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="password" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field">
											<?php
										}
										if('telephone' == get_post_meta($current_billing_post_id,'ct_rfq_quote_fields_field_type',true) ){
											?><input class="<?php echo esc_attr( $additonal_classes ); ?>" placeholder="<?php echo esc_attr( $placeholder_holder_value ); ?>" value="<?php echo esc_attr( $default_value ); ?>" type="tel" name="<?php echo esc_attr( $current_billing_post_id ); ?>" id="<?php echo esc_attr( $current_billing_post_id ); ?>ct_rfq_quote_fields_field">
											<?php
										}
									?>
									</td>
								

							<?php } ?>
								</tr>
						</table>

					</div>
				<?php } ?>

				</div>
					</div>
				<input type="submit" name="palce_quote" class="third-template-btn palce_quote button primary-button" value="<?php echo esc_attr (get_option('ct_rfq_place_quote_button_text') ? get_option('ct_rfq_place_quote_button_text') : 'Place Quote'); ?>" >
			</div>
		</section>
	</form>
	<?php
