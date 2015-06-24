<?php

add_action( 'wp_ajax_hbook_confirm_order', 'hbook_confirm_order' );

function hbook_confirm_order() {

global $wpdb;


    $booking_ref = sanitize_text_field( $_POST["booking_ref"] );
    $action = $_POST["condel"];


    if($action == "confirm") {

    $confirm_orders = $wpdb->update($wpdb->prefix . "hostel_booking_orders", 
    array( "pending" => 0 ), 
    array("booking_ref" => $booking_ref), 
    array("%d"), 
    array("%s") );

    $confirm_resv = $wpdb->update($wpdb->prefix . "hostel_booking_resv", 
    array( "pending" => 0 ), 
    array("booking_ref" => $booking_ref), 
    array("%d"), 
    array("%s") );

    echo "Booking successfully confirmed.";
    }

    if ($action == "delete") {
    $delete_order = $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "hostel_booking_orders WHERE booking_ref=%s", $booking_ref));
    
    $delete_resv = $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "hostel_booking_resv WHERE booking_ref=%s", $booking_ref));

    echo "Booking successfully removed.";
    }

}

?>