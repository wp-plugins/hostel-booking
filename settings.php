
<script type="text/javascript">
window.onload = function() {

jQuery("#email").on("change", function(){
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

  jQuery('#btn-send').click(function (e) {
            var isValid = true;
            jQuery('#email').each(function () {
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
            if (isValid == true && firstValid == true) hbook_saveSettings();

        });

  function hbook_saveSettings() {

    var formData = {
            'currency-code'         : jQuery('select[name=currency-code]').val(),
            'currency-symbol'       : jQuery('select[name=currency-symbol]').val(),
            'message'               : jQuery('textarea[name=message]').val(),
            'email'                 : jQuery('input[name=email]').val()
        }

        jQuery.ajax({                                           // Start Ajax Sending
            url: ajaxurl, 
            type:'POST',
            success: function (data){alert( data.trim() )},
            error:function (response){alert( response.trim() )},
            data:{
                "action" : 'process_settings',
                "formData" : formData
            }
        });
        window.location.reload();

}




} // end of window onload

</script>


<div id="interface-container">

<span id="shortcode-display">Paste this shortcode to display the calendar: <strong>[hostel-booking]</strong></span>

<h1>Settings</h1>

<h4 class="inline-header">Current Settings</h4>
<table id="current-settings">
<tr>
  <th>Currency Code</th><th>Currency Symbol</th><th>Message</th><th>E-Mail</th>
</tr>


<?php 

global $wpdb;

$settings_sql = "SELECT * FROM " . $wpdb->prefix . "hostel_booking_settings";  
$current_settings = $wpdb->get_results($settings_sql, ARRAY_A);

foreach($current_settings as $row) {
echo "<tr><td class='col1'> ". esc_html( $row['currency_code'] ) .  "<td class='col2'>" . esc_html( $row['currency_symbol'] ) . "</td><td class='col1'> ". esc_html( $row['message'] ) . "</td><td class='col2'>" . esc_html( $row['email'] ) . "</td>"; 
}

wp_enqueue_style( 'hbook-settings-style' );

?>

</table>

<h4 class="inline-header">Change Settings</h4>

<!-- <form id="settings-form" class="form" name="settings-form">
 -->      
<table id="form-table" class="settings-page">
<tr>

    <td>
      <label for="currency-code" class="label">Currency Code</label><br />
      <select name="currency-code" type="text" id="currency-code" class="field">
      <p><label>Currency Code:</label>
            <option value='USD'>USD</option>
            <option value='EUR'>EUR</option>
            <option value='GBP'>GBP</option>
            <option value='JPY'>JPY</option>
            <option value='AUD'>AUD</option>
            <option value='CAD'>CAD</option>
            <option value='CHF'>CHF</option>
            <option value='CLP'>CLP</option>
            <option value='CZK'>CZK</option>
            <option value='DKK'>DKK</option>
            <option value='HKD'>HKD</option>
            <option value='HUF'>HUF</option>
            <option value='ILS'>ILS</option>
            <option value='INR'>INR</option>
            <option value='MXN'>MXN</option>
            <option value='NOK'>NOK</option>
            <option value='NZD'>NZD</option>
            <option value='PLN'>PLN</option>
            <option value='SEK'>SEK</option>
            <option value='SGD'>SGD</option>
            <option value='ZAR'>ZAR</option>
      </select>
    </td>

    <td>
      <label for="currency-symbol" class="label">Currency Symbol:</label><br />
      <select name="currency-symbol" type="text" id="currency-symbol" class="field">
            <option value='USD'>$</option>
            <option value='EUR'>&euro;</option>
            <option value='GBP'>&pound;</option>
            <option value='JPY'>&yen;</option>
      </select>
    </td>
</tr>

<tr>

    <td>   
      <label for="message" class="label">Email Message:</label><br />
      <textarea name="message" id="message" cols="35" rows="5" class="message-area"></textarea>
    </td>

    <td>
      <label for="email" class="label">E-Mail</label><br />
      <input type="text" name="email" id="email" class="field" />
    </td> 
</tr>
    
<tr>
  <td>
    <button class="btn" id="btn-send">Save</button>

  </td>
</tr>
</table>

<!--  </form>
 -->
</div><!-- interface container -->