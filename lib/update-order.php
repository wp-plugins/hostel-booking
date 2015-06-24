<?php 

add_action( 'wp_ajax_update_order', 'hbook_update_order' );

function hbook_update_order() {

global $wpdb;

$booking_ref = sanitize_text_field( $_POST['booking_ref'] );

$name = sanitize_text_field( $_POST['new_values'][0] );

$email = sanitize_email( $_POST['new_values'][1] );

$phone = sanitize_text_field( $_POST['new_values'][2] );

$price = floatval( $_POST['new_values'][3] );


$update_orders = $wpdb->update($wpdb->prefix . "hostel_booking_orders", 
	array("name" => "$name", "email" => "$email", "phone" => "$phone", "price" => "$price" ), 
	array("booking_ref" => $booking_ref), 
	array("%s", "%s", "%s", "%d"), 
	array("%s") );

}

?>