<?php

add_action( 'wp_ajax_process_settings', 'hbook_process_settings' );

function hbook_process_settings() {

$code = sanitize_text_field( $_POST['formData']['currency-code'] );

$symbol = sanitize_text_field( $_POST['formData']['currency-symbol'] );

$message = sanitize_text_field( $_POST['formData']['message'] );

$email = sanitize_email( $_POST['formData']['email'] );

global $wpdb;


		$delete_settings = $wpdb->query("DELETE FROM " . $wpdb->prefix . "hostel_booking_settings", $delete);

		$wpdb->insert('wp_hostel_booking_settings',
			array('currency_code' => $code, 'currency_symbol' => $symbol, 'email' => $email, 'message' => $message),
			array('%s', '%s', '%s', '%s'));

		
		$settings_exists = "SELECT * FROM " . $wpdb->prefix . "hostel_booking_settings WHERE currency_code = '$code' AND currency_symbol = '$symbol' AND email = '$email' AND message = '$message'";	
		$confirm = $wpdb->get_results($settings_exists);



		if($confirm) {
			echo "Done! Your settings have been saved.";
			} else {
						echo "There was a problem saving the settings, please try again.";
		}

		wp_die();
}

?>