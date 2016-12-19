<?php
	// collection users inputs
    $genre = strtolower($_POST['genre']);
    //$country   = $_POST['country'];
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
	// Build query
	if($genre ==='all'){
		$query = "SELECT genre AS country, COUNT(*) AS frequency FROM movie WHERE genre IS NOT NULL GROUP BY genre ";
	} else {
		$genre_arr = explode(" ", $genre);
		$condition = "(";
		foreach($genre_arr as $value) {
			$condition .= " genre ="."'". $value."'"." or ";
		}
		$condition = substr($condition, 0, -3);
		$condition .= ")";
		$query = "SELECT genre AS country, COUNT(*) AS frequency FROM movie WHERE genre IS NOT NULL AND" .$condition. "GROUP BY genre ";
	}
	$result = mysqli_query($connection, $query);
	if(!$result) {
		die("Database query: $query. Failed.");
	}
	for($data = array(); $row = mysqli_fetch_assoc($result); $data[]=$row);
	echo json_encode($data, JSON_PRETTY_PRINT);
?>
