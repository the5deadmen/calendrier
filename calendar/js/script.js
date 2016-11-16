if (!Object.keys) {
	Object.keys = (function () {
		var hasOwnProperty = Object.prototype.hasOwnProperty,
			hasDontEnumBug = !({toString: null}).propertyIsEnumerable('toString'),
			dontEnums = ['toString', 'toLocaleString', 'valueOf', 'hasOwnProperty', 'isPrototypeOf', 'propertyIsEnumerable', 'constructor'],
			dontEnumsLength = dontEnums.length;

	return function (obj) {
		if (typeof obj !== 'object' && typeof obj !== 'function' || obj === null) throw new TypeError('Object.keys called on non-object');

		var result = [];

		for (var prop in obj) { if (hasOwnProperty.call(obj, prop)) result.push(prop); }

		if (hasDontEnumBug) {
			for (var i=0; i < dontEnumsLength; i++) {
			if (hasOwnProperty.call(obj, dontEnums[i])) result.push(dontEnums[i]);
			}
		}
		return result;
	};
  })();
}

var m_text = ["", "janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"];
var weekdays = ["lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi", "dimanche"];
var tbody;
var this_td;
var time_format;
var d = new Date();
var day = d.getDate();
var month = d.getMonth() + 1;
var year = d.getFullYear();
var days_number = daysInMonth(month, year);
var actual_d = d.getDate();
var actual_m = d.getMonth() + 1;
var actual_y = d.getFullYear();
var page = 1, showEvents = [];
var storage;

$(function() {
	tbody = $("tbody");
	time_format = $("#timeFormat").val();

	tbody.val(getCalendar(year, month));

	wdaysOriginal();

	$(".active").on("click", function () {
		$(this).removeClass("active");
		$(this).hide();
	});

	$("table").delegate("td", "click", function () {
		$("td").removeClass("active");
		$("#myModalLabel").text("Events");

		if (!$(this).hasClass("blank")) {
			$(this).addClass("active");


			this_td = $(this);

			$(".modal-body").empty();
			$(".modal-footer").empty().removeClass("addEvent");

			$('#myModal').modal("show");

			if (typeof($(this).children(".label").html()) !== "undefined") {
				page = 1;
				showEvents = [];

				if (parseInt(this_td.children(".label").html()) > 4) {
					showEvents = [0, 1, 2, 3];
				}
				else {
					for (i = 0; i < parseInt(this_td.children(".label").html()); i++) {
						showEvents.push(i);
					}
				}
				getFirstEvents(this_td);
			}
			else {
				getFirstEvents(null);
			}
		}
	});

	$("#myModal").on("hidden.bs.modal", function () {
		$("td").removeClass("active");
	});

	var width = $(window).width();
	if (width < 960) {
		$("tr.weekdays").empty();
		for (i = 0; i < 7; i++) {
			weekdays[i] = weekdays[i].substring(0, 3);
			$("tr.weekdays").append("<th>" + weekdays[i] + "</th>");
		}
	}
});

function getFirstEvents() {
		$.ajax({
			dataType: "json",
			url: "calendar/php/date.php",
			type: "POST",
			data: {
				select: "%Y-%c-%e",
				where: "%Y-%c-%e",
				d: this_td.attr("id"),
				order: "DATE_FORMAT(timestamp, '%H-%i')"
			},
			beforeSend: function() { $(".modal-body").html("<span class=\"loading\">Chargement...</span>"); },
			success: function(data) {
				getEvents(parseInt(this_td.children(".label").html()), data[this_td.attr("id")], this_td.attr("id"));
			},
			error: function () { getEvents(null, null, this_td.attr("id")); }
		});
}

function wdaysOriginal() {
	if ($("tr.weekdays").attr("id") == "sunday") {
		weekdays.unshift(weekdays.pop());
	}

	for (i = 0; i < 7; i++) {
		$("tr.weekdays").append("<th>" + weekdays[i] + "</th>");
	}
}

function daysInMonth(month, year) {
	return new Date(year, month, 0).getDate();
}

function Go(e) {
	if (e === "prev") {
		if (month === 1) { year -= 1; month = 12; }
		else { month -= 1; }
	}
	else if (e === "next") {
		if (month === 12) { year += 1; month = 1; }
		else { month += 1; }
	}
	else if (e === "today") { year = actual_y; month = actual_m; }

	days_number = daysInMonth(month, year);

	tbody.find("*").remove();
	tbody.val(getCalendar(year, month));

	$("span#anio").text(year);
	$("span#mes").text(m_text[month]);
}

function getCalendar(y, m) {
	function firstDay(month, year) {
		if ($(".weekdays").attr("id") == "sunday") { return new Date(year, (month - 1), 2).getDay(); }
		else { return new Date(year, (month - 1), 1).getDay(); }
	}

	$("span#mes").text(m_text[month]);
	$("span#anio").text(year);

	var diap = 1,
		empty = 0;

	firstDay = firstDay(m, y);
	// var daysLast = daysInMonth(m - 1, year) + 2;
	
	for (i = 1; i <= 7; i++) {
		if (firstDay === 0) {
			firstDay = 7;
		}
		if (i < firstDay) {
			if (i == 1) {
				tbody.append("<td class='border-left-off blank'><span></span></td>");
			}
			else {
				tbody.append("<td class='blank'><span></span></td>");
			}
			empty++;
		}
		else if (y == actual_y && m == actual_m && diap == actual_d) {
			if (i == 1) {
				tbody.append("<td id='" + y + "-" + m + "-" + diap + "' class='actual border-left-off'><span><a role='button' class='days' data-toggle='modal'>" + diap + "</a></span></td>");
			}
			else {
				tbody.append("<td id='" + y + "-" + m + "-" + diap + "' class='actual'><span><a role='button' class='days' data-toggle='modal'>" + diap + "</a></span></td>");
			}
			diap++;
		}
		else {
			if (i == 1) {
				tbody.append("<td id='" + y + "-" + m + "-" + diap + "' class='border-left-off'><span><a role='button' class='days' data-toggle='modal'>" + diap + "</a></span></td>");
			}
			else {
				tbody.append("<td id='" + y + "-" + m + "-" + diap + "'><span><a role='button' class='days' data-toggle='modal'>" + diap + "</a></span></td>");
			}
			diap++;
		}
	}

	tbody.wrapInner("<tr>");

	for (i = 1; i <= days_number; i++) {
		if (y === actual_y && m === actual_m && diap === actual_d) {
			if (i == 1 || i == 8 || i == 15 || i == 22 || i == 29) {
				tbody.append("<td id='" + y + "-" + m + "-" + diap + "' class='actual b border-left-off'><span><a role='button' class='days' data-toggle='modal'>" + diap + "</a></span></td>");
			}
			else {
				tbody.append("<td id='" + y + "-" + m + "-" + diap + "' class='actual b'><span><a role='button' class='days' data-toggle='modal'>" + diap + "</a></span></td>");
			}
		}
		else {
			if (i == 1 || i == 8 || i == 15 || i == 22 || i == 29) {
				tbody.append("<td id='" + y + "-" + m + "-" + diap + "' class='border-left-off b'><span><a role='button' class='days' data-toggle='modal'>" + diap + "</a></span></td>");
			}
			else {
				tbody.append("<td id='" + y + "-" + m + "-" + diap + "' class='b'><span><a role='button' class='days' data-toggle='modal'>" + diap + "</a></span></td>");
			}
		}

		if (i % 7 === 0) { tbody.append("</tr><tr>"); }

		if (diap === days_number) {
			var numberOfWeeks = Math.ceil((days_number + empty) / 7),
				pre_total = days_number + empty,
				post_total = numberOfWeeks * 7;

			for (i = 1; i <= post_total - pre_total; i++) {
				tbody.append("<td class='b blank'><span></span></td>");
			}
			
			break;
		}

		diap++;
	}

	if ("sessionStorage" in window && window.sessionStorage !== null) { storage = sessionStorage; }
	else { alert("What an old browser! The webpage won´t work correctly with this browser, update it!"); }

	if (storage == sessionStorage && sessionStorage[y+"-"+m] !== undefined) {
		data = JSON.parse(sessionStorage.getItem(y+"-"+m));
		for (var key in data) {
			$("<span class='label label-success'>" + Object.keys(data[key]).length + "</span>").prependTo($("#" + key));
		}
	}
	else {
		$.ajax({
			dataType: "json",
			url: "calendar/php/date.php",
			type: "POST",
			data: {
				select: "%Y-%c-%e",
				where: "%Y-%c",
				d: y + "-" + m,
				order: "timestamp, id"
			},
			beforeSend: function() { $("#loading").show(); },
			success: function(data) {
				sessionStorage[y+"-"+m] = JSON.stringify(data);
				for (var key in data) {
					$("<span class='label label-success'>" + Object.keys(data[key]).length + "</span>").prependTo($("#" + key));
				}
			},
			complete: function() { $("#loading").fadeOut(200); }
		});
	}
}

function getEvents(e, data, td_id) {

	if ($("#admin").val() == "true") {
		$("#addEvent").remove();
		$(".modal-header").append("<button class='btn btn-small btn-success add' id='addEvent' onclick=\"addEvent('"+ td_id + "');\">+ Ajouter</button>");
	}

	$(".modal-body").empty();
	$(".modal-footer").empty();

	if (e !== null) {
		if (e > 4 && page > 1 && e - (showEvents[showEvents.length-1]+1) !== 0 && typeof(showEvents[3]) !== "string") {
			$(".modal-footer").append("<a class='next' onclick=\"pageEvents('more', '" + e + "')\" href='javascript:void(0)'></a>");
			$(".modal-footer").append("<a class='prev' onclick=\"pageEvents('less', '" + e + "')\" href='javascript:void(0)'></a>");
		}
		else if (e > 4) {
			if (e - showEvents[showEvents.length-1] >= 1) {
				$(".modal-footer").append("<a class='next' onclick=\"pageEvents('more', '" + e + "')\" href='javascript:void(0)'></a>");
			}
			if (page > 1) {
				$(".modal-footer").append("<a class='prev' onclick=\"pageEvents('less', '" + e + "')\" href='javascript:void(0)'></a>");
			}
		}

		for (i = 0; i < showEvents.length; i++) {
			try {
				if (showEvents[i] != "null") {
					data[i] = data[showEvents[i]];
				}
				else {
					break;
				}

				time = data[i].timestamp.replace(/-|:/g, " ").split(" ").slice(3, 6);
				time = new Date(2000, 01, 01, time[0], time[1], time[2]);

				time = {
					hours: time.getHours(),
					minutes: time.getMinutes() < 10 ? "0" + time.getMinutes() : time.getMinutes(),
					ampm: (time.getHours() < 12 ? "am" : "pm")
				}

				if (time_format == "standard") {
					time.hours = (time.hours > 12) ? time.hours - 12 : time.hours;

					time = time.hours + ":" + time.minutes + time.ampm;
				}
				else if (time_format == "military") {
					time = time.hours + ":" + time.minutes + "hs";
				}

				if (data[i].location === null) {
					$(".modal-body").append("<div id='event'><span class='title'><h4 style='margin: 24px 0px'>" + data[i].title + "</h4></span><span class='time'><span style='width: 78px; float: left;'><span class='glyphicon glyphicon-time' style='top: 2px'></span> " + time + "</span></span></div>");
				}
				else {
					$(".modal-body").append("<div id='event'><span class='title'><h4>" + data[i].title + "</h4><p>at <i>" + data[i].location + "</i></p></span><span class='time'><span style='width: 78px; float: left;'><span class='glyphicon glyphicon-time' style='top: 2px'></span> " + time + "</span></span></div>");
				}
			}
			catch (err) {}
		}
	}
	else {
		$(".modal-body").append("<span class='not-found'>No events found.</span>");
	}
}

function pageEvents(lessmore, e) {
	if (lessmore == "less") {
		page -= 1;

		for (key in showEvents) {
			showEvents[key] -= 4;
		}

		getFirstEvents();
	}
	else if (lessmore == "more") {
		page += 1;

		for (key in showEvents) {
			if (showEvents[key]+4 < e) {
				showEvents[key] += 4;
			}
			else {
				showEvents[key] += 4;
				showEvents[key] = "" + showEvents[key] + "";
			}
		}

		getFirstEvents();
	}
}

function addEvent(date) {
	$(".modal-header").html(modalAddEvent.header);
	$(".modal-body").html(modalAddEvent.body);
	$(".modal-footer").html(modalAddEvent.footer).addClass("addEvent");

	$("#inputDate").datepicker({ firstDay: 1 });
	
	if (time_format == "standard") {
		$("#inputTime").timepicker({
			timeFormat: 'hh:mm tt'
		});
	}
	else {
		$("#inputTime").timepicker();
	}

	date = date.split("-");
	date = date[1] + "/" + date[2] + "/" + date[0];

	$("#inputDate").val(date);

	$("button#add").on("click", function() {
		form = { title: $("#inputTitle").val(),
				 location: $("#inputLocation").val(),
				 date: $("#inputDate").val(),
				 time: $("#inputTime").val() };

		title = form.title;
		loc = form.location;
		date = new Date(form.date);
		date = date.getFullYear() + "-" +
			   (date.getMonth() + 1 < 10 ? "0" + (date.getMonth() + 1) : date.getMonth() + 1) + "-" +
			   (date.getDate() < 10 ? "0" + date.getDate() : date.getDate());
		time = form.time + ":00";

		if (time_format == "standard") {

			if (time.indexOf("am") > -1) {
				
				time = form.time.split(" ")[0];
				original = time.split(":");
				
				if (parseInt(original[0]) == 12) {
					time = "00:" + original[1];
				}
			}
			else {
				time = form.time.split(" ")[0];
				original = time.split(":");

				if (parseInt(original[0]) != 12) {
					timePm = parseInt(original[0]) + 12;
					time = timePm + ":" + original[1];
				}
			}
		}

		timestamp = date + " " + time;

		if ((title && loc && timestamp) !== "") {
			form_data = { 1: title, 2: loc, 3: timestamp };
			
			$.ajax({
				type: "POST",
				data: { action: "insert",
						title: form_data[1],
						loc: form_data[2],
						timest: form_data[3] },
				url: "calendar/admin/php/func_events.php",
				beforeSend: function() { $("button#add").button("loading"); },
				success: function() {
					$("#myModal").modal("hide");

					sessionStorage.clear();
					window.location.reload();
				},
				error: function() { alert("Une erreur s'est produite lors de l'ajout de l'événement."); }
			});
		}
		else {
			alert("Vous devez remplir tous les champs.");
		}
	});
}

$(window).resize(function() {
	var width = $(window).width();
	if (width < 960) {
		wdaysOriginal();
		$("tr.weekdays").empty();

		for (i = 0; i < 7; i++) {
			weekdays[i] = weekdays[i].substring(0, 3);
			$("tr.weekdays").append("<th>" + weekdays[i] + "</th>");
		}
	}
	else {
		$("tr.weekdays").empty();
		wdaysOriginal();
	}
});

// Arrows shorcuts
$(document).keydown(function(e) {
	var key = e.which;
	
	if (key == "37") { Go("prev"); }
	if (key == "39") { Go("next"); }
});

var modalAddEvent = { header: "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button><h3 id='myModalLabel'>Ajouter un évènement</h3>",
						 body: "<form class='form-horizontal' id='addEvent' action=' method='post'>" +
									"<div class='form-group'>" +
										"<label class='col-sm-3 control-label' for='inputTitle'>Titre</label>" +
										"<div class='col-sm-7'>" +
											"<input type='text' id='inputTitle' class='form-control' maxlength='32' placeholder='Titre' />" +
										"</div>" +
									"</div>" +
									"<div class='form-group'>" +
										"<label class='col-sm-3 control-label' for='inputLocation'>Emplacement</label>" +
										"<div class='col-sm-7'>" +
											"<input type='text' id='inputLocation' class='form-control' maxlength='26' placeholder='Emplacement' />" +
										"</div>" +
									"</div>" +
									"<div class='form-group'>" +
										"<label class='col-sm-3 control-label' for='inputDate'>date</label>" +
										"<div class='col-sm-7'>" +
											"<input type='text' id='inputDate' class='form-control' maxlength='10' placeholder='date' />" +
										"</div>" +
										"<div id='datepicker'></div>" +
									"</div>" +
									"<div class='form-group'>" +
										"<label class='col-sm-3 control-label' for='inputTime'>Temps</label>" +
										"<div class='col-sm-7'>" +
											"<input type='text' id='inputTime' class='form-control' maxlength='5' placeholder='Temps' />" +
										"</div>" +
									"</div>" +
								"</form>",
						 footer: "<button type='button' id='add' class='btn btn-success' data-loading-text='Ajout d'un événement ...'>AJOUTER UN ÉVÈNEMENT</button>" };