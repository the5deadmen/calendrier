<?php

$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die ('There was a problem connecting to the database');

mysqli_set_charset($link, "utf8");

?>