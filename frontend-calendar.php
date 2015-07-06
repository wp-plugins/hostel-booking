<div id="interface-container">

<div id="calendar-table-cont">

    <div id="nav-cont"><!-- nav-cont -->

    <table id="year-nav-table" class="nav-table">
    <tbody>

    <tr id="year-nav-row">
    <td><span class="triangle-left"></span><button onclick="hbook_prevYear()" class="btn-month btn-month-left" id="prev-year"></button></td>


    <td class="month-nav" onclick="flexbookcal_toMonth(0)"><div class="month-nav-div">Jan</div></td><td class="month-nav" onclick="flexbookcal_toMonth(1)"><div class="month-nav-div">Feb</div></td><td class="month-nav" onclick="flexbookcal_toMonth(2)"><div class="month-nav-div">Mar</div></td><td class="month-nav" onclick="flexbookcal_toMonth(3)"><div class="month-nav-div">Apr</div></td><td class="month-nav" onclick="flexbookcal_toMonth(4)"><div class="month-nav-div">May</div></td><td class="month-nav" onclick="flexbookcal_toMonth(5)"><div class="month-nav-div">Jun</div></td><td class="month-nav" onclick="flexbookcal_toMonth(6)"><div class="month-nav-div">Jul</div></td><td class="month-nav" onclick="flexbookcal_toMonth(7)"><div class="month-nav-div">Aug</div></td><td class="month-nav" onclick="flexbookcal_toMonth(8)"><div class="month-nav-div">Sep</div></td><td class="month-nav" onclick="flexbookcal_toMonth(9)"><div class="month-nav-div">Oct</div></td><td class="month-nav" onclick="flexbookcal_toMonth(10)"><div class="month-nav-div">Nov</div></td><td class="month-nav" onclick="flexbookcal_toMonth(11)" id="dec-cell"><div class="month-nav-div">Dec</div></td>
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
<p class="hostel-plugin-title-2">Add New Booking:</p>

<!--  Form: -->
  <form id="hostel-cal-booking-form" class="hostel-cal-form" name="form" method="post" action="process-email.php">
      
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
<td class="cell-price">Price <?php echo esc_html( $currency_code ) . " " . esc_html( $curr_symb );  ?></td>
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

</div><!-- add-booking-cont -->


</div><!-- interface container -->


<?php

wp_enqueue_script( 'hbook-frontend-script' );
wp_enqueue_style( 'hbook-custom-style' );



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

// Get records for the current month and store in array $current_resv
$current_resv = $wpdb->get_results("SELECT year, month, dates, room, booking_ref FROM wp_hostel_booking_resv WHERE year = $current_year AND (month = $current_month OR month = $second_month)", ARRAY_A); 


   $json_response = array();
    
    foreach ($current_resv as $row1) {
        

        $row_array['dates'] = explode(",", $row1['dates']);
        $row_array['room'] = $row1['room'];
        // $row_array['pending'] = $row1['pending'];
        $row_array['booking_ref'] = $row1['booking_ref'];
        $row_array['month'] = $row1['month'];
        $row_array['year'] = $row1['year'];

        //push the values in the array
        array_push($json_response,$row_array);
    }


  $reservations = json_encode($json_response, JSON_NUMERIC_CHECK);

  $current_rooms = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hostel_booking_rooms WHERE display = 1", ARRAY_A); 

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
hbGlob.orders = [];

ajaxurl = <?php echo '"' .  admin_url( 'admin-ajax.php' ) . '"';  ?>;

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

  jQuery.ajax({
     url: ajaxurl,
     type: 'post',
     data: {"action" : 'fetch_bookings', "currentYear" : hbGlob.yearNow, "currentMonth" : hbGlob.monthNow, "secondMonth" : hbGlob.secondMonth},
     success: function(data) {

          window.hbGlob.reservations = JSON.parse(data);

          hbook_addBookings();

     }
  });  
}


function hbook_grabCont() {
<?php
foreach ($rooms as $key => $value) {
  echo 'jQuery(hbook_grabDates("' . $key . '"));' ;
}
?>
};

</script>