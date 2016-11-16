<?php

	require_once '../settings.php';
	require_once '../../config/db_config.php';

	$action = (isset($_POST['action'])) ? clean($link, $_POST['action']) : null;
	$title = (isset($_POST['title'])) ? clean($link, $_POST['title']) : null;
	$location = (isset($_POST['loc'])) ? clean($link, $_POST['loc']) : null;
	$timestamp = (isset($_POST['timest'])) ? clean($link, $_POST['timest']) : null;
	$id = (isset($_POST['id'])) ? intval(clean($link, $_POST['id'])) : null;


	if ($action == "select") {

		$y = (isset($_POST['y'])) ? clean($link, $_POST['y']) : null;
		$m = (isset($_POST['m'])) ? clean($link, $_POST['m']) : null;
		$searchQuery = (isset($_POST['search_q'])) ? clean($link, $_POST['search_q']) : null;

		$sql = "SELECT *, DATE_FORMAT(timestamp, '%M %e, %Y %H:%i') selector FROM events";
				
		if ($y !== null && $m !== null) {
		// Sort by Year and Month
			$sql .= " WHERE DATE_FORMAT(timestamp, '%Y %c') = '".$y." ".$m."' ";
		}
		else if ($y !== null) {
		// Sort by Year
			$sql .= " WHERE DATE_FORMAT(timestamp, '%Y') = '".$y."' ";
		}
		else if ($m !== null) {
		// Sort by Month
			$sql .= " WHERE DATE_FORMAT(timestamp, '%c') = '".$m."' ";
		}
		// Search
		($searchQuery) ? $sql .= " WHERE title LIKE '%".$searchQuery."%' " : '';
						
		$sql .= " ORDER BY timestamp ASC";

		$query = mysqli_query($link, $sql);

		if (mysqli_num_rows($query) > 0) {
			$data = array(); $index = 0;
			while ($recset = mysqli_fetch_array($query)){
				$data[$index] = $recset;
				$index++;
			}

			echo json_encode($data);
		}

	}

	else if ($action == "insert") {

		$timestamp = strtotime($timestamp) ? $timestamp : die;

		$sql = "INSERT INTO events (timestamp, title, location) VALUES ('".$timestamp."', '".$title."', '".$location."')";
		$query = mysqli_query($link, $sql);
		
	}

	else {

		if ($action == "edit") {
			$timestamp = strtotime($timestamp) ? $timestamp : die;
			$sql = "UPDATE events SET title = '".$title."', location = '".$location."', timestamp = '".$timestamp."' WHERE id = '".$id."'";
			
		}
		else if ($action == "del") {
			$sql = "DELETE FROM events WHERE id = '".$id."'";
		}

		$results = mysqli_query($link, $sql) or die(mysqli_error($link));
	}

	function clean($link, $e) {
		return trim(htmlspecialchars(mysqli_real_escape_string($link, $e)));
	}

?>