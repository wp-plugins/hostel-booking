<?php

add_action( 'wp_ajax_search_orders', 'hbook_search_orders' );

function hbook_search_orders() {

echo "<script>alert('search orders activated');</script>";

global $wpdb;

$search_query = $_POST["search_query"];


echo '<tr><th>Name</th><th class="email-head">Email</th><th>Phone</th><th>Price</th><th>Booking ref</th><th class="button-head"></td><th class="button-head"></td></tr>';

// $query = "SELECT * FROM wp_hostel_booking_orders WHERE name LIKE '%" . $search_query . "%' OR email LIKE '%" . $search_query . "%' OR phone LIKE '%" . $search_query . "%' OR price LIKE '%" . $search_query . "%' OR booking_ref LIKE '%" . $search_query . "%'";
// $all_orders = $wpdb->get_results($query, ARRAY_A);
//     if(!$all_orders) {
//       die("Database query failed: " . mysql_error());
//       }



// First, escape the link for use in a LIKE statement.
$query = $wpdb->esc_like( $search_query );

// Add wildcards, since we are searching within comment text.
$query = '%' . $query . '%';

// Create a SQL statement with placeholders for the string input.
$sql = 	"
	SELECT *
	FROM wp_hostel_booking_orders 
	WHERE (name LIKE %s OR email LIKE %s OR phone LIKE %s OR price LIKE %s OR booking_ref LIKE %s)
	";

// Prepare the SQL statement so the string input gets escaped for security.
$sql = $wpdb->prepare( $sql, $query );

// Search local spam for comments or author url containing this link.
$all_orders = $wpdb->get_results( $sql );


foreach($all_orders as $row) {
echo "<tr><td class='col1'> ". $row[2] .  "<td class='col2'>" . $row[3] . "</td><td class='col1'> ". $row[4] . "</td><td class='col2'>" . $row[5] . "</td><td class='col1'>" . $row[6] . "</td>"; 
echo '<td><button onclick="confirmBooking(this, '; echo "'remove'"; echo ');" class="btn" id="btn-delete">Edit</button></td>';
echo '<td><button onclick="confirmBooking(this, '; echo "'confirm'"; echo ');" class="btn" id="btn-delete">Remove</button></td>';
}

echo "</table>";

// "SELECT * FROM wp_hostel_booking_orders WHERE name LIKE $search_query OR email LIKE $search_query OR phone LIKE $search_query OR price LIKE $search_query OR booking_ref LIKE $search_query"

}
?>