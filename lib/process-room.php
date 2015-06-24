<?php

add_action( 'wp_ajax_process_room', 'hbook_process_room' );

function hbook_process_room() {

  $name = sanitize_text_field( $_POST['formData']['name'] );

  $price = floatval( $_POST['formData']['price'] );

  $beds = intval( $_POST['formData']['beds'] );

  $gender = intval( $_POST['formData']['gender'] );

  $type = sanitize_text_field( $_POST['formData']['type'] );

  $bathroom = sanitize_text_field( $_POST['formData']['bathroom'] );

  $display = intval( $_POST['formData']['display'] );

  $nonce = $_POST['formData']['nonce'];

if ( ! wp_verify_nonce( $nonce, 'create_rooms_nonce' ) ) {
        die ( 'Wrong nonce!');
      }

  global $wpdb;

    $wpdb->insert('wp_hostel_booking_rooms',
          array('name' => $name, 'price' => $price, 'beds' => $beds, 'type' => $type, 'bathroom' => $bathroom, 'display' => $display, 'gender' => $gender),
          array('%s', '%f', '%d', '%s', '%s', '%d', '%d'));

    $room_exists = "SELECT * FROM " . $wpdb->prefix . "hostel_booking_rooms WHERE name = '$name' AND price = '$price' AND beds = '$beds' AND type = '$type' AND bathroom = '$bathroom' AND display = '$display' AND gender = '$gender'";  
    $confirm = $wpdb->get_results($room_exists);

    // echo "Confirm: ";
    // var_dump($confirm);

    if($confirm){
      echo 'Done! ' . $name. ' has been created.';
      } else {
      echo 'There was a problem creating ' . $name . ', please try again.';
    }

  wp_die(); // this is required to terminate immediately and return a proper response

}

?>