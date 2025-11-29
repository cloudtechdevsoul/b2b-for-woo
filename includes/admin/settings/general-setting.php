<?php

if ( ! defined('ABSPATH') ) {
	exit;
}


add_settings_section(
	'ct_rbpaqp_customer_table',
	'',
	'',
	'ct_rbpaqp_general_settings_page',
);

add_settings_field(
	'ct_rbpaqp_excludes_rule',
	esc_html__( 'Exclude User Role From Role List', ' cloud_tech_rbpaqpfw' ),
	'ct_rbpaqp_excludes_rule',
	'ct_rbpaqp_general_settings_page',
	'ct_rbpaqp_customer_table' 
);
register_setting(
	'ct_rbpaqp_general_settings',
	'ct_rbpaqp_excludes_rule'
);

function ct_rbpaqp_excludes_rule() {

	global $wp_roles;

	$all_role 			= $wp_roles->get_names();
	$all_role['guest'] 	= 'Guest';

	?>
	<select style="width: 40%;" name="ct_rbpaqp_excludes_rule[]" class="ct-rbpaqp-live-search" multiple >

		<?php foreach ($all_role as $role_key =>  $value) { ?>

			<option value="<?php echo esc_attr( $role_key ); ?>" 
				<?php if ( in_array($role_key , (array) get_option('ct_rbpaqp_excludes_rule') ) ) {  ?>
					selected <?php } ?>>
					<?php  echo esc_html__( ucwords( $value ) );  ?>
				</option>

			<?php } ?>

		</select>

		<p><?php echo esc_html__('Select user roles to exclude from list' , 'cloud_tech_rbpaqpfw'); ?></p>

		<?php
	}


	add_settings_field(
		'ct_rbpaqp_show_role_pricing_on_product_page',
		esc_html__( 'Show Role Base Pricing On Product Page', ' cloud_tech_rbpaqpfw' ),
		'ct_rbpaqp_show_role_pricing_on_product_page',
		'ct_rbpaqp_general_settings_page',
		'ct_rbpaqp_customer_table' 
	);
	register_setting(
		'ct_rbpaqp_general_settings',
		'ct_rbpaqp_show_role_pricing_on_product_page'
	);

	function ct_rbpaqp_show_role_pricing_on_product_page() {

		global $wp_roles;

		?>
		<input type="checkbox" name="ct_rbpaqp_show_role_pricing_on_product_page" value="yes" <?php if ( !empty( get_option('ct_rbpaqp_show_role_pricing_on_product_page') ) ) {  ?> checked <?php } ?>>

		<p><?php echo esc_html__('Enable checkbox to get show role base pricing table on product page ' , 'cloud_tech_rbpaqpfw'); ?></p>

		<?php
	}

	add_settings_field(
		'ct_rbpaqp_set_role_pricing_on_title',
		esc_html__( 'Set Role Base Pricing On Title', ' cloud_tech_rbpaqpfw' ),
		'ct_rbpaqp_set_role_pricing_on_title',
		'ct_rbpaqp_general_settings_page',
		'ct_rbpaqp_customer_table' 
	);
	register_setting(
		'ct_rbpaqp_general_settings',
		'ct_rbpaqp_set_role_pricing_on_title'
	);

	function ct_rbpaqp_set_role_pricing_on_title() {

		global $wp_roles;

		?>
		<input type="text" name="ct_rbpaqp_set_role_pricing_on_title" value="<?php echo esc_attr( get_option('ct_rbpaqp_set_role_pricing_on_title') ); ?>">
		<?php
	}

	add_settings_field(
		'ct_rbpaqp_set_min_max_table_title',
		esc_html__( 'Set Title For Min Max Qty Table', ' cloud_tech_rbpaqpfw' ),
		'ct_rbpaqp_set_min_max_table_title',
		'ct_rbpaqp_general_settings_page',
		'ct_rbpaqp_customer_table' 
	);
	register_setting(
		'ct_rbpaqp_general_settings',
		'ct_rbpaqp_set_min_max_table_title'
	);
	function ct_rbpaqp_set_min_max_table_title() {
		global $wp_roles;
		?>
		<input type="text" name="ct_rbpaqp_set_min_max_table_title" value="<?php echo esc_attr( get_option('ct_rbpaqp_set_min_max_table_title') ); ?>">
		<?php
	}