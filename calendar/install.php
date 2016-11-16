<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$adminUser = trim($_POST['admin_user']);
	$adminPass = trim($_POST['admin_pass']);

	$calendarStyle = trim($_POST['calendar_style']);
	$firstDay = trim($_POST['first_day']);
	$timeFormat = trim($_POST['time_format']);

	$dbUser = trim($_POST['db_user']);
	$dbPass = trim($_POST['db_pass']);
	$dbHost = trim($_POST['db_host']);
	$dbName = trim($_POST['db_name']);

	$string = "<?php

/*
	DO NOT DELETE THESE FILE!!
*/


//\\//\\ ADMIN ACCOUNT //\\//\\

\$admin_user = \"".$adminUser."\"; // ADMIN USERNAME
\$admin_pass = \"".$adminPass."\"; // ADMIN PASSWORD


//\\//\\ CALENDAR PREFERENCES //\\//\\

\$calendar_style = \"".$calendarStyle."\"; // \"CLASSIC\" or \"MODERN\"
	 \$first_day = \"".$firstDay."\"; // FIRST WEEKDAY - \"Sunday\" or \"Monday\"
   \$time_format = \"".$timeFormat."\";	// \"STANDARD\" (0-12hs) or \"MILITARY\" (0-24hs)


//\\//\\ DATABASE CONNECTION //\\//\\

\$db_user = \"".$dbUser."\"; // DB USERNAME
\$db_pass = \"".$dbPass."\"; // DB PASSWORD
\$db_name = \"".$dbName."\"; // DATABASE NAME
\$db_host = \"".$dbHost."\"; // DB HOST

?>";

	$fp = fopen("admin/settings.php", "w");
	fwrite($fp, $string);
	fclose($fp);

	// Create Table

	require 'admin/settings.php';
	require 'config/db_config.php';

	$sql = "CREATE TABLE events (id int(10) unsigned NOT NULL AUTO_INCREMENT,
								 timestamp TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
								 title VARCHAR(45) NOT NULL DEFAULT 'No title',
								 location VARCHAR(200) NOT NULL DEFAULT 'No location',
								 PRIMARY KEY(id))";

	if (mysqli_query($link, $sql)) {
		echo "true";
	}
	else {
		echo "false";
	}
}

?>

<!DOCTYPE html>
<html>
	<head>
	<title>eCalendar - Installation</title>
	<meta charset="utf-8">
	<!-- Bootstrap -->
		<link rel="Stylesheet" href="css/bootstrap.min.css" media="screen">
	<!-- Main style -->
		<link rel="StyleSheet" href="admin/css/main.css" type="text/css" />
		<script src="js/jquery.min.js"></script>
		<script type="text/javascript">

			$(function() {

				$("form").on("submit", function(e) {
					e.preventDefault();

					var a = true;
					
					for (i = 0; i < $("input").length; i++) {
						if ($("input")[i].value == "") {
							if ($("input")[i] == $("input")[4]) {
								a = true;
							}
							else {
								alert("You must complete all the fields");
								a = false;
								break;
							}
						}
					}

					if (a == true) {
						$.ajax({
							type: $(this).attr("method"),
							data: $(this).serialize(),
							url: "install.php",
							success: function(data) {
								console.clear();
								if (data.substr(0, 4) == "true") {
									$("#section").empty();

									html = "<h3 style='text-align: center'>Well done!</h3><br />" +
										   "<h4 style='text-align: center'>Now you must delete this file (\"calendar/install.php\") from the calendar's directory.</h4>" +
										   "<h4 style='text-align: center'>Continue reading instructions.txt step by step!</h4><br />" +
										   "<h5 style='text-align: center'>For more support send me an email: facu6mg@gmail.com. Enjoy your script!</h5>" +
										   "<br/><br/><br/><a style='text-align: center' href='../calendar.php'><h4>Go to eCalendar</h4></a>";

									$("#section").html(html);
								}
								else if (data.substr(0, 4) == "false") {
									alert("There was a problem creating the table. Check if it hasn't already been created.")
								}
								else {
									alert("Something went wrong. Try filling the form again and make sure that the database exists.")
								}
								console.log(data.substr(0, 4));
								console.log(data);
							},
							error: function(data) {
								alert("ERROR");
							}
						});
					}
				});
			});

		</script>
	</head>
	<body>
		<div class="container">
			<div class="well">
				<div class="header">
					<h2>Installation</h2>
				</div>
				<div id="section">
					<div class="tab-content">
						<form class="form-horizontal" action="" method="post" style="margin: 0px" role="form">
							<fieldset>
							<legend>Admin</legend>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="inputUser">Username</label>
								<div class="col-sm-7">
									<input type="text" class="form-control" name="admin_user" id="inputUser" placeholder="The username you will use to enter the control panel" autocomplete="off" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="inputPass">Password</label>
								<div class="col-sm-7">
									<input type="password" class="form-control" name="admin_pass" placeholder="The password you will use to enter the control panel" id="inputPass" />
								</div>
							</div>
							</fieldset>
				<!---->
							<fieldset>
							<legend>Preferences</legend>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="selectStyle">Calendar Style</label>
								<div class="col-sm-2">
									<select id="selectStyle" class="form-control" name="calendar_style" value="Select">
										<option>Classic</option>
										<option>Modern</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="selectStyle">First Weekday</label>
								<div class="col-sm-2">
									<select id="selectStyle" class="form-control" name="first_day" value="Select">
										<option>Sunday</option>
										<option>Monday</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="selectFormat">Time Format</label>
								<div class="col-sm-3">
									<select id="selectFormat" class="form-control" name="time_format">
										<option value="standard" <?php if (strtolower($time_format) == "standard") echo "selected" ?>>Standard (0-12hs)</option>
										<option value="military" <?php if (strtolower($time_format) == "military") echo "selected" ?>>Military (0 - 24hs)</option>
									</select>
								</div>
							</div>
							</fieldset>
				<!---->
							<fieldset>
							<legend>Database connection</legend>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="inputHost">MySQL Host</label>
								<div class="col-sm-7">
									<input type="text" class="form-control" name="db_host" id="inputHost" placeholder="Host" autocomplete="off" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="inputUser">MySQL Username</label>
								<div class="col-sm-7">
									<input type="text" class="form-control" name="db_user" id="inputUser" placeholder="Username" autocomplete="off" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="inputPass">MySQL Password</label>
								<div class="col-sm-7">
									<input type="password" class="form-control" name="db_pass" id="inputPass" placeholder="Password" />
								</div>
								<div id="datepicker"></div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="inputTable">MySQL Database</label>
								<div class="col-sm-7">
									<input type="text" class="form-control" name="db_name" id="inputTable" placeholder="Database" autocomplete="off" />
								</div>
							</div>
							</fieldset>
							<span class="badge badge-warning" style="background-color:#f89406;display:block;text-align:center;overflow:hidden">
								Make sure the database exists. Otherwise you will not be able to continue with the installation.
							</span>
							<br />
							<input type="submit" name="save" id="save" class="btn btn-success" value="Save and Create the DB Table" />
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>