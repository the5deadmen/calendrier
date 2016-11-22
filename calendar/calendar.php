<?php
require 'calendar/php/session.php';
require 'calendar/admin/settings.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>eCalendar</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8">
		<!-- Bootstrap -->
			<link rel="StyleSheet" href="calendar/css/bootstrap.min.css" media="screen">
		<!-- Main style -->
			<link rel="StyleSheet" href="calendar/css/main.css" media="screen">
			<link rel="StyleSheet" href="calendar/css/<?php echo strtolower($calendar_style); ?>-style.css" media="screen">
		<script type="text/javascript" src="calendar/js/jquery.min.js"></script>
		<script type="text/javascript" src="calendar/js/json2.js"></script>
		<script type="text/javascript" src="calendar/js/script.js"></script>
	</head>
	<body onload="sessionStorage.clear();">
		<?php
			if (isSet($_SESSION['admin'])) {
				include 'calendar/admin/php/headerAdmin.php';
			}
		?>
		<div id="loading" class="loading">Chargement...</div>
		<?php
		
			if (!empty($time_format)) {
				echo '<input type="hidden" id="timeFormat" value="' . strtolower($time_format) .'" />';
			}
		
		?>
		<div class="container">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th><span id="anio"></span></th>
						<th colspan="5" style="padding: 0px; line-height: 38px;">
							<a class="prev" onclick="Go('prev')" href="javascript:void(0)"></a>
							<span id="mes"></span>
							<a class="next" onclick="Go('next')" href="javascript:void(0)"></a>
						</th>
						<th style="padding: 0px; line-height: 40px;">
							<a class="today" onclick="Go('today')" href="javascript:void(0)">AUJOURD'HUI</a>
						</th>
					</tr>
					<tr class="weekdays info" <?php echo "id=\"", strtolower($first_day), "\""; ?>>
						<!-- WEEKDAYS -->
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
    			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 id="myModalLabel">Événements</h3>
					</div>
					<div class="modal-body">
					</div>
					<div class="modal-footer">
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="calendar/js/bootstrap.min.js"></script>
	</body>
</html>