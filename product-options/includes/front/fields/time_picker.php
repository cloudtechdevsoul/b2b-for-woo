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
?>

<input type="time"
	style="width: 100%; height: 40px;"
	name="ck_options_<?php echo esc_attr($field_id); ?>" 
	data-dependent_on="<?php echo esc_attr($dependent_fields); ?>" 
	data-dependent_val="<?php echo esc_attr($dependent_options); ?>"
	class="ck_time_picker <?php echo esc_attr($front_dep_class); ?>" 
	id="<?php echo esc_attr($f_field_id); ?>"
	style="width: 50%;" 
	data-price_type="<?php echo esc_attr($price_type . '_time_picker'); ?>" 
	data-price="<?php echo esc_attr($price); ?>"
	<?php if ('yes' == get_post_meta($field_id, 'ck_req_field', true)) : ?>
		required
	<?php endif ?>
>
<?php
