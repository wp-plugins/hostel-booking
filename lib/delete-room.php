<?php


add_action( 'wp_ajax_delete_room', 'hbook_delete_room' );

function hbook_delete_room() {

global $wpdb;

$room_id = sanitize_text_field( $_POST['room_id'] );

$delete_room = $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "hostel_booking_rooms WHERE room_id=%d", $room_id));

		if($delete_room > 0){
			echo "Done!";
			} else {
			echo "Please try again.";
		}

		wp_die();
}

?>