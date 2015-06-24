<?php


/**
* Plugin Name: Hostel Booking
* Plugin URI: http://plugin.hostelbooked.com/
* Description: Hostel Booking System with Calendar Interface
* Version: 1.0.0
* Author: HostelBooked
* Author URI: http://plugin.hostelbooked.com/
* License: GPL12
*/



// Add item to menu in dashboard:

add_action('admin_menu', 'hostel_booking_menu');
function hostel_booking_menu() {
add_menu_page('Hostel Calendar Settings', 'Hostel Booking', 'administrator', 'hostel-booking-settings', 'hostel_booking_settings_page', 'dashicons-admin-generic');
add_submenu_page( 'hostel-booking-settings', 'Create Delete Rooms', 'Rooms', 'administrator', 'hostel-booking-settings-rooms', 'hostel_booking_settings_page_rooms' );
add_submenu_page( 'hostel-booking-settings', 'Orders', 'Orders', 'administrator', 'hostel-booking-settings-orders', 'hostel_booking_settings_page_orders' );
add_submenu_page( 'hostel-booking-settings', 'Settings', 'Settings', 'administrator', 'hostel-booking-settings-reservations', 'hostel_booking_settings_page_reservations' );
}


function hostel_booking_settings_page() {
include 'backend-calendar.php';
}
function hostel_booking_settings_page_rooms() {
include 'view-create-rooms.php';
}
function hostel_booking_settings_page_orders() {
include 'view-delete-orders.php';
}
function hostel_booking_settings_page_reservations() {
include 'settings.php';
}

// add settings options to the page:

add_action( 'admin_init', 'hostel_booking_settings' );
function hostel_booking_settings() {
register_setting( 'hostel-booking-settings-group', 'accountant_name' );
register_setting( 'hostel-booking-settings-group', 'accountant_phone' );
register_setting( 'hostel-booking-settings-group', 'accountant_email' );
}

add_action( 'admin_enqueue_scripts', 'hostel_booking_scripts' );
add_action( 'wp_enqueue_scripts', 'hostel_booking_scripts' );

function hostel_booking_scripts() {
  wp_register_script(  'hbook-backend-script', plugins_url( '/js/backend-script.js', __FILE__ ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-selectable' ) );
  wp_register_script(  'hbook-frontend-script', plugins_url( '/js/frontend-script.js', __FILE__ ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-selectable' ) );

  wp_register_style( 'hbook-custom-style', plugins_url( 'hostel-plugin-style.css', __FILE__ ) );
  wp_register_style( 'hbook-backend-style', plugins_url( 'backend-css.css', __FILE__ ) );
  wp_register_style( 'hbook-settings-style', plugins_url( 'settings-style.css', __FILE__ ) );
}

include 'lib/process-room.php';
include 'lib/confirm-order.php';
include 'lib/delete-room.php';
include 'lib/fetch-bookings.php';
include 'lib/process-booking.php';
include 'lib/process-settings.php';
include 'lib/update-order.php';
include 'lib/update-room.php';
include 'lib/delete-order.php';
// include '/lib/search-orders.php';



// Creation of shortcode and function to run when shortcode is used:
function show_frontend_calendar(){
          ob_start();
          include 'frontend-calendar.php';
          $content = ob_get_clean();
          return $content;
}
add_shortcode( 'hostel-booking', 'show_frontend_calendar');



// Create a hook for when the plugin is installed, and create the tables for the DB
register_activation_hook( __FILE__, 'hostel_booking_create_db' );
function hostel_booking_create_db() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_resv = $wpdb->prefix . 'hostel_booking_resv';
    $table_orders = $wpdb->prefix . 'hostel_booking_orders';
    $table_rooms = $wpdb->prefix . 'hostel_booking_rooms';
    $table_settings = $wpdb->prefix . 'hostel_booking_settings';

    $sql_resv = "CREATE TABLE $table_resv (
    id int(11) NOT NULL AUTO_INCREMENT,
    year int(4) NOT NULL,
    month int(2) NOT NULL,
    dates varchar(255) NOT NULL,
    room varchar(4) NOT NULL,
    booking_ref varchar(20) NOT NULL,
    pending int(1) NOT NULL,
    UNIQUE KEY id (id)
    ) $charset_collate;";

    $sql_orders = "CREATE TABLE $table_orders (
    id int(11) NOT NULL AUTO_INCREMENT,
    datetime datetime NOT NULL,
    name varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    phone varchar(255) NOT NULL,
    price int(12) NOT NULL,
    booking_ref varchar(12) NOT NULL,
    year int(4) NOT NULL,
    month int(2) NOT NULL,
    pending int(1) NOT NULL,
    UNIQUE KEY id (id)
    ) $charset_collate;";
 
    $sql_rooms = "CREATE TABLE $table_rooms (
    room_id int(4) NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    price decimal(10,2) NOT NULL,
    beds int(2) NOT NULL,
    type varchar(20) NOT NULL,
    bathroom varchar(20) NOT NULL,
    display int(1) NOT NULL,
    gender int(1) NOT NULL,
    UNIQUE KEY id (room_id)
    ) $charset_collate;";

    $sql_settings = "CREATE TABLE $table_settings (
    currency_code varchar(3) NOT NULL,
    currency_symbol varchar(6) NOT NULL,
    email varchar(255) NOT NULL,
    message text NOT NULL,
    payment varchar(20) NOT NULL,
    id int(11) NOT NULL AUTO_INCREMENT,
    UNIQUE KEY id (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql_resv ); 
    dbDelta( $sql_orders ); 
    dbDelta( $sql_rooms); 
    dbDelta( $sql_settings ); 

}

?>