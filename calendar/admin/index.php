<?php
include '../php/session.php';
require_once 'settings.php';

if (!isSet($_SESSION['admin'])) {
	header('location: login.php');
}
?>

<!DOCTYPE html>
<html>
	<head>
	<title>eCalendar - Panneau de contrôle</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8">
	<!-- Bootstrap -->
		<link rel="Stylesheet" href="../css/bootstrap.min.css" media="screen">
	<!-- Main style -->
		<link rel="StyleSheet" href="css/main.css" type="text/css" />
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<!-- Date/Time picker style -->
		<link rel="StyleSheet" href="css/timepicker-addon.css" type="text/css" />
		<link rel="StyleSheet" href="js/datepicker/css/jquery-ui-1.10.3.custom.min.css" type="text/css" />
	</head>
	<body>
		<?php
		
			if (!empty($time_format)) {
				echo '<input type="hidden" id="timeFormat" value="' . strtolower($time_format) .'" />';
			}
		
		?>
		<div class="container">
			<div class="well">
				<div class="header">
					<h2 onclick="document.location.reload(true);">Panneau de contrôle</h2>
					<div class="btn-group pull-right">
						<button class="btn btn-default btn-medium" onclick="sessionStorage.clear();document.location='../../calendar.php'"><span class="glyphicon glyphicon-calendar" style="margin-top: 1px !important"></span>&nbsp;&nbsp;Calendrier</button>
						<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li><a id="config" href="javascript:void(0);"><span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;Configuration</a></li>
							<li><a id="log-out" href="javascript:void(0)" onclick="document.location='login/logout.php'"><span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Se déconnecter</a></li>
						</ul>
					</div>
				</div>
				<div id="section">
					<button class="btn btn-primary btn-large add" data-toggle="modal" data-target="#myModal">
						Ajouter un évènement
					</button>

					<div class="searchbox">
						<div class="btn-group sortYear">
							<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">An&nbsp;<span class="caret"></span></button>
							<ul class="dropdown-menu">
								<li><a href="javascript:void(0);" id="ally">Tout</a></li>
								<!-- YEARS -->
							</ul>
						</div>
						<div class="btn-group sortMonth">
							<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">Mois&nbsp;<span class="caret"></span></button>
							<ul class="dropdown-menu">
								<li><a href="javascript:void(0);" id="allm">Tout</a></li>
								<!-- MONTHS -->
							</ul>
						</div>
						<div class="input-group">
							<form action="" method="post" id="search" style="display: inherit">
								<input type="text" name="search_q" placeholder="Un événement..." class="form-control" />
								<span class="input-group-btn">
									<input type="submit" class="btn btn-default" value="Chercher" />
								</span>
							</form>
						</div>
					</div>

					<table class="table table-hover">
						<thead>
							<tr>
								<th>prénom</th>
								<th>Emplacement</th>
								<th id="reverseList">date&nbsp;<span class="caret"></span></th>
								<th></th>
							</tr>
						</thead>
						<tbody id="listEvents">
							<!-- EVENT'S TABLE -->
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
    			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 class="modal-title" id="myModalLabel">Ajouter un évènement</h3>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" id="addEvent" action="" method="post" role="form">
							<div class="form-group">
								<label class="col-sm-3 control-label" for="inputTitle">Titre</label>
								<div class="col-sm-7">
									<input type="text" id="inputTitle" class="form-control" maxlength="32" placeholder="Titre" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="inputLocation">Emplacement</label>
								<div class="col-sm-7">
									<input type="text" id="inputLocation" class="form-control" maxlength="26" placeholder="Emplacement" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="inputDate">date</label>
								<div class="col-sm-7">
									<input type="text" id="inputDate" class="form-control" maxlength="10" placeholder="date" />
								</div>
								<div id="datepicker"></div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="inputTime">Temps</label>
								<div class="col-sm-7">
									<input type="text" id="inputTime" class="form-control" maxlength="5" placeholder="Temps" />
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" id="add" class="btn btn-success" data-loading-text="Ajout d'événement ...">AJOUTER</button>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="js/script.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
		<script type="text/javascript" src="../js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/timepicker-addon.js"></script>
	</body>
</html>