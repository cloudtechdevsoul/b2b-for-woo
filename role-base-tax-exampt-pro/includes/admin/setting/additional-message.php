<?php

/**
 * General Settings of plugin
 */

add_settings_section(
	'ct-tepfw-general-sec',         // ID used to identify this section and with which to register options.
	esc_html__( '', 'cloud_tech_tepfw' ),   // Title to be displayed on the administration page.
	'', // Callback used to render the description of the section.
	'ct_tepfw_additional_message_section' // Page on which to add this section of options.
);


add_settings_field(
	'ct_tepfw_pendind_request_message',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Set Pending Request Message', 'cloud_tech_tepfw' ),    // The label to the left of the option interface element.
	'ct_tepfw_pendind_request_message',   // The name of the function responsible for rendering the option interface.
	'ct_tepfw_additional_message_section',   // The page on which this option will be displayed.
	'ct-tepfw-general-sec',
);

register_setting(
	'ct_tepfw_additional_message_fields',
	'ct_tepfw_pendind_request_message'
);

add_settings_field(
	'ct_tepfw_accepted_request_message',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Set Accepted Status Request Message', 'cloud_tech_tepfw' ),    // The label to the left of the option interface element.
	'ct_tepfw_accepted_request_message',   // The name of the function responsible for rendering the option interface.
	'ct_tepfw_additional_message_section',   // The page on which this option will be displayed.
	'ct-tepfw-general-sec',
);

register_setting(
	'ct_tepfw_additional_message_fields',
	'ct_tepfw_accepted_request_message'
);


add_settings_field(
	'ct_tepfw_rejected_request_message',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Set Rejected Status Request Message', 'cloud_tech_tepfw' ),    // The label to the left of the option interface element.
	'ct_tepfw_rejected_request_message',   // The name of the function responsible for rendering the option interface.
	'ct_tepfw_additional_message_section',   // The page on which this option will be displayed.
	'ct-tepfw-general-sec',
);

register_setting(
	'ct_tepfw_additional_message_fields',
	'ct_tepfw_rejected_request_message'
);


function ct_tepfw_pendind_request_message() {

	$content   = get_option( 'ct_tepfw_pendind_request_message' ) ;
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
	wp_editor( $content, 'ct_tepfw_pendind_request_message', $settings );

}
function ct_tepfw_accepted_request_message() {
	$content   = get_option( 'ct_tepfw_accepted_request_message' ) ;
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
	wp_editor( $content, 'ct_tepfw_accepted_request_message', $settings );

}
function ct_tepfw_rejected_request_message() {
	$content   = get_option( 'ct_tepfw_rejected_request_message' ) ;
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
	wp_editor( $content, 'ct_tepfw_rejected_request_message', $settings );

}