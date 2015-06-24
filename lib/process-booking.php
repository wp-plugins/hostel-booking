<?php 

add_action( 'wp_ajax_process_booking', 'hbook_process_booking' );
add_action( 'wp_ajax_nopriv_process_booking', 'hbook_process_booking' );

function hbook_process_booking() {

	$pending_passed = $_POST["pending"];

	if($pending_passed == "frn8945KdC") {
		$pending = 1;
	} elseif ($pending_passed == "bck7384FvW") {
		$pending = 0;
	} else { exit("We couldn't process your booking."); }


    $bookings = $_POST["allBookings"];

    $price = floatval( $_POST["price"] );
    $year = intval( $_POST["currentYear"] );
    $monthNow = intval( $_POST["currentMonth"] );


$name = sanitize_text_field( $_POST['formData']['name'] );

$email = sanitize_email( $_POST['formData']['email'] );

$phone = intval( $_POST['formData']['phone'] );

$gender = intval( $_POST['formData']['gender'] );

$message = sanitize_text_field( $_POST['formData']['message'] );


global $wpdb;

$dates_beds = array();

foreach ($bookings as $month => $rooms) {
	$dates_beds[$month] = array();
	$current_month = $bookings[$month];

	foreach ($current_month as $room => $ids) {
		$current_room = $current_month[$room];
		$dates_beds[$month][$room] = array();

		foreach ($current_room as $id) {
			$date = substr($id, strrpos($id, '-') + 1);
			if (array_key_exists($date, $dates_beds[$month][$room])) {
				$dates_beds[$month][$room][$date] += 1;
				} else {
					$dates_beds[$month][$room][$date] = 1;
				}

		}
	}
}


$roomStrings = array();
foreach($dates_beds as $month => $rooms) {
	$roomStrings[$month] = array();
	$current_month = $dates_beds[$month];
	echo "Month:  " . $month . "  Rooms: " . $rooms . "<br />";
	echo "Current Month: ";
	var_dump($current_month);

	foreach($current_month as $room => $pairs) {
		$roomStrings[$month][$room] = '';
		$current_room = $current_month[$room];

		foreach($current_room as $date => $beds) {
			if (next($current_room)) {
				$roomStrings[$month][$room] .= "{$date}:{$beds},";
			} else {
				$roomStrings[$month][$room] .= "{$date}:{$beds}";
			}
		}
	}
}


	function randomString($length = 3) {
		 $str = "";
		 $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
		 $max = count($characters) - 1;
		 for ($i = 0; $i < $length; $i++) {
			  $rand = mt_rand(0, $max);
			  $str .= $characters[$rand];
		 	}
		 return $str;
		}


	do {
		$rdmStr = randomString();
		$booking_ref = substr($year, 2,3) . $month . $rdmStr . substr($name, 0,3);

		$ref_sql = "SELECT * FROM " . $wpdb->prefix . "hostel_booking_resv WHERE booking_ref = '$booking_ref'";
		$ref_result = $wpdb->get_results($ref_sql);

		echo 'Ref result is: ' . var_dump($ref_result);
		} while ($ref_result == "bob");


foreach($roomStrings as $monthn => $room) {
	$current_month = $roomStrings[$monthn];
	foreach ($current_month as $room => $dates) {

		$wpdb->insert( $wpdb->prefix . 'hostel_booking_resv',
			array('year' => $year, 'month' => $monthn, 'dates' => $dates, 'room' => $room, 'booking_ref' => $booking_ref, 'pending' => $pending),
			array('%d', '%d', '%s', '%s', '%s', '%d'));
	}
}

		$wpdb->insert( $wpdb->prefix . 'hostel_booking_orders',
			array('name' => $name, 'email' => $email, 'phone' => $phone, 'price' => $price, 'booking_ref' => $booking_ref, 'year' => $year, 'month' => $monthNow, 'pending' => $pending ),
			array('%s', '%s', '%s', '%d', '%s', '%d', '%d', '%d'));

}

?>