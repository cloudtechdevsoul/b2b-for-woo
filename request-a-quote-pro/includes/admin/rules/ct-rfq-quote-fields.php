<?php
if (! defined('WPINC') ) {
	die;
}

add_action('add_meta_boxes','ct_rfq_quoted_fields_add_meta_boxes');

function ct_rfq_quoted_fields_add_meta_boxes() {

	add_meta_box(
		'ct-rfq-cart-restrictions',
		esc_html__('Field Type', 'cloud_tech_rfq'),
		'ct_rfq_quote_fields',
		'ct-rfq-quote-fields',
		'normal',
		'high'
	);
}

function ct_rfq_quote_fields() {

    $current_fields_all_post_ids    = get_posts(['post_type' => 'ct-rfq-quote-fields','post_per_page' => -1 ,'post_status' => 'publish','fields' => 'ids']);

    if( in_array( get_the_ID() , $current_fields_all_post_ids ) ) {
        unset( $current_fields_all_post_ids[ array_search(get_the_ID(),$current_fields_all_post_ids) ]);
    }

    $field_type_array               = ['date','time','file_upload','select','multi_select','radio','checkbox','multi_checkbox','countries','input','textarea','number','color','email','password','telephone'];
    $filed_show_with                = ['in_billing_fields','in_shipping_fields',]; 
    $filed_show_in               	= ['Both','private','company']; 

	?>
	<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">
        <tr>
			<th><?php echo esc_html__('Field Show With', 'cloud_tech_rfq');?></th>
			<td>
                <select required name="ct_rfq_quote_fields_show_field_with" style="width: 40%;" required class="ct-rfq-live-search">

					<?php foreach ( $filed_show_with as $show_with ){ ?>
						
							<option value="<?php echo esc_attr( $show_with ); ?>" <?php echo esc_attr( (string) $show_with  === (string) get_post_meta( get_the_ID(),'ct_rfq_quote_fields_show_field_with',true ) ? 'selected' : ''); ?> >
								<?php echo esc_attr( ucwords(str_replace('_',' ', $show_with )) ); ?>
							</option>

					<?php } ?>
				</select>
            </td>
		</tr>
		<tr>
			<th><?php echo esc_html__('Field Show in ', 'cloud_tech_rfq');?></th>
			<td>
                <select required name="ct_rfq_quote_fields_show_field_in_private_company" style="width: 40%;" required class="ct-rfq-live-search">

					<?php foreach ( $filed_show_in as $show_with ){ ?>
						
							<option value="<?php echo esc_attr( $show_with ); ?>" <?php echo esc_attr( (string) $show_with  === (string) get_post_meta( get_the_ID(),'ct_rfq_quote_fields_show_field_in_private_company',true ) ? 'selected' : ''); ?> >
								<?php echo esc_attr( ucwords(str_replace('_',' ', $show_with )) ); ?>
							</option>

					<?php } ?>
				</select>
            </td>
		</tr>
		<tr style="display:none;">
			<th><?php echo esc_html__('Is this Dependent', 'cloud_tech_rfq');?></th>
			<td><input type="checkbox" name="ct_rfq_quote_fields_is_this_dependent" id="ct_rfq_quote_fields_select_dependent_field_checkbox" value="checked" <?php echo esc_attr( get_post_meta( get_the_ID(),'ct_rfq_quote_fields_is_this_dependent',true ) ); ?>></td>
		</tr>
        <tr style="display:none;">
			<th><?php echo esc_html__('Select Dependent Field', 'cloud_tech_rfq');?></th>
			<td>
				<select name="ct_rfq_quote_fields_select_dependent_field" style="width: 40%;" class="ct_rfq_quote_fields_select_dependent_field ct-rfq-live-search">

					<?php foreach ( $current_fields_all_post_ids as $curr_post_id ){ ?>
						
						<option value="<?php echo esc_attr( $curr_post_id ); ?>"  data-field_type="<?php echo esc_attr( get_post_meta( $curr_post_id,'ct_rfq_quote_fields_field_type',true ) ); ?>" <?php echo esc_attr( (string) $curr_post_id  === (string) get_post_meta( get_the_ID(),'ct_rfq_quote_fields_select_dependent_field',true ) ? 'selected' : ''); ?> >
							<?php echo esc_attr( get_the_title( $curr_post_id ) ) ; ?>
						</option>

					<?php } ?>

				</select>
				<br>
				<?php foreach ( $current_fields_all_post_ids as $curr_post_id ){
					
					if( ! str_contains( 'select multi_checkbox radio multi_select' , get_post_meta( $curr_post_id,'ct_rfq_quote_fields_field_type',true ) ) ){
						continue;
					}

					$is_multi_select 	= 'multi_checkbox' == get_post_meta( $curr_post_id,'ct_rfq_quote_fields_field_type',true ) || 'multi_select' == get_post_meta( $curr_post_id,'ct_rfq_quote_fields_field_type',true ) ? 'multiple' : '';
					
					?>
					<select <?php echo esc_attr( $is_multi_select ); ?> name="ct_rfq_quote_fields_selected_dependent_field_value[<?php echo esc_attr( $curr_post_id ); ?>]" style="width: 40%;" class="ct_rfq_quote_fields_selected_dependent_field_value ct_rfq_quote_fields_selected_dependent_field_value<?php echo esc_attr( $curr_post_id ); ?>">

					<?php foreach ( (array) get_post_meta( $curr_post_id , 'ct_rfq_request_a_quote_options_value_and_label' , true ) as $key => $value) {
							if( is_array( $value ) ){
								?>
								<option value="<?php echo esc_attr( $value['option_value'] ? $value['option_value'] : ''); ?>" >
									<?php echo esc_attr( $value['option_label'] ? $value['option_label'] : ''); ?>
								</option>
								<?php
							}
                		} 
					?>	

					</select>

				<?php } ?>
			</td>
		</tr>
		<tr>
			<th><?php echo esc_html__('Field Label', 'cloud_tech_rfq');?></th>
			<td><input type="text" name="ct_rfq_quote_fields_field_label" id="" value="<?php echo esc_attr( get_post_meta( get_the_ID(),'ct_rfq_quote_fields_field_label',true ) ); ?>" ></td>
		</tr>
		<tr>
			<th><?php echo esc_html__('Field Default Value', 'cloud_tech_rfq');?></th>
			<td><input type="text" name="ct_rfq_quote_fields_field_default_value" id="" value="<?php echo esc_attr( get_post_meta( get_the_ID(),'ct_rfq_quote_fields_field_default_value',true ) ); ?>" ></td>
		</tr>
		<tr>
			<th><?php echo esc_html__('Field Place Holder', 'cloud_tech_rfq');?></th>
			<td><input type="text" name="ct_rfq_quote_fields_field_placeholder" id="" value="<?php echo esc_attr( get_post_meta( get_the_ID(),'ct_rfq_quote_fields_field_placeholder',true ) ); ?>" ></td>
		</tr>
		<tr>
			<th><?php echo esc_html__('Additional Class', 'cloud_tech_rfq');?></th>
			<td><input type="text" name="ct_rfq_quote_fields_field_additonal_class" id="" value="<?php echo esc_attr( get_post_meta( get_the_ID(),'ct_rfq_quote_fields_field_additonal_class',true ) ); ?>" ></td>
		</tr>
        <tr>
			<th><?php echo esc_html__('Field Type', 'cloud_tech_rfq');?></th>
			<td>
				<select name="ct_rfq_quote_fields_field_type" style="width: 40%;" class="ct-rfq-quote-fields-field-type ct-rfq-live-search">

					<?php foreach ( $field_type_array as $field_type ){ ?>
						
							<option value="<?php echo esc_attr( $field_type ); ?>" <?php echo esc_attr( (string) $field_type  === (string) get_post_meta( get_the_ID(),'ct_rfq_quote_fields_field_type',true ) ? 'selected' : ''); ?> >
								<?php echo esc_attr( ucwords(str_replace('_',' ', $field_type )) ); ?>
							</option>
					<?php } ?>
				</select>
			</td>
		</tr>
        
	</table>
    <div class="ct-rfq-quote-fields-add-options">

        <table class="wp-list-table widefat fixed striped table-view-list customers dataTable ct-rfq-quote-fields-add-options-table">
            <thead>
                <tr>
                    <th><?php echo esc_html__('Option Value', 'cloud_tech_rfq');?></th>
                    <th><?php echo esc_html__('Option label', 'cloud_tech_rfq');?></th>
                    <th><?php echo esc_html__('Action', 'cloud_tech_rfq');?></th>
                </tr>
            </thead>
            <tbody>
                <?php 

                foreach ( (array) get_post_meta( get_the_ID() , 'ct_rfq_request_a_quote_options_value_and_label' , true ) as $key => $value) {
                    if( is_array( $value ) ){
                        ?>
                        <tr index="<?php echo esc_attr( $key ); ?>">
                            <td><input name="ct_rfq_request_a_quote_options_value_and_label[<?php echo esc_attr( $key ); ?>][option_value]" type="text" value="<?php echo esc_attr( $value['option_value'] ? $value['option_value'] : ''); ?>"></td>
                            <td><input name="ct_rfq_request_a_quote_options_value_and_label[<?php echo esc_attr( $key ); ?>][option_label]" type="text" value="<?php echo esc_attr( $value['option_label'] ? $value['option_label'] : ''); ?>"></td>
                            <td><i class="fa fa-trash ct-rfq-quote-remove-quote-options"></i></td>
                        </tr>
                        <?php
                    }
                }                
                ?>
            </tbody>
	    </table>
        <i class="ct-rfq-quote-fields-add-new-options fa fa-plus button primary-button"><?php echo esc_html('Add New','cloud_tech_rfq'); ?></i>
    </div>

	<?php
}

// Add custom columns
function custom_columns_head($defaults) {
    unset( $defaults['date'] );
	$defaults['field_name'] = 'Field Name';
    $defaults['field_type'] = 'Field Type';
    $defaults['show_with'] = 'Show With';
    $defaults['show_with_private_or_company'] = 'Show With Private/Company';
	$defaults['date'] = 'Date';

    return $defaults;
}

add_filter('manage_ct-rfq-quote-fields_posts_columns', 'custom_columns_head');

// Display custom column data
function custom_columns_content($column_name, $post_id) {
    if ('field_name' == $column_name ) {
		echo esc_attr(ucfirst(str_replace('_',' ', get_post_meta($post_id, 'ct_rfq_quote_fields_field_label', true))));
    }
    if ('field_type' == $column_name ) {
        echo esc_attr(ucfirst(str_replace('_',' ', get_post_meta($post_id, 'ct_rfq_quote_fields_field_type', true))));
    }

    if ( 'show_with' == $column_name ) {
        echo esc_attr(ucfirst(str_replace('_',' ', get_post_meta($post_id, 'ct_rfq_quote_fields_show_field_with', true))));
    }
	if ( 'show_with_private_or_company' == $column_name ) {
        echo esc_attr(ucfirst(str_replace('_',' ', get_post_meta($post_id, 'ct_rfq_quote_fields_show_field_in_private_company', true))));
    }
}

add_action('manage_ct-rfq-quote-fields_posts_custom_column', 'custom_columns_content', 10, 2);


