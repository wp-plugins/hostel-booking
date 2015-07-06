window.onload = function() {
	hbook_createDaysArray();
	hbook_createDatesRow();
	hbook_createRooms();
	hbook_addBookings();

	hbook_grabCont();

	hbook_formValidation();

}; /* end of window.onload */

hbGlob.todayNow = new Date();
hbGlob.today = new Date();

function hbook_daysInMonth(month, year) {
	return new Date(year, month + 1, 0).getDate();
}

function hbook_defineDatesVars() {

	hbGlob.yearNow = hbGlob.today.getFullYear();
	hbGlob.monthNow = hbGlob.today.getMonth();

	hbGlob.firstDay = new Date(hbGlob.yearNow, hbGlob.monthNow, 1);
	hbGlob.firstDayValue = hbGlob.firstDay.getDay();


	hbGlob.daysThisMonth = hbook_daysInMonth(hbGlob.monthNow, hbGlob.yearNow);

	// daysNextMonth monthNext
	if (hbGlob.monthNow == 11) {
		hbGlob.daysNextMonth = hbook_daysInMonth(0, hbGlob.yearNow + 1);
		hbGlob.secondMonth = 0;
		hbGlob.secondYear = hbGlob.yearNow + 1;
	} else {
		hbGlob.daysNextMonth = hbook_daysInMonth(hbGlob.monthNow + 1, hbGlob.yearNow);
		hbGlob.secondMonth = hbGlob.monthNow + 1;
		hbGlob.secondYear = hbGlob.yearNow + 1;
	};

	hbGlob.allBookings = {};
	hbGlob.allBookings[hbGlob.monthNow] = {};
	hbGlob.allBookings[hbGlob.secondMonth] = {};


	hbGlob.nameOfDays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
	hbGlob.monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];


	hbGlob.todayName = (hbGlob.nameOfDays[hbGlob.today.getDay()]);

}

hbook_defineDatesVars();

function hbook_createDaysArray() {
	hbGlob.daysArray = [];
	var first = hbGlob.firstDayValue;
	for (var i = 1; i <= hbGlob.daysThisMonth; i++) {
		hbGlob.daysArray.push([i, first]);
		first += 1;
		if (first == 7) first = 0;
	};
	for (var i = 1; i <= hbGlob.daysNextMonth + hbGlob.daysNextMonth; i++) {
		hbGlob.daysArray.push([i, first]);
		first += 1;
		if (first == 7) first = 0;
	};
};

function hbook_createDaysArray_PREVIOUS() {
	hbGlob.daysArray = [];
	var first = hbGlob.firstDayValue;
	for (var i = 1; i <= hbGlob.daysThisMonth; i++) {
		hbGlob.daysArray.push([i, first]);
		first += 1;
		if (first == 7) first = 0;
	};

};


function hbook_createDatesRow() {

	var widthOfTableCells = 31;

	var theWidth = (hbGlob.daysThisMonth * widthOfTableCells);
	jQuery("#nav-cont").css({
		width: theWidth + "px"
	});

	var tableContWidth = ((hbGlob.daysThisMonth * widthOfTableCells) + 50);
	jQuery("#calendar-table-cont").css({
		width: tableContWidth + "px"
	});


	var secondMonthWidth = (hbGlob.daysNextMonth * widthOfTableCells);
	var monthTitleWidth = tableContWidth + secondMonthWidth;


	var displayPrevYear = document.getElementById("prev-year");
	displayPrevYear.innerHTML = hbGlob.today.getFullYear() - 1;

	var displayNextYear = document.getElementById("next-year");
	displayNextYear.innerHTML = hbGlob.today.getFullYear() + 1;

	jQuery("#month-title-cont").css({
		width: monthTitleWidth + "px"
	});

	var firstMonthTitle = document.getElementById("first-month-title");
	jQuery(firstMonthTitle).css({
		width: theWidth + "px"
	});
	firstMonthTitle.innerHTML = "<span class='month-span'>" + hbGlob.monthNames[hbGlob.today.getMonth()] + "</span>";

	var secondMonthTitle = document.getElementById("second-month-title");
	jQuery(secondMonthTitle).css({
		width: secondMonthWidth + "px"
	});

	var secondMonthName;
	if (hbGlob.today.getMonth() == 11) {
		secondMonthName = hbGlob.monthNames[0];
	} else {
		secondMonthName = hbGlob.monthNames[hbGlob.today.getMonth() + 1];
	}

	secondMonthTitle.innerHTML = "<span class='month-span'>" + secondMonthName + "</span>";


	var dateRow = document.getElementById("dates-row");

	var d = 1;

	for (var b = 0; b < (hbGlob.daysThisMonth + hbGlob.daysNextMonth); b++) {

		var dateId = (hbGlob.monthNow + "-" + hbGlob.yearNow + "-" + b);

		var dateCell = dateRow.insertCell();
		dateCell.id = dateId;
		dateCell.className = 'date-cell ' + "dc" + b;
		dateCell.innerHTML = hbGlob.daysArray[b][0] + "<br /><span class='days'>" + hbGlob.nameOfDays[(hbGlob.daysArray[b][1])] + "</span>";

		if (b == hbGlob.daysThisMonth - 1) dateCell.className = dateCell.className + " month-border";

	};
};


function hbook_createRooms() {

	i = 0;
	var calendarTable = document.getElementById("calendar-table");


	// Loop that creates a row and two cells for each room
	for (room in hbGlob.rooms) {
		i++;
		var tbody = document.createElement("tbody");
		var mytable = calendarTable.appendChild(tbody);
		mytable.id = "selectable-" + room;
		mytable.className = "ui-selectable";

		var rowid = room;

		var tr = mytable.insertRow();
		tr.id = rowid;
		tr.className = 'row-cont';

		var leftid = "bed-title-cell" + room;
		var centerid = "room-name" + room;

		var td1 = tr.insertCell();
		td1.id = centerid;
		td1.className = 'room-name-cell';

		var td2 = tr.insertCell();
		td2.id = leftid;
		td2.className = 'room-title-cell month-border';
		td2.colSpan = hbGlob.daysThisMonth;
		td2.innerHTML = "<span class='room-name'>" + hbGlob.rooms[room].name + "</span>";

		var td3 = tr.insertCell();
		td3.id = leftid + "-2";
		td3.className = 'room-title-cell';
		td3.colSpan = hbGlob.daysNextMonth;
		td3.innerHTML = "<span class='room-name'>" + hbGlob.rooms[room].name + "</span>";

		// Loop that creates the row id, creates row and cell for the title cell, populates cell with bed name + number
		for (var j = 0; j < hbGlob.rooms[room].beds; j++) {

			var bedid = (rowid + "-" + (j + 1));
			var bedrow = mytable.insertRow();
			var bedTitle = bedrow.insertCell();
			bedTitle.id = "bed-title-" + j;
			bedTitle.className = 'bed-title-cell  ui-state-default';
			bedTitle.innerHTML = "<span class='bed-title'>" + "Bed " + (j + 1) + "</span>";


			for (var b = 1; b <= hbGlob.daysThisMonth; b++) {
				var bedcellid = "n" + bedid + "-" + b;

				var bedcell = bedrow.insertCell();
				bedcell.id = bedcellid;
				bedcell.axis = "firstMonth";
				bedcell.className = "bed-cell ui-state-default ui-selectee firstMonth";

				if (b == hbGlob.daysThisMonth) bedcell.className = bedcell.className + " month-border";

			}

			for (var b = 1; b <= hbGlob.daysNextMonth; b++) {
				var bedcellid = "s" + bedid + "-" + b;

				var bedcell = bedrow.insertCell();
				bedcell.id = bedcellid;
				bedcell.className = "bed-cell ui-state-default ui-selectee hbGlob.secondMonth";
				bedcell.axis = "hbGlob.secondMonth";
			}

		}
	}
};



function hbook_grabDates(name) {


	jQuery("#selectable-" + name).selectable({

		stop: function() {

			hbGlob.allBookings[hbGlob.monthNow][name] = [];
			hbGlob.allBookings[hbGlob.secondMonth][name] = [];

			jQuery("td.ui-selected", this).each(function() {
				if (this.axis == "firstMonth") {
					hbGlob.allBookings[hbGlob.monthNow][name].push(this.id);
				} else {
					hbGlob.allBookings[hbGlob.secondMonth][name].push(this.id);
				}
			});

			hbGlob.roomTotals[name] = (hbGlob.allBookings[hbGlob.monthNow][name].length + hbGlob.allBookings[hbGlob.secondMonth][name].length) * hbGlob.rooms[name].price;

			hbGlob.dates_array = [];
			for (i = 0; i < hbGlob.allBookings[hbGlob.monthNow][name].length; i++) {
				var date = hbGlob.allBookings[hbGlob.monthNow][name][i].substring(hbGlob.allBookings[hbGlob.monthNow][name][i].lastIndexOf("-") + 1);
				if (hbGlob.dates_array.indexOf(date) < 0) hbGlob.dates_array.push(date);
			}

			for (i = 0; i < hbGlob.allBookings[hbGlob.secondMonth][name].length; i++) {
				var date = hbGlob.allBookings[hbGlob.secondMonth][name][i].substring(hbGlob.allBookings[hbGlob.secondMonth][name][i].lastIndexOf("-") + 1);
				if (hbGlob.dates_array.indexOf(date) < 0) hbGlob.dates_array.push(date);
			}

			if (!(document.contains(document.getElementById("sumrow-" + name)))) hbook_summaryRow(name);
			if (hbGlob.allBookings[hbGlob.monthNow][name].length > 0 || hbGlob.allBookings[hbGlob.secondMonth][name].length > 0) {
				hbook_summaryCalc(name);
			} else {
				delete hbGlob.allBookings[hbGlob.monthNow][name];
				delete hbGlob.allBookings[hbGlob.secondMonth][name];
				if (document.contains(document.getElementById("sumrow-" + name))) {
					var table = document.getElementById("tbody-summary");
					var row = document.getElementById("sumrow-" + name);
					table.removeChild(row);
				};
			}

			if (hbGlob.allBookings[hbGlob.monthNow][name] == undefined || hbGlob.allBookings[hbGlob.monthNow][name].length < 1) delete hbGlob.allBookings[hbGlob.monthNow][name];
			if (hbGlob.allBookings[hbGlob.secondMonth][name] == undefined || hbGlob.allBookings[hbGlob.secondMonth][name].length < 1) delete hbGlob.allBookings[hbGlob.secondMonth][name];
		}


	});

	jQuery("#selectable-" + name).selectable({

		filter: ".bed-cell",

	});

}

function hbook_addRoomTotals() {
	hbGlob.grandTotal = 0;
	for (total in hbGlob.roomTotals) {
		hbGlob.grandTotal += hbGlob.roomTotals[total];
	}

	jQuery("#grandtotal").html(hbGlob.grandTotal);

};

function hbook_summaryCalc(name) {
	document.getElementById("name-" + name).innerHTML = hbGlob.rooms[name].name;
	document.getElementById("beds-" + name).innerHTML = ((hbGlob.allBookings[hbGlob.monthNow][name].length + hbGlob.allBookings[hbGlob.secondMonth][name].length) / hbGlob.dates_array.length).toFixed(0);
	document.getElementById("nights-" + name).innerHTML = hbGlob.dates_array.length;
	document.getElementById("price-" + name).innerHTML = hbGlob.roomTotals[name];
}

function hbook_cancelSummary(btn) {

	var name = btn.id;
	delete hbGlob.allBookings[hbGlob.monthNow][name];
	delete hbGlob.allBookings[hbGlob.secondMonth][name];

	var row = btn.parentNode.parentNode;
	row.parentNode.removeChild(row);
}


hbGlob.roomTotals = {};

function hbook_sendBooking(frontBack) {

	hbook_addRoomTotals();

	var formData = {
		'name': jQuery('input[name=name]').val(),
		'email': jQuery('input[name=email]').val(),
		'phone': jQuery('input[name=phone]').val(),
		'gender': jQuery('select[name=gender]').val(),
		'message': jQuery('textarea[name=message]').val()
	};


	jQuery.ajax({
		url: ajaxurl,
		type: 'post',
		data: {
			"action": 'process_booking',
			"allBookings": hbGlob.allBookings,
			"currentYear": hbGlob.yearNow,
			"secondYear": hbGlob.secondYear,
			"currentMonth": hbGlob.monthNow,
			"secondMonth": hbGlob.secondMonth,
			"formData": formData,
			"price": hbGlob.grandTotal,
			"pending": frontBack
		},
		success: function(data) {document.getElementById("jfn").innerHTML = data;}
	});

	alert("Your booking was successful!");
	document.getElementById("hostel-cal-booking-form").reset();
	location.reload();
	window.scrollTo(0, 0);

}

function hbook_fetchBookings() {
	 jQuery.ajax({
     url: ajaxurl,
     type: 'post',
     data: {"currentYear" : hbGlob.yearNow, "secondYear" : hbGlob.secondYear, "currentMonth" : hbGlob.monthNow, "secondMonth" : hbGlob.secondMonth, "action" : 'fetch_bookings'},
     success: function(data) {

          window.hbGlob.reservations = JSON.parse(data);

          hbook_addBookings();

     }
  });  
}

function hbook_summaryRow(name) {

	var tr = document.getElementById("tbody-summary").insertRow();
	tr.className = 'row-summary';
	tr.id = 'sumrow-' + name;


	var td1 = tr.insertCell();
	td1.className = 'cell-summary cell-room';
	td1.innerHTML = "<span class='cell-data' id='name-" + name + "'></span>";

	var td2 = tr.insertCell();
	td2.className = 'cell-summary';
	td2.innerHTML = "<span class='cell-data' id='beds-" + name + "'></span>";

	var td3 = tr.insertCell();
	td3.className = 'cell-summary';
	td3.innerHTML = "<span class='cell-data' id='nights-" + name + "'></span>";

	var td4 = tr.insertCell();
	td4.className = 'cell-summary';
	td4.innerHTML = "<span class='cell-data' id='price-" + name + "'></span>";

	var td5 = tr.insertCell();
	td5.className = 'cell-summary';
	td5.innerHTML = "<input type='button' class='btn-delete' value='X' onclick='hbook_cancelSummary(this)' id='" + name + "' />";
}


function hbook_addBookings() {

	var bedCount = {};
	var bedCount2 = {};
	var backEnd = document.getElementById("pending_orders");

	for (var i = 0; i < hbGlob.reservations.length; i++) {
		var currentRoom = hbGlob.reservations[i].room;


		if (bedCount[currentRoom] == null) bedCount[currentRoom] = [];
		if (bedCount2[currentRoom] == null) bedCount2[currentRoom] = [];

		for (var d = 0; d < hbGlob.reservations[i].dates.length; d++) {

			var currentPair = hbGlob.reservations[i].dates[d];
			var currentDate = currentPair.substring(0, currentPair.indexOf(":"));

			var currentBeds = parseInt(currentPair.substring(currentPair.indexOf(":") + 1));

			if (bedCount[currentRoom][currentDate] == null) bedCount[currentRoom][currentDate] = 0;
			if (bedCount2[currentRoom][currentDate] == null) bedCount2[currentRoom][currentDate] = 0;


			for (var b = 1; b <= currentBeds; b++) {

				if (hbGlob.reservations[i].month == hbGlob.monthNow) {
					var currentId = "n" + currentRoom + "-" + (bedCount[currentRoom][currentDate] + b) + "-" + currentDate;
				} else {
					var currentId = "s" + currentRoom + "-" + (bedCount2[currentRoom][currentDate] + b) + "-" + currentDate;
				}


				var currentThang = document.getElementById(currentId);

				if (currentThang) {
					currentThang.setAttribute("onclick", "hbook_occupied();");

					if (hbGlob.reservations[i].pending == 1 && backEnd) {
						currentThang.className = "pending";
					} else {
						currentThang.className = "disabled";
					}

					if (backEnd) {

						var cellTitle = "";

						cellTitle += "Name: " + hbGlob.reservations[i].order.name + "  -  " +
							"Email: " + hbGlob.reservations[i].order.email + "  -  " +
							"Phone: " + hbGlob.reservations[i].order.phone + "  -  " +
							"Booking Ref: " + hbGlob.reservations[i].order.booking_ref + " ";


						currentThang.setAttribute("alt", cellTitle);

					}
				}

			}
			if (hbGlob.reservations[i].month == hbGlob.monthNow) {
				bedCount[currentRoom][currentDate] += currentBeds;
			} else {
				bedCount2[currentRoom][currentDate] += currentBeds;
			}
		}
	}

};

function hbook_formValidation() {

	var firstValid;

	jQuery("#email").on("change", function() {
		var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
		var valid = emailReg.test(this.value);


		if (!valid) {
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


	jQuery('#btn-send-booking').click(function(e) {
		var isValid = 0;
		jQuery('#name, #phone, #email').each(function() {
			if (jQuery.trim(jQuery(this).val()) == '') {
				isValid += 0;
				jQuery(this).css({
					"border": "1px solid red",
					"background": "#FFCECE"
				});
			} else {
				jQuery(this).css({
					"border": "",
					"background": ""
				});
				isValid += 1;
			}
		});

		if (isValid < 3) alert("Must fill fields: Name - Phone - Email");
		if ((Object.keys(hbGlob.allBookings[hbGlob.monthNow]).length == 0) && (Object.keys(hbGlob.allBookings[hbGlob.secondMonth]).length == 0)) alert("You haven't selected any beds!");
		if ((Object.keys(hbGlob.allBookings[hbGlob.monthNow]).length > 0) || (Object.keys(hbGlob.allBookings[hbGlob.secondMonth]).length > 0)) var bookingExists = true;
		if (isValid == 3 && firstValid == true && bookingExists == true) hbook_sendBooking("bck7384FvW");
	});


};


function hbook_confirmBooking(e, condel) {

	if (action == "delete") {
		var goAhead = 0;
		if (confirm('Are you sure you want to delete this order?')) goAhead = 1;
	}

	if (goAhead == 0) return;

	var cells = e.parentNode.parentNode.getElementsByTagName("td");
	var ref = cells[4].innerHTML;

	jQuery.ajax({
		url: ajaxurl,
		type: 'post',
		data: {
			"booking_ref": ref,
			"action": 'hbook_confirm_order',
			"condel": condel
		},
		success: function(data) {
			// Do something with data that came back. 
			alert(data);
		}
	});

	var row = e.parentNode.parentNode;
	row.parentNode.removeChild(row);

};

function hbook_occupied() {
	alert("This bed is occupied");
};