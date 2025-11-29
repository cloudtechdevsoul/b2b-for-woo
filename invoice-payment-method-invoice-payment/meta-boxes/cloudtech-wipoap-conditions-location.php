<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get Post Meta.
$sel_countries = (array) ( get_post_meta( get_the_ID(), 'cloudtech_wipo_countries', true ) );
$sel_states    = (array) ( get_post_meta( get_the_ID(), 'cloudtech_wipo_states', true ) );
$sel_zips      = get_post_meta( get_the_ID(), 'cloudtech_wipo_zip_codes', true );
$sel_cities    = get_post_meta( get_the_ID(), 'cloudtech_wipo_cities', true );

// General Variables.
$countires = WC()->countries->get_countries();
?>

<div class="extra-fee-conditions">
	<?php wp_nonce_field( 'cloudtech_wipo_nonce_action', 'cloudtech_wipo_field_nonce' ); ?>
	<table class="addify-table-optoin">
		<tr class="addify-option-field">
			<th>
				<div class="option-head">
					<h3>
						<?php echo esc_html__( 'Select Countries', 'cloudtech_wipo' ); ?>
					</h3>
				</div>
			</th>
			<td>
				<select name="cloudtech_wipo_countries[]" id="cloudtech_wipo_countries" class="af-ig-select2" multiple>
					<?php foreach ( $countires as $key => $value ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" 
							<?php echo in_array( (string) $key, $sel_countries, true ) ? 'selected' : ''; ?>
							>
							<?php echo esc_attr( $value ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<p><?php echo esc_html__( 'Choose Countries. Leave it empty for all countries.', 'cloudtech_wipo' ); ?></p>
			</td>
		</tr>
		<tr class="addify-option-field">
			<th>
				<div class="option-head">
					<h3>
						<?php echo esc_html__( 'Select States', 'cloudtech_wipo' ); ?>
					</h3>
				</div>
			</th>
			<td>
				<select name="cloudtech_wipo_states[]" id="cloudtech_wipo_states" class="af-ig-select2" multiple>
					<?php

					foreach ( $countires as $key => $val ) {
						$states = WC()->countries->get_states( $key );
						if ( empty( $states ) ) {
							continue;
						} else {
							echo '<optgroup label="' . esc_attr( $val ) . '">';
							foreach ( $states as $key1 => $value ) {

								$state_val = esc_attr( $key ) . ':' . esc_attr( $key1 );
								?>
								<option value="<?php echo esc_attr( $state_val ); ?>"
									<?php echo in_array( (string) $state_val, $sel_states, true ) ? 'selected' : ''; ?>
									>
									<?php echo esc_attr( $val ) . ' &mdash; ' . esc_attr( $value ); ?>
								</option>
								<?php
							}
							echo '</optgroup>';
						}
					}
					?>
				</select>
				<p><?php echo esc_html__( 'Select States. Leave empty for all states.', 'cloudtech_wipo' ); ?></p>
			</td>
		</tr>
		<tr class="addify-option-field">
			<th>
				<div class="option-head">
					<h3>
						<?php echo esc_html__( 'Select Cities', 'cloudtech_wipo' ); ?>
					</h3>
				</div>
			</th>
			<td>
				<textarea name="cloudtech_wipo_cities" id="cloudtech_wipo_cities" cols="45" rows="5"><?php echo esc_attr( $sel_cities ); ?></textarea>
				<p><?php echo esc_html__( 'Select Cities. Leave it empty for all cities', 'cloudtech_wipo' ); ?></p>
				<p><?php echo esc_html__( 'Insert all Cities separated by comma(,). e.g. new york,Rawalpindi,Lahore etc', 'cloudtech_wipo' ); ?></p>
			</td>
		</tr>
		<tr class="addify-option-field">
			<th>
				<div class="option-head">
					<h3>
						<?php echo esc_html__( 'Select Zip Codes', 'cloudtech_wipo' ); ?>
					</h3>
				</div>
			</th>
			<td>
				<textarea name="cloudtech_wipo_zip_codes" id="cloudtech_wipo_zip_codes" cols="45" rows="5"><?php echo esc_attr( $sel_zips ); ?></textarea>
				<p><?php echo esc_html__( 'Select Zip Codes. Leave it empty for all Zip Codes.', 'cloudtech_wipo' ); ?></p>
				<p><?php echo esc_html__( 'Insert all zip codes separated by comma(,). e.g. 45000,46000,47000 etc', 'cloudtech_wipo' ); ?></p>
				<p><?php echo esc_html__( 'For range of zip codes use hyphen(-). e.g. 45000-46000', 'cloudtech_wipo' ); ?></p>
			</td>
		</tr>        
	</table>
</div>
