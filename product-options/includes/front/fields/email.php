<?php
defined( 'ABSPATH' ) || exit();

$price_type = '';
$price      = '';

if ('yes' == get_post_meta($field_id, 'ck_field_price_checkbox', true)) {
	$price_type = get_post_meta($field_id, 'ck_field_pricing_type', true);

	$price = get_post_meta($field_id, 'ck_field_price', true);
} else {
	$price_type = 0;
	$price      = 0;
}

$addon_price = '';
if ('flat_fixed_fee' == $price_type || 'fixed_fee_based_on_quantity' == $price_type ) {
	$addon_price = ' (+' . get_woocommerce_currency_symbol() . ' ' . number_format($price, 2, '.', '') . ')';
} elseif ('flat_percentage_fee' == $price_type || 'Percentage_fee_based_on_quantity' == $price_type) {
	$addon_price = ' (+' . number_format($price, 2, '.', '') . '%)';
}
?>

<input type="email"
style="width: 100%; height: 40px;"
data-dependent_on="<?php echo esc_attr($dependent_fields); ?>" 
data-dependent_val="<?php echo esc_attr($dependent_options); ?>" 
name="ck_options_<?php echo esc_attr($field_id); ?>" 
class="ck_email_option <?php echo esc_attr($front_dep_class); ?>" 
id="<?php echo esc_attr($f_field_id); ?>" 
data-price_type="<?php echo esc_attr($price_type . '_email'); ?>" 
data-price="<?php echo esc_attr($price); ?>"
<?php if ('yes' == get_post_meta($field_id, 'ck_req_field', true)) : ?>
	required
<?php endif ?>
>
