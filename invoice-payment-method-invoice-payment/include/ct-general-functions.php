<?php


function ct_ipmaip_send_invoice_pdf($order_id)
{
    $pdf = generate_invoice_pdf_using_order_id($order_id);
    $order_number = $order_id;
    $order = wc_get_order($order_number);
    $to = $order->get_billing_email();
    $subject = get_option('cloudtect_invoice_email_subject');
    $message = get_option('cloudtect_invoice_email_message');
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $attachments = array(
        array(
            'name' => 'invoice.pdf',
            'data' => $pdf,
        )
    );
    foreach ($attachments as $attachment) {
        $file_path = sys_get_temp_dir() . '/' . $attachment['name'];
        file_put_contents($file_path, $attachment['data']);
        $attached = wp_mail($to, $subject, $message, $headers, $file_path);
        unlink($file_path);
    }
}