<?php

add_action( 'wp_ajax_update_room', 'hbook_update_room' );

function hbook_update_room() {

global $wpdb;

$room_id = intval( $_POST['room_id'] );

$name = sanitize_text_field( $_POST['new_values'][1] );

$price = floatval( $_POST['new_values'][2] );

$beds = intval( $_POST['new_values'][3] );

$type = sanitize_text_field( $_POST['new_values'][4] );

$bathroom = sanitize_text_field( $_POST['new_values'][5] );

$display = intval( $_POST['new_values'][6] );

$gender = intval( $_POST['new_values'][7] );


$update_room = $wpdb->update($wpdb->prefix . "hostel_booking_rooms", 
	array("name" => "$name", "price" => "$price", "beds" => "$beds", "type" => "$type", "bathroom" => "$bathroom", "display" => "$display", "gender" => "$gender" ), 
	array("room_id" => $room_id), 
	array( "%s", "%f", "%d", "%s", "%s", "%d", "%d"), 
	array("%d") );

wp_die();

}
?>