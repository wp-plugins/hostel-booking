
<script type="text/javascript">


var pluginsURL = <?php echo '"' .  plugins_url() . '"';  ?>;

window.onload = function() {

jQuery("#price").on("keyup", function(){
    var valid = /^\d{0,10}(\.\d{0,2})?$/.test(this.value),
        val = this.value;
    
    if(!valid){
        console.log("Invalid input!");
        this.value = val.substring(0, val.length - 1);
    }
});

  jQuery('#btn-create').click(function (e) {
            var isValid = true;
            jQuery('#name, #price').each(function () {
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
            if (isValid == true) { 
              addRoom();
            }
  });

}



function addRoom() {

var formData = {
            'name'              : jQuery('input[name=name]').val(),
            'price'             : jQuery('input[name=price]').val(),
            'beds'              : jQuery('select[name=beds]').val(),
            'gender'            : jQuery('select[name=gender]').val(),
            'type'              : jQuery('select[name=type]').val(),
            'bathroom'          : jQuery('select[name=bathroom]').val(),
            'display'           : jQuery('select[name=display]').val(),
            'nonce'             : jQuery('input[name=create_rooms_nonce]').val()
}

        jQuery.ajax({                                           // Start Ajax Sending
            url: ajaxurl , 
            type:'POST',
            success: function (data){alert(data.trim())},
            error:function (response){alert("There was an error please try again.")},
            data:{
                'action' : 'process_room',
                'formData' : formData
            }
        });
        window.location.reload();

};


function validateEdit() {

jQuery("#edit-price").on("keyup", function(){
    var valid = /^\d{0,10}(\.\d{0,2})?$/.test(this.value),
        val = this.value;
    
    if(!valid){
        console.log("Invalid input!");
        this.value = val.substring(0, val.length - 1);
    }
});

  jQuery('#btn-save').click(function (e) {
            var isValid = true;
            jQuery('#edit-name, #edit-price').each(function () {
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
            if (isValid == true) {
              var currentSave = this;
              updateRoom(currentSave);
            }
  });
}

function editRoom(e) {

  var cells = e.parentNode.parentNode.getElementsByTagName("td");

  var cellVals = [];  
  for(i = 1; i < cells.length-2; i++) {
    cellVals[i] = cells[i].innerHTML;
  }
    cells[1].innerHTML = '<input type="text" name="edit-name" id="edit-name" class="field" value="' + cellVals[1] + '" />';
    cells[2].innerHTML = '<input type="text" name="edit-price" id="edit-price" class="field" value="' + cellVals[2] + '" />';
    cells[3].innerHTML = '<select name="edit-beds" type="text" id="edit-beds" class="field" value="' + cellVals[3] + '">\n\
                            <option value="1">1</option>\n\
                            <option value="2">2</option>\n\
                            <option value="3">3</option>\n\
                            <option value="4">4</option>\n\
                            <option value="5">5</option>\n\
                            <option value="6">6</option>\n\
                            <option value="7">7</option>\n\
                            <option value="8">8</option>\n\
                            <option value="9">9</option>\n\
                            <option value="10">10</option>\n\
                            <option value="11">11</option>\n\
                            <option value="12">12</option>\n\
                            <option value="13">13</option>\n\
                            <option value="14">14</option>\n\
                            <option value="15">15</option>\n\
                            <option value="16">16</option>\n\
                            <option value="17">17</option>\n\
                            <option value="18">18</option>\n\
                            <option value="19">19</option>\n\
                            <option value="20">20</option>\n\
                          </select>';
                            jQuery('select[name="edit-beds"] option[value="' + cellVals[3] + '"]').attr("selected","selected");

    cells[4].innerHTML = '<select name="edit-type" class="field">\n\
                              <option value="Dorm">Dorm</option>\n\
                              <option value="Private Room">Private Room</option>\n\
                            </select>';
                            jQuery('select[name="edit-type"] option[value="' + cellVals[4] + '"]').attr("selected","selected");


    cells[5].innerHTML = '<select name="edit-bathroom" class="field">\n\
                              <option value="Shared">Shared</option>\n\
                              <option value="Private">Private</option>\n\
                            </select>';
                            jQuery('select[name="edit-bathroom"] option[value="' + cellVals[5] + '"]').attr("selected","selected");


    cells[6].innerHTML = '<select name="edit-display" class="field">\n\
                              <option value="1">Yes</option>\n\
                              <option value="0">No</option>\n\
                            </select>';

                            if(cellVals[6] == "Yes") {cellVal6 = 1} else {cellVal6 = 0};

                            jQuery('select[name="edit-display"] option[value="' + cellVal6 + '"]').attr("selected","selected");


    cells[7].innerHTML = '<select name="edit-gender" class="field">\n\
                              <option value="1">Male</option>\n\
                              <option value="2">Female</option>\n\
                              <option value="3">Mixed</option>\n\
                            </select> ';

                            if(cellVals[7] == "Male") {cellVal7 = 1} else if (cellVals[7] == "Female") {cellVal7 = 2} else {cellVal7 = 3};

                            jQuery('select[name="edit-gender"] option[value="' + cellVal7 + '"]').attr("selected","selected");

    e.parentNode.innerHTML = '<button id="btn-save" class="btn btn-save">Save</button>';
    validateEdit();
    jQuery(function(){
      jQuery(".btn-delete").attr("disabled", true);
    });
}


function updateRoom(e) {

  var cells = e.parentNode.parentNode.getElementsByTagName("td");  
  var roomId = cells[0].innerHTML;


  var cellVals = [];  
  for(i = 1; i < cells.length-2; i++) {
    cellVals[i] = cells[i].firstChild.value;
    cells[i].innerHTML = cellVals[i];   
    }


        jQuery.ajax({
           url: ajaxurl,
           type: 'post',
           data: {"new_values" : cellVals, "room_id" : roomId, "action" : 'update_room'},
           success: function(data) {
                var currentRooms = document.getElementById("current-rooms");
                currentRooms.innerHTML += data;
           }
        });


    e.parentNode.innerHTML = '<button class="btn btn-delete" onclick="editRoom(this);">Edit</button>';
    jQuery(function(){
      jQuery(".btn-delete").removeAttr('disabled');
    });
}



</script>


<div id="interface-container">


<h1>Create New Room:</h1>

      
<table id="form-table" class="view-create-rooms">
<tr>
  <td>
      <label for="name" class="label">Name</label><br />
      <input type="text" name="name" id="name" class="field" />
  </td>

  <td>
      <label for="price" class="label">Price</label><br />
      <input id="price" maxlength="13" type="text" name="price" class="field" />
    </td>

    <td>
      <label for="beds" class="label">No. of Beds</label><br />
      <select name="beds" type="text" id="beds" class="field">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
      </select>
    </td>

    <td>
      <label for="gender" class="label">Gender</label><br />
       <select name="gender" class="field">
          <option value="1">Male</option>
          <option value="2">Female</option>
          <option value="3">Mixed</option>
        </select> 
    </td>
</tr>


<tr>
  <td>
      <label for="name" class="label">Type</label><br />
       <select name="type" class="field">
          <option value="Dorm">Dorm</option>
          <option value="Private Room">Private Room</option>
        </select> 
    </td>

    <td>
      <label for="bathroom" class="label">Bathroom</label><br />
       <select name="bathroom" class="field">
          <option value="Shared">Shared</option>
          <option value="Private">Private</option>
        </select> 
    </td>

    <td>
      <label for="display" class="label">Display</label><br />
       <select name="display" class="field">
          <option value="1">Yes</option>
          <option value="0">No</option>
        </select> 
    </td>

    <td>
    <?php wp_nonce_field('create_rooms_nonce', 'create_rooms_nonce') ?>
    <button class="btn" id="btn-create">Create</button>
  </td>
</tr>
</table>




<table id="current-rooms">
<tr>
  <th id="room-id">ID</th><th>Name</th><th>Price</th><th>Beds</th><th>Type</th><th>Bathroom</th><th>Display</th><th>Gender</th><th class="delete-head"></th><th class="delete-head"></th>
</tr>


<?php 

global $wpdb;

$current_rooms = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hostel_booking_rooms", ARRAY_A);


foreach($current_rooms as $row) {
echo "<tr id='r" . esc_attr( $row['room_id'] ) . "'><td class='col1'> ". esc_html( $row['room_id'] ) .  "<td class='col2'>" . esc_html( $row['name'] ) . "</td><td class='col1'> ". esc_html( $row['price'] ) . "</td><td class='col2'>" . esc_html( $row['beds'] ) . "</td><td class='col1'>" . esc_html( $row['type'] ) . "</td><td class='col2'>" . esc_html( $row['bathroom'] ) . "</td><td class='col1'>"; 
if($row['display'] == 1) {echo "Yes";} else {echo "No";}  
echo "</td><td class='col2'>";
if($row['gender'] == 1) echo "Male"; if($row['gender'] == 2) echo "Female"; if($row['gender'] == 3) echo "Mixed"; 
echo "</td><td><button onclick='editRoom(this);' class='btn btn-delete'>Edit</button></td>";
echo "<td><button onclick='deleteRoom(this);' class='btn btn-delete'>Delete</button></td></tr>";
}

wp_enqueue_style( 'hbook-settings-style' );

?>

</table>

<div id="print-out"></div>

<script type="text/javascript">

function deleteRoom(room) {
  var roomRow = room.parentNode.parentNode.id;
  var roomId = roomRow.slice(1);
  if (confirm('Are you sure you want to delete room ' + roomId + '?')) {
    
    jQuery.ajax({
     url: ajaxurl,
     type: 'post',
     data: {"room_id" : roomId, "action" : 'delete_room'},
     success: function(data) {
          }
    });


  if ( document.contains(room) ) {
                var row = document.getElementById(roomRow);
                row.parentNode.removeChild(row);
        }

      } else {
          // Do nothing!
      }
 
}

</script>

</div><!-- interface container -->