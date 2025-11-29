<?php
$accepted_request_email_Setting 	= (array) get_option('ct_tepfw_accepted_request_email');
$rejected_request_email_Setting 	= (array) get_option('ct_tepfw_rejected_request_email');
$pending_request_email_Setting 		= (array) get_option('ct_tepfw_pending_request_email');

?>
<table class="form-table">
	<tr>
		<th><?php echo esc_html__('Enable Settings To Send Email To Pending Request User','cloud_tech_tepfw') ?></th>
		<td>
			<input type="checkbox" name="ct_tepfw_pending_request_email[enable_setting]" value="yes" <?php if ( isset( $pending_request_email_Setting['enable_setting'] ) && !empty( $pending_request_email_Setting['enable_setting'] ) ): ?>
			checked
			<?php endif ?> >
		</td>
	</tr>
	<tr>
		<th><?php echo esc_html__('Subject','cloud_tech_tepfw') ?></th>
		<td>
			<input type="text" name="ct_tepfw_pending_request_email[subject]" value="<?php echo esc_attr( isset( $pending_request_email_Setting['subject'] ) ? $pending_request_email_Setting['subject'] : ''  ) ; ?>">
		</td>
	</tr>
	<tr>
		<th><?php echo esc_html__('Content','cloud_tech_tepfw') ?></th>
		<td>
			<?php
			$content   = isset( $pending_request_email_Setting['content'] ) ? $pending_request_email_Setting['content'] : '' ;
			$settings  = array(
				'wpautop'       => false,
				'tinymce'       => true,
				'media_buttons' => false,
				'textarea_rows' => 10,
				'quicktags'     => array( 'buttons' => 'em,strong,link' ),
				'quicktags'     => true,
				'tinymce'       => array(
					'toolbar1' => 'bold,italic,link,unlink,undo,redo',
				),
			);
			wp_editor( $content, 'ct_tepfw_pending_request_email_content', $settings );
			?>
		</td>
	</tr>


	<tr>
		<th><?php echo esc_html__('Enable Settings To Send Email To Approved User','cloud_tech_tepfw') ?></th>
		<td>
			<input type="checkbox" name="ct_tepfw_accepted_request_email[enable_setting]" value="yes" <?php if ( isset( $accepted_request_email_Setting['enable_setting'] ) && !empty( $accepted_request_email_Setting['enable_setting'] ) ): ?>
			checked
			<?php endif ?> >
		</td>
	</tr>
	<tr>
		<th><?php echo esc_html__('Subject','cloud_tech_tepfw') ?></th>
		<td>
			<input type="text" name="ct_tepfw_accepted_request_email[subject]" value="<?php echo esc_attr( isset( $accepted_request_email_Setting['subject'] ) ? $accepted_request_email_Setting['subject'] : ''  ) ; ?>">
		</td>
	</tr>
	<tr>
		<th><?php echo esc_html__('Content','cloud_tech_tepfw') ?></th>
		<td>
			<?php
			$content   = isset( $accepted_request_email_Setting['content'] ) ? $accepted_request_email_Setting['content'] : '' ;
			$settings  = array(
				'wpautop'       => false,
				'tinymce'       => true,
				'media_buttons' => false,
				'textarea_rows' => 10,
				'quicktags'     => array( 'buttons' => 'em,strong,link' ),
				'quicktags'     => true,
				'tinymce'       => array(
					'toolbar1' => 'bold,italic,link,unlink,undo,redo',
				),
			);
			wp_editor( $content, 'ct_tepfw_accepted_request_email_content', $settings );
			?>
		</td>
	</tr>
	<tr>
		<th><?php echo esc_html__('Enable Settings To Send Email To Rejected User','cloud_tech_tepfw') ?></th>
		<td>
			<input type="checkbox" name="ct_tepfw_rejected_request_email[enable_setting]" value="yes" <?php if ( isset( $rejected_request_email_Setting['enable_setting'] ) && !empty( $rejected_request_email_Setting['enable_setting'] ) ): ?>
			checked
			<?php endif ?> >
		</td>
	</tr>
	<tr>
		<th><?php echo esc_html__('Subject','cloud_tech_tepfw') ?></th>
		<td>
			<input type="text" name="ct_tepfw_rejected_request_email[subject]" value="<?php echo esc_attr( isset( $rejected_request_email_Setting['subject'] ) ? $rejected_request_email_Setting['subject'] : ''  ) ; ?>">
		</td>
	</tr>
	<tr>
		<th><?php echo esc_html__('Content','cloud_tech_tepfw') ?></th>
		<td>
			<?php
			$content   = isset( $rejected_request_email_Setting['content'] ) ? $rejected_request_email_Setting['content'] : '' ;
			$settings  = array(
				'wpautop'       => false,
				'tinymce'       => true,
				'media_buttons' => false,
				'textarea_rows' => 10,
				'quicktags'     => array( 'buttons' => 'em,strong,link' ),
				'quicktags'     => true,
				'tinymce'       => array(
					'toolbar1' => 'bold,italic,link,unlink,undo,redo',
				),
			);
			wp_editor( $content, 'ct_tepfw_rejected_request_email_content', $settings );
			?>
		</td>
	</tr>
</table>

<i><?php echo esc_html__('Here we have some variable for help to print data of customer in email {customer_email} to print email ','cloud_tech_tepfw') ?></i>

<ul style="list-style-type: none;">
	<li><?php echo esc_html__('{user_my_account_link} to print user my account ','cloud_tech_tepfw') ?></li>
	<li><?php echo esc_html__('{user_email} to print email ','cloud_tech_tepfw') ?></li>
	<li><?php echo esc_html__('{display_name} to print Display Name ','cloud_tech_tepfw') ?></li>
	<li><?php echo esc_html__('{user_status} to print request status ','cloud_tech_tepfw') ?></li>


</ul>
