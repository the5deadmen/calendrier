<?php 
	(session_id() === '') ? session_start() : '';
	require_once '../settings.php';

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

		$fp = fopen("../settings.php", "w");
		fwrite($fp, $string);
		fclose($fp);
	}

?>

<script type="text/javascript">

	$(function() {
		$("form").on("submit", function(e) {
			e.preventDefault();

			$.ajax({
				type: $(this).attr("method"),
				data: $(this).serialize(),
				url: "php/config.php",
				beforeSend: function() { $("input#save").button('loading') },
				success: function(data) {
					document.location.reload(true);
					alert("Configuration saved");
				},
				error: function(data) {
					alert("ERROR");
				}
			});
		})
	});

</script>

<div class="tab-content">
		<form class="form-horizontal" action="" method="post" style="margin: 0px">
			<fieldset>
			<legend>Admin</legend>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="inputUser">Username</label>
				<div class="col-sm-5">
					<input type="text" name="admin_user" class="form-control" id="inputUser" value="<?php echo $admin_user; ?>" autocomplete="off" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="inputPass">Password</label>
				<div class="col-sm-5">
					<input type="password" name="admin_pass" class="form-control" id="inputPass" value="<?php echo $admin_pass; ?>" />
				</div>
			</div>
			</fieldset>
<!---->
			<fieldset>
			<legend>Preferences</legend>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="selectStyle">Calendar Style</label>
				<div class="col-sm-2">
					<select id="selectStyle" class="form-control" name="calendar_style">
						<option <?php if (strtolower($calendar_style) == "classic") echo "selected" ?>>Classic</option>
						<option <?php if (strtolower($calendar_style) == "modern") echo "selected" ?>>Modern</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="selectStyle">First Weekday</label>
				<div class="col-sm-2">
					<select id="selectStyle" class="form-control" name="first_day">
						<option <?php if (strtolower($first_day) == "sunday") echo "selected" ?>>Sunday</option>
						<option <?php if (strtolower($first_day) == "monday") echo "selected" ?>>Monday</option>
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
				<div class="col-sm-5">
					<input type="text" name="db_host" class="form-control" id="inputHost" value="<?php echo $db_host; ?>" placeholder="Host" autocomplete="off" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="inputUser">MySQL Username</label>
				<div class="col-sm-5">
					<input type="text" name="db_user" class="form-control" id="inputUser" value="<?php echo $db_user; ?>" placeholder="Username" autocomplete="off" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="inputPass">MySQL Password</label>
				<div class="col-sm-5">
					<input type="password" name="db_pass" class="form-control" id="inputPass" value="<?php echo $db_pass; ?>" placeholder="Password" />
				</div>
				<div id="datepicker"></div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="inputTable">MySQL Database</label>
				<div class="col-sm-5">
					<input type="text" name="db_name" class="form-control" id="inputTable" value="<?php echo $db_name; ?>" placeholder="Database" autocomplete="off" />
				</div>
			</div>
			</fieldset>
			<br />
			<input type="submit" name="save" id="save" class="btn btn-success" data-loading-text="Saving..." value="SAVE" />
		</form>
</div>