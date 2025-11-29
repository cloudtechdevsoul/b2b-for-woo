<?php
defined( 'ABSPATH' ) || exit();

$min_limit_range = '';
$max_limit_range = '';
$price_type      = 0;
$price           = 0;
if ('yes' == get_post_meta($field_id, 'ck_field_limit_range', true)) {
	$min_limit_range = get_post_meta($field_id, 'ck_field_min_limit', true);
	$max_limit_range = get_post_meta($field_id, 'ck_field_max_limit', true);
}

if ('yes' == get_post_meta($field_id, 'ck_field_price_checkbox', true)) {
	$price_type = get_post_meta($field_id, 'ck_field_pricing_type', true);
	$price      = get_post_meta($field_id, 'ck_field_price', true);
}

?>
<input type="tel"
style="width: 100%; height: 40px;"
name="ck_options_<?php echo esc_attr($field_id); ?>" 
class="ck_telephone <?php echo esc_attr($front_dep_class); ?>" 
id="<?php echo esc_attr($f_field_id); ?>"
data-dependent_on="<?php echo esc_attr($dependent_fields); ?>" 
data-dependent_val="<?php echo esc_attr($dependent_options); ?>"
pattern="[+]?[0-9]{<?php echo esc_attr($max_limit_range); ?>}" 
style="width: 98%;" 
data-price_type="<?php echo esc_attr($price_type . '_telephone'); ?>" 
data-price="<?php echo esc_attr($price); ?>"
<?php if ('yes' == get_post_meta($field_id, 'ck_req_field', true)) : ?>
	required
<?php endif ?>
>
<?php
