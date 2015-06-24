<?php

add_action( 'wp_ajax_delete_order', 'hbook_delete_order' );

function hbook_delete_order() {

$booking_ref = sanitize_text_field( $_POST['booking_ref'] );

global $wpdb;

$delete_order = $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "hostel_booking_orders WHERE booking_ref=%s", $booking_ref));
$delete_resv = $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "hostel_booking_resv WHERE booking_ref=%s", $booking_ref));

}

?>