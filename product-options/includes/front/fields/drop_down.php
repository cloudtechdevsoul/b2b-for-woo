<?php
defined( 'ABSPATH' ) || exit();
?>

<select style="width: 100%; height: 40px;" id="<?php echo esc_attr($f_field_id); ?>" class="<?php echo esc_attr($front_dep_class); ?> ck_drop_down ck_option_font_<?php echo esc_attr($rule_id); ?>" name="ck_options_<?php echo esc_attr($field_id); ?>" data-dependent_on="<?php echo esc_attr($dependent_fields); ?>" data-dependent_val="<?php echo esc_attr($dependent_options); ?>"
	>
	<?php
	if ('yes' != get_post_meta($field_id, 'ck_req_field', true)) {
		?>
		<option value="0">
			<?php echo esc_html__('Select an option', 'prod_options'); ?>
		</option>
		<?php
	}
	$args = array(
		'post_type'   => 'product_add_option',
		'post_status' => 'publish',
		'post_parent' => $field_id,
		'fields'      => 'ids',
		'orderby'     => 'menu_order',
		'order'       => 'ASC',
	);

	$options = get_posts( $args );
	foreach ( $options as $option_id ) {
		if (empty($option_id)) {
			continue;
		}
		$option_name  = get_post_meta($option_id, 'ck_option_name', true);
		$price_type   = get_post_meta($option_id, 'ck_option_price_type', true);
		$option_price = (float) get_post_meta($option_id, 'ck_option_price', true);
		?>
		<option 
		value="<?php echo esc_attr($option_id); ?>" 
		data-option_name="<?php echo esc_attr($option_name); ?>" 
		data-price_type="<?php echo esc_attr($price_type . '_selecter'); ?>" 
		data-option_price="<?php echo esc_attr($option_price); ?>">
		<?php
		$ck_option_price = '';

		if ('free' == $price_type) {

			$ck_option_price = $option_name;

		} elseif ('flat_fixed_fee' == $price_type || 'fixed_fee_based_on_quantity' == $price_type ) {

			$ck_option_price = $option_name . ' (+' . get_woocommerce_currency_symbol() . ' ' . number_format($option_price, 2, '.', '') . ')';

		} elseif ('flat_percentage_fee' == $price_type || 'percentage_fee_based_on_quantity' == $price_type) {

			$ck_option_price = $option_name . ' (+' . number_format($option_price, 2, '.', '') . '%)';
		}

		echo esc_attr(' ' . $ck_option_price);
		?>
	</option>
		<?php
	}
	?>
</select>
<?php
