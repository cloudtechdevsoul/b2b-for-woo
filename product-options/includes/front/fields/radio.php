<?php
defined( 'ABSPATH' ) || exit();

$counter = 1;
$options = get_posts(
	array(
		'post_type'   => 'product_add_option',
		'post_status' => 'publish',
		'post_parent' => $field_id,
		'fields'      => 'ids',
		'orderby'     => 'menu_order',
		'order'       => 'ASC',
	)
);

$total_checkboxes = count($options);
foreach ( $options as $option_id ) {
	if (empty($option_id)) {
		continue;
	}
	$option_name  = get_post_meta($option_id, 'ck_option_name', true);
	$price_type   = get_post_meta($option_id, 'ck_option_price_type', true);
	$option_price = (float) get_post_meta($option_id, 'ck_option_price', true);

	$ck_option_price = '';

	if ('free' == $price_type) {

		$ck_option_price = $option_name;

	} elseif ('flat_fixed_fee' == $price_type || 'fixed_fee_based_on_quantity' == $price_type ) {

		$ck_option_price = $option_name . ' (+' . get_woocommerce_currency_symbol() . ' ' . number_format($option_price, 2, '.', '') . ')';

	} elseif ('flat_percentage_fee' == $price_type || 'percentage_fee_based_on_quantity' == $price_type) {

		$ck_option_price = $option_name . ' (+' . number_format($option_price, 2, '.', '') . '%)';
	}
	?>
	
	<input type="radio" 
		name="ck_options_<?php echo esc_attr($field_id); ?>"
		id="<?php echo esc_attr($f_field_id); ?>" 
		class="ck_radio_buttons <?php echo esc_attr($front_dep_class); ?>" 
		value="<?php echo esc_attr($option_id); ?>" 
		data-dependent_on="<?php echo esc_attr($dependent_fields); ?>" 
		data-dependent_val="<?php echo esc_attr($dependent_options); ?>"
		data-option_name="<?php echo esc_attr($option_name); ?>" 
		data-total_checkboxes="<?php echo esc_attr($total_checkboxes); ?>" 
		data-option_price="<?php echo esc_attr($option_price); ?>" 
		data-price_type="<?php echo esc_attr($price_type . '_radio_btn'); ?>" 
		data-field_id="<?php echo esc_attr($field_id); ?>" 
		<?php if ('yes' == get_post_meta($field_id, 'ck_req_field', true)) : ?>
			required
		<?php endif ?>
	>
	<label class="af_addon_option_font_<?php echo esc_attr($rule_id); ?>"><?php echo esc_attr(' ' . $ck_option_price); ?></label>
	<?php
	if ( end($options) != $option_id) {
			echo '<br>';
	}
}
