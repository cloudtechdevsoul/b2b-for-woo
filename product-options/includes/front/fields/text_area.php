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

<textarea 
name="ck_options_<?php echo esc_attr($field_id); ?>" 
style="width: 100%; height: 100px;"
class="ck_text_area <?php echo esc_attr($front_dep_class); ?>" 
id="<?php echo esc_attr($f_field_id); ?>" 
data-dependent_on="<?php echo esc_attr($dependent_fields); ?>" 
data-dependent_val="<?php echo esc_attr($dependent_options); ?>"
<?php if (!empty($min_limit_range)) : ?>
	minlength='<?php echo intval($min_limit_range); ?>'
<?php endif ?>
<?php if (!empty($max_limit_range)) : ?>
	maxlength='<?php echo intval($max_limit_range); ?>'
<?php endif ?>
data-price_type="<?php echo esc_attr($price_type . '_long_text'); ?>" 
data-price="<?php echo esc_attr($price); ?>"
<?php if ('yes' == get_post_meta($field_id, 'ck_req_field', true)) : ?>
	required
<?php endif ?>
></textarea>
<?php
