<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sel_customers1 = (array) ( get_post_meta( get_the_ID(), 'cloudtech_wipo_customer_select', true ) );
$sel_roles1     = (array) ( get_post_meta( get_the_ID(), 'cloudtech_wipo_customer_roles', true ) );
?>
<div class="extra-fee-conditions">
	<?php wp_nonce_field( 'cloudtech_wipo_nonce_action', 'cloudtech_wipo_field_nonce' ); ?>
	<table class="addify-table-optoin">
		<tr class="addify-option-field">
			<th>
				<div class="option-head">
					<h3>
						<?php echo esc_html__( 'Enable Invoice for customers', 'cloudtech_wipo' ); ?>
					</h3>
				</div>
			</th>
			<td>
				<select name="cloudtech_wipo_customer_select[]"  data-placeholder="Select Customers..." class=" cloudtech_wipo_ajax_customer_search" multiple="multiple">
					<?php
					foreach ( $sel_customers1 as $usr1 ) {
						$author_obj = get_user_by( 'id', $usr1 );
						if ( !  $author_obj  ) {
							continue;
						}
						?>
						<option value="<?php echo intval( $usr1 ); ?>" selected="selected"><?php echo esc_attr( $author_obj->display_name ); ?>(<?php echo esc_attr( $author_obj->user_email ); ?>)</option>
						<?php
					}
					?>
				</select>
				<p><?php echo esc_html__( 'Search and select customers Leave empty for all.', 'cloudtech_wipo' ); ?></p>
			</td>
		</tr>
		<tr class="addify-option-field">
			<th>
				<div class="option-head">
					<h3>
						<?php echo esc_html__( 'Enable Invoice for User Roles', 'cloudtech_wipo' ); ?>
					</h3>
				</div>
			</th>
			<td>
				<div class="all_cats">
					<select name="cloudtech_wipo_customer_roles[]" multiple class="af-ig-select2" id="af-ig-select2">
						
						<ul>
							<?php
							global $wp_roles;
							$roles = $wp_roles->get_names();
							$roles['guest'] = 'Guest';
							foreach ( $roles as $key => $value ) {
								?>
								<option value="<?php echo esc_attr( $key ); ?>" 
									<?php
									if ( ! empty( $sel_roles1 ) && in_array( (string) $key, $sel_roles1, true ) ) {
										echo 'selected';
									}
								?>>

								<?php echo esc_attr( $value ); ?>
							</option>

						<?php } ?>
					</select>
				</div>
				<p><?php echo esc_html__( 'Select user roles leave empty for all.', 'cloudtech_wipo' ); ?></p>
			</td>
		</tr>
	</table>
</div>
