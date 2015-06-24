<?php

add_action( 'wp_ajax_fetch_bookings', 'hbook_fetch_bookings' );
add_action( 'wp_ajax_nopriv_fetch_bookings', 'hbook_fetch_bookings' );

function hbook_fetch_bookings() {

    $current_year = intval( $_POST["currentYear"] );
    $current_month = intval( $_POST["currentMonth"] );
    $second_month = intval( $_POST["secondMonth"] );

global $wpdb;


$resv_sql = "SELECT year, month, dates, room, pending, booking_ref  FROM " . $wpdb->prefix . "hostel_booking_resv WHERE year = $current_year AND (month = $current_month OR month = $second_month)";    
$current_resv = $wpdb->get_results($resv_sql);


   $json_response = array();
    
    foreach($current_resv as $row1) {

        $row_array['dates'] = explode(",", $row1->dates);
        $row_array['room'] = $row1->room;
        $row_array['pending'] = $row1->pending;
        $row_array['booking_ref'] = $row1->booking_ref;
        $row_array['month'] = $row1->month;
        $row_array['year'] = $row1->year;

        //push the values in the array
        array_push($json_response,$row_array);
    }


// Code to get all orders from DB, cross reference and attach to each reservation under a new array named order

$orders_sql = "SELECT * FROM " . $wpdb->prefix . "hostel_booking_orders WHERE year = $current_year AND (month = $current_month OR month = $second_month)";    
$current_orders = $wpdb->get_results($orders_sql);


$orders = array();
    
    foreach ($current_orders as $row1) {

        $orders_array[$row1->booking_ref] = array("name" => $row1->name, "email" => $row1->email, "phone" => $row1->phone, "booking_ref" => $row1->booking_ref);
        //push the values in the array
        array_push($orders,$orders_array);
    }

  $orders_json = json_encode($orders, JSON_NUMERIC_CHECK);

    foreach ($json_response as $reskey => $resvalue) {

        foreach ($orders as $key => $value) {
                
                foreach ($value as $ikey => $ivalue) {
                  
                    if($json_response[$reskey]['booking_ref'] == $ivalue['booking_ref']) {
                    $json_response[$reskey]['order'] = array();

                    $json_response[$reskey]['order'] = $ivalue;

                    } else {
                    } 
                
                }
        }
    } 


  $reservations = json_encode($json_response, JSON_NUMERIC_CHECK);
  echo $reservations;

  wp_die();

}

?>