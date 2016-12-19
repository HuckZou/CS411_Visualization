<?php
	// $address = $_POST['address'];
    $year    = $_POST['year'];
    $address = $_POST['location'];
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

	$arrContextOptions=array(
	    "ssl"=>array(
	        "verify_peer"=>false,
	        "verify_peer_name"=>false,
	    ),
	);  

    $prepAddr = str_replace(' ','+',$address);
    $prepAddr = str_replace(',','+',$prepAddr);
    $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false',false, stream_context_create($arrContextOptions));
    $output= json_decode($geocode);
    $latitude = $output->results[0]->geometry->location->lat;
    $longitude = $output->results[0]->geometry->location->lng;
    $center_coord = array($latitude, $longitude);
	
	$query_size = 20;
	// select locations, count(*) from movie where locations is not null and production_date is not null and production_date = 2015 group by locations order by RAND() limit 5;
	//a query that randomly select elements from the db that meet the requirement
	$query = "SELECT locations, count(*) AS frequency FROM movie WHERE locations IS NOT NULL AND production_date IS NOT NULL AND production_date=$year GROUP BY locations ORDER BY RAND() LIMIT $query_size";
	$result = mysqli_query($connection, $query);
	if(!$result) {
		die("Database query: $query. Failed.");
	}
	$data = array();
	while($row = mysqli_fetch_assoc($result)) {
		$movie_addr = $row["locations"];
	    $prepAddr = str_replace(' ','+',$movie_addr);
	    $prepAddr = str_replace(',','+',$prepAddr);
	    $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false',false, stream_context_create($arrContextOptions));
	    $output= json_decode($geocode);
        $row["latitude"] = $output->results[0]->geometry->location->lat;
	    $row["longitude"] = $output->results[0]->geometry->location->lng;
	    $data[] = $row;
	}
	
	$total_data = array();
	$total_data["center"] = $center_coord;
	$total_data["data"] = $data;
	echo json_encode($total_data, JSON_PRETTY_PRINT);
?>
