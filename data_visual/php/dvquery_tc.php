<?php
	// collection users inputs
    $start_year    = $_POST['start'];
    $end_year   = $_POST['end'];
    // Create a database connection
    $dbhost = "fa16-cs411-09.cs.illinois.edu";
	$dbuser = "root";
	$dbpass = "cs411fa2016";
	$dbname = "smarvie";
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	// Test if connection occurred.
	if(mysqli_connect_errno()) {
		die("Database connection failed: " .
				mysqli_connect_error() .
				" (" . mysqli_connect_errno() . ")"
				);
	}
	$query = "SELECT production_date as year, COUNT(*) AS frequency FROM movie WHERE production_date >= $start_year AND production_date <= $end_year GROUP BY production_date ";
	$result = mysqli_query($connection, $query);
	if(!$result) {
		die("Database query: $query. Failed.");
	}
	for($data = array(); $row = mysqli_fetch_assoc($result); $data[]=$row);
	echo json_encode($data, JSON_PRETTY_PRINT);
?>
