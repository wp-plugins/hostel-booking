
<script type="text/javascript">


function validateEdit() {

var firstValid = true;

jQuery("#edit-field1").on("change", function(){
    var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    var valid = emailReg.test(this.value);
    
    if(!valid){
        alert("Invalid Email!");
        firstValid = false;
        jQuery(this).css({
                        "border": "1px solid red",
                        "background": "#FFCECE"
                    });
    } else {
            firstValid = true;
                    jQuery(this).css({
                        "border": "",
                        "background": ""
                    });
                }
});

jQuery("#edit-field3").on("keyup", function(){
    var valid = /^\d{0,10}(\.\d{0,2})?$/.test(this.value),
        val = this.value;
    
    if(!valid){
        this.value = val.substring(0, val.length - 1);
    }
});

  jQuery('#btn-save').click(function (e) {
            var isValid = true;
            jQuery('#edit-field0, #edit-field2, #edit-field3').each(function () {
                if (jQuery.trim(jQuery(this).val()) == '') {
                    isValid = false;
                    jQuery(this).css({
                        "border": "1px solid red",
                        "background": "#FFCECE"
                    });
                }
                else {
                    jQuery(this).css({
                        "border": "",
                        "background": ""
                    });
                }
            });

            if(firstValid == false){
              alert("Invalid Email!");
            }

            if (isValid == true && firstValid == true) {
              var currentSave = this;
              updateOrder(currentSave);
            }
  });

}


var pluginsURL = <?php echo '"' .  plugins_url() . '"';  ?>;

function searchOrders() {

  var searchQuery = document.getElementById("search-value").value;
  jQuery.ajax({
     url: ajaxurl,
     type: 'post',
     data: {"search_query" : searchQuery, "action" : 'search_orders'},
     success: function(data) {
          // Do something with data that came back. 
          var ordersTable = document.getElementById("table-all-orders");   
          ordersTable.innerHTML = data; 
     }
  });
}



function editOrder(e) {

  var cells = e.parentNode.parentNode.getElementsByTagName("td");

  var cellVals = [];  
  for(i = 0; i < cells.length-3; i++) {

    cellVals[i] = cells[i].innerHTML;
    cells[i].innerHTML = '<input type="text" name="e" id="edit-field' + i + '" class="field" value="' + cellVals[i] + '" />';

    }

    e.parentNode.innerHTML = '<button class="btn" id="btn-save">Save</button>';
    validateEdit();
    jQuery(function(){
      jQuery(".btn-delete").attr("disabled", true);
    });
}


function updateOrder(e) {

  var cells = e.parentNode.parentNode.getElementsByTagName("td");  
  var bookingRef = cells[4].innerHTML;


  var cellVals = [];  
  for(i = 0; i < cells.length-3; i++) {
    cellVals[i] = cells[i].firstChild.value;
    cells[i].innerHTML = cellVals[i];

  }


        jQuery.ajax({
           url: ajaxurl,
           type: 'post',
           data: {"new_values" : cellVals, "booking_ref" : bookingRef, "action" : 'update_order'},
           success: function(data) {

           }
        });


    e.parentNode.innerHTML = '<button class="btn btn-delete" onclick="editOrder(this);">Edit</button>';
    jQuery(function(){
      jQuery(".btn-delete").removeAttr('disabled');
    });
}

function confirmDeleteOrder(e, condel) {
  var cells = e.parentNode.parentNode.getElementsByTagName("td");  
  var bookingRef = cells[4].innerHTML;

  if (confirm('Are you sure you want to ' + condel + ' this order ?')) {
    
    jQuery.ajax({
     url: ajaxurl,
     type: 'post',
     data: {"booking_ref" : bookingRef, "action" : 'delete_order'},
     success: function(data) {

          }
    });

    var orderRow = e.parentNode.parentNode.id;

    var row = document.getElementById(orderRow);
    row.parentNode.removeChild(row);

  }

}



</script>

<div id="interface-container">

<h1>Orders</h1>

<h4>Pending Orders:</h4>
<table class="orders-table">
<tr>
  <th>Name</th><th class="email-head">Email</th><th>Phone</th><th>Price</th><th>Booking ref</th><th class="button-head"></td><th class="button-head"></td>
</tr>


<?php 

global $wpdb;

$pending_orders = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hostel_booking_orders WHERE pending = 1", ARRAY_A );

foreach($pending_orders as $row) {
echo "<tr id='o" . esc_attr( $row['booking_ref'] ) . "'><td class='col1'> ". esc_html( $row['name'] ) .  "<td class='col2'>" . esc_html( $row['email'] ) . "</td><td class='col1'> ". esc_html( $row['phone'] ) . "</td><td class='col2'>" . esc_html( $row['price'] ) . "</td><td class='col1'>" . esc_html( $row['booking_ref'] ) . "</td>"; 
echo '<td><button onclick="confirmDeleteOrder(this, '; echo "'confirm'"; echo ');" class="btn btn-delete" >Confirm</button></td>';
echo '<td><button onclick="confirmDeleteOrder(this, '; echo "'delete'"; echo ');" class="btn btn-delete" >Delete</button></td>';
}

?>

</table>


<div id="filters"> 
<h4 class="inline-header">Confirmed Orders:</h4>

<!-- <div id="search-cont">  
      <label for="name" class="label">Search</label><br />
      <input type="text" name="search-value" id="search-value" class="field" />
    <button onclick="searchOrders();" class="btn" id="btn-go">Go</button>
  </div> -->
</div><!-- filters -->

<table class="orders-table" id="table-all-orders">
<tr>
  <th>Name</th><th class="email-head">Email</th><th>Phone</th><th>Price</th><th>Booking ref</th><th class="button-head"></td><th class="button-head"></td>
</tr>


<?php 

$current_orders = $wpdb->get_results("SELECT * FROM wp_hostel_booking_orders WHERE year = $current_year AND (month = $current_month OR month = $second_month)", ARRAY_A); 

$orders = array();
    
    foreach($current_orders as $row1) {

        $orders_array[$row1['booking_ref']] = array("name" => $row1['name'], "email" => $row1['email'], "phone" => $row1['phone'], "booking_ref" => $row1['booking_ref']);
        //push the values in the array
        array_push($orders,$orders_array);
    }


$all_orders = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hostel_booking_orders WHERE pending = 0 ORDER BY id DESC", ARRAY_A);


foreach($all_orders as $row) {
echo "<tr id='r" . esc_attr( $row['booking_ref'] ) . "'><td class='col1'> ". esc_html( $row['name'] ) .  "<td class='col2'>" . esc_html( $row['email'] ) . "</td><td class='col1'> ". esc_html( $row['phone'] ) . "</td><td class='col2'>" . esc_html( $row['price'] ) . "</td><td class='col1'>" . esc_html( $row['booking_ref'] ) . "</td>"; 
echo '<td><button onclick="editOrder(this);" class="btn btn-delete">Edit</button></td>';
echo '<td><button onclick="confirmDeleteOrder(this, '; echo "'delete'"; echo ');" class="btn btn-delete">Delete</button></td>';
}

wp_enqueue_style( 'hbook-settings-style' );

?>

</table>


</div><!-- interface container -->