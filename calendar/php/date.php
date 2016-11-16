<?php

	require_once '../admin/settings.php';
	require_once '../config/db_config.php';

	$select = trim($_POST['select']);
	$where = trim($_POST['where']);
	$d = trim($_POST['d']);
	$order = trim(stripslashes($_POST['order']));
	$limit = isSet($_POST['limit']) ? " LIMIT ".trim($_POST['limit']) : '';

	$sql = "SELECT *, DATE_FORMAT(timestamp, '".$select."') selector FROM events WHERE DATE_FORMAT(timestamp, '".$where."') = '".$d."' ORDER BY ".$order." ASC".$limit;
	$query = mysqli_query($link, $sql);

	if (mysqli_num_rows($query) > 0) {
		$data = array();

		while ($recset = mysqli_fetch_array($query)) {
			if (!array_key_exists($recset["selector"], $data)) {
				$data[$recset["selector"]] = array($recset);
			}
			else {
				array_push($data[$recset["selector"]],$recset);
			}
		}
		
		echo json_encode($data);
	}

?>