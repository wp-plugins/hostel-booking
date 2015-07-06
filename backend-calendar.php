<div id="interface-container">


<h1>Booking Calendar - Overview</h1>

<div id="nav-cont"><!-- nav-cont -->

<table id="year-nav-table" class="nav-table">
<tbody>

<tr id="year-nav-row">
<td><span class="triangle-left"></span><button onclick="hbook_prevYear()" class="btn-month btn-month-left" id="prev-year"></button></td>


<td class="month-nav" onclick="hbook_toMonth(0)">Jan</td><td class="month-nav" onclick="hbook_toMonth(1)">Feb</td><td class="month-nav" onclick="hbook_toMonth(2)">Mar</td><td class="month-nav" onclick="hbook_toMonth(3)">Apr</td><td class="month-nav" onclick="hbook_toMonth(4)">May</td><td class="month-nav" onclick="hbook_toMonth(5)">Jun</td><td class="month-nav" onclick="hbook_toMonth(6)">Jul</td><td class="month-nav" onclick="hbook_toMonth(7)">Aug</td><td class="month-nav" onclick="hbook_toMonth(8)">Sep</td><td class="month-nav" onclick="hbook_toMonth(9)">Oct</td><td class="month-nav" onclick="hbook_toMonth(10)">Nov</td><td class="month-nav" onclick="hbook_toMonth(11)" id="dec-cell">Dec</td>
<td><span class="triangle-right" id="year-right"></span><button onclick="hbook_nextYear()" class="btn-month btn-month-right" id="next-year"></button></td>
</tr><!-- year-nav-row -->
</tbody>
</table>

<table id="month-nav-table"  class="nav-table"><!-- Nav Table -->

<tbody>
<tr id="months-nav-row">
<td><span class="triangle-left"></span><button onclick="hbook_prevMonth()" class="btn-month btn-month-left">Previous Month</button></td>
<td><span class="triangle-right"></span><button onclick="hbook_nextMonth()" class="btn-month btn-month-right">Next Month</button></td>
</tr>
</tbody>

</table><!-- Nav Table -->

</div><!-- nav-cont -->

<div id="calendar-table-cont">

<div id="month-title-cont">
<div id="first-month-title" class="month-title"></div>
<div id="second-month-title" class="month-title"></div>
</div><!-- month title cont -->

<table id="calendar-table">
<tr id="dates-row"><td id="dates-left"></td></tr>


</tbody>

</table>

</div><!-- table-cont -->


<table id="legend-table">
<tr id="legend-row">
<td class="disabled"></td>
<td class="legend-cell">&nbsp; = Occupied </td>
<td class="legend-cell" id"hold-ctrl">Hold Ctrl for multiple selections in the same room</td>
</tr>
</table>


<div id="addbooking-cont">
<h3>Add New Booking:</h3>

<!--  Form: -->
  <form id="hostel-cal-booking-form" class="hostel-cal-form" name="form">
      
    <p>
      <label for="name" class="label">Name</label>
      <input type="text" name="name" id="name" class="field" />
    </p>

    <p> 
      <label for="email" class="label">E-Mail</label>
      <input type="text" name="email" id="email" class="field" />
    </p>

    <p>
      <label for="phone" class="label">Phone</label>
      <input name="phone" type="text" id="phone" class="field" />
    </p>

    <p>
      <label for="name" class="label">Gender</label>
       <select name="gender" class="field" id="gender">
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="mixed">Mixed</option>
        </select> 
    </p>

    <p>
      <label for="message" class="label">Message - Optional</label><br />
      <textarea name="message" id="message" cols="35" rows="5" class="message-area"></textarea>
    </p>
  </form>
<!--  /Form -->


<?php

global $wpdb;

$currency_settings = $wpdb->get_results("SELECT currency_code, currency_symbol FROM " . $wpdb->prefix . "hostel_booking_settings", ARRAY_A); 

foreach($currency_settings as $row) {
  $currency_symbol = $row['currency_symbol'];
  $currency_code = $row['currency_code'];
}

if ($currency_symbol == "JPY") {
    $curr_symb =  "&yen;";
} elseif ($currency_symbol == "EUR") {
    $curr_symb =  "&euro;";
} elseif ($currency_symbol == "GBP") {
    $curr_symb =  "&pound;";
} else {
    $curr_symb =  "$";
}

?>

<!--  Summary: -->
<div id="div-summary">

<table id="table-summary">
<tbody id="tbody-summary">
<tr id="row-summary-title">
<td class="cell-room">Room</td>
<td class="cell-beds">Beds</td>
<td class="cell-nights">Nights</td>
<td class="cell-price">Price <?php echo esc_html($currency_code) . " " . esc_html($curr_symb);  ?></td>
<td class="cell-delete"></td>
</tr>

</tbody>

<tfoot>
<tr>
  <td><button onclick="hbook_addRoomTotals()" class="btn-total">Total</button></td>
  <td></td><td></td>
  <td><span id="grandtotal" class="totalprice"></span></td>
</tr>
</tfoot>

</table>
<button class="btn btn-booking" id="btn-send-booking">Send Booking</button>
</div><!--  /Summary -->

<h3>Pending Orders:</h3>
<table id="pending_orders">
<tr>
  <th>Name</th><th id="email-head">Email</th><th>Phone</th><th>Price</th><th>Booking ref</th><th class="button-head"></td><th class="button-head"></td>
</tr>


<?php 

wp_enqueue_script( 'hbook-backend-script');
wp_enqueue_style( 'hbook-backend-style' );

$pending_bookings = $wpdb->get_results("SELECT * FROM wp_hostel_booking_orders WHERE pending = 1", ARRAY_A);

foreach ($pending_bookings as $row) {
echo "<tr><td class='col1'> ". esc_html( $row['name'] ) .  "<td class='col2'>" . esc_html( $row['email'] ) . "</td><td class='col1'> ". esc_html( $row['phone'] ) . "</td><td class='col2'>" . esc_html( $row['price'] ) . "</td><td class='col1'>" . esc_html( $row['booking_ref'] ) . "</td>"; 
echo '<td><button onclick="hbook_confirmBooking(this, '; echo "'confirm'"; echo ');" class="btn btn-delete" >Confirm</button></td>';
echo '<td><button onclick="hbook_confirmBooking(this, '; echo "'delete'"; echo ');" class="btn btn-delete" >Delete</button></td>';
}


?>

</table>


</div><!-- add-booking-cont -->

</div><!-- interface container -->

<?php

// Get the current month and year:
$current_year = date("Y");
$current_month = date("m")-1;

if ($current_month == 11) {
    $second_month = 0;
    $second_year = $current_year + 1;
  } else {
    $second_month = $current_month + 1;
    $second_year = $current_year;
  };



if($second_month == 0) {
    $resv_sql = "SELECT year, month, dates, room, pending, booking_ref  FROM " . $wpdb->prefix . "hostel_booking_resv WHERE (year = $current_year AND month = $current_month) OR (year = $second_year AND month = $second_month)";
} else {
    $resv_sql = "SELECT year, month, dates, room, pending, booking_ref  FROM " . $wpdb->prefix . "hostel_booking_resv WHERE year = $current_year AND (month = $current_month OR month = $second_month)";
}

// $resv_sql = "SELECT year, month, dates, room, pending, booking_ref  FROM " . $wpdb->prefix . "hostel_booking_resv WHERE (year = $current_year OR $second_year) AND (month = $current_month OR month = $second_month)";
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

if($second_month == 0) {
    $orders_sql = "SELECT * FROM " . $wpdb->prefix . "hostel_booking_orders WHERE (year = $current_year AND month = $current_month) OR (year = $second_year AND month = $second_month)";    
} else {
    $orders_sql = "SELECT * FROM " . $wpdb->prefix . "hostel_booking_orders WHERE year = $current_year AND (month = $current_month OR month = $second_month)";    
}

// $orders_sql = "SELECT * FROM " . $wpdb->prefix . "hostel_booking_orders WHERE year = $current_year AND (month = $current_month OR month = $second_month)";    
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


    $current_rooms = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hostel_booking_rooms", ARRAY_A); 


$rooms = array();
    
    foreach ($current_rooms as $row1) {

        $rooms['r' . $row1["room_id"]] = array("name" => $row1['name'], "beds" => $row1['beds'], "price" => $row1['price'], "type" => $row1['type'], "bathroom" => $row1['bathroom']);
    }

    $rooms_json = json_encode($rooms, JSON_NUMERIC_CHECK);

?>

<script type="text/javascript">
var hbGlob = {};
hbGlob.reservations = <?php echo $reservations; ?>;
hbGlob.rooms = <?php echo $rooms_json; ?>;
hbGlob.orders = <?php echo $orders_json; ?>;

var pluginsURL = <?php echo '"' .  plugins_url() . '"';  ?>;


function hbook_nextYear() {
var today = window.hbGlob.today;
    var year = today.getFullYear() + 1;
    var month = 0;
    hbook_toMonth(month, year);
 };

function hbook_prevYear() {
var today = window.hbGlob.today;
    var year = today.getFullYear() - 1;
    var month = 11;
    hbook_toMonth(month, year);
 };

function hbook_nextMonth() {
var today = window.hbGlob.today;
  if (today.getMonth() == 11) {
    var year = (today.getFullYear() + 1);
    var month = 0;
    hbook_toMonth(month, year);
    } else {
        var month = (today.getMonth() + 1);
        hbook_toMonth(month);
    }
};

hbGlob.monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

 function hbook_prevMonth() {
var today = window.hbGlob.today;
  if (today.getMonth() == 0) {
    var year = (today.getFullYear() - 1);
    var month = 11;    // , 0, 1);
    hbook_toMonth(month, year);
    } else {
        var month = (today.getMonth() - 1);
        hbook_toMonth(month);
    }
 };

function hbook_toMonth(month, year) {
  if (!year) year = hbGlob.today.getFullYear();
  var today = new Date(year, month, 1);
    
  window.hbGlob.today = today;

  hbook_defineDatesVars();

  document.getElementById("calendar-table").innerHTML = '<thead><tr id="dates-row"><td id="dates-left"></td></tr></thead>';


  hbook_createDaysArray();
  hbook_createDatesRow();
  hbook_createRooms();
  hbook_grabCont();

  hbook_fetchBookings();
}


function hbook_grabCont() {
<?php
foreach ($rooms as $key => $value) {
  echo 'jQuery(hbook_grabDates("' . $key . '"));' ;
}
?>
};
</script>