<!DOCTYPE HTML>
<html>
	<head>
		<title>DataVisual</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<link rel="stylesheet" href="data_visual/css/dv.css">
	</head>

	<body class="homepage">
		<div id="page-wrapper">

			<!-- Header -->
				<div id="header-wrapper">
					<header id="header" class="container">

						<!-- Logo -->
							<div id="logo">
								<h1><a href="index.php">Smarvie</a></h1>
							</div>

						<!-- Nav -->
							<nav id="nav">
								<ul>
									<li><a href="index.php">Welcome</a></li>
									<li class="current"><a href="datavisual.html">Data Visualization</a></li>
									<li><a href="select.php">What's UP!</a></li>
									<!-- <li><a href="no-sidebar.html">No Sidebar</a></li> -->
									<li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
									<li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
									<li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
									<li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
									<li></li><li>
									
								</ul>
							</nav>
							<nav class="main">
							<ul>
								<li class="search">
									<a class="fa-search" href="#search">Search</a>
									<form id="search" method="get" action="MovieInfo.php">
										<input type="text" name="SearchMovie" placeholder="Search" />
									</form>
								</li>

								<!-- <li class="menu">
									<a class="fa-bars" href="#menu">Menu</a>
								</li> -->
							</ul>
						</nav>
					</header>
				</div>
				<!-- Menu -->
					<section id="menu">

						<!-- Search -->
							<section>
								<form class="search" method="get" action="#">
									<input type="text" name="query" placeholder="Search" />
								</form><!-- 
							</section>

						Links
							<section> -->
							<br>
								<ul>
									<li>
										<a href="#">
											<h4>Account Information</h4>
											<p>Description1</p>
										</a>
									</li>
									<li>
										<a href="#">
											<h4>Link 2</h4>
											<p>Description2</p>
										</a>
									</li>
									<li>
										<a href="#">
											<h4>Link 3</h4>
											<p>Description3</p>
										</a>
									</li>
									<li>
										<a href="#">
											<h4>Link 4</h4>
											<p>Description4</p>
										</a>
									</li>
								</ul>
							
								<ul class="actions vertical">
									<li><a href="#" class="button">Sign In</a></li>
								</ul>
							</section>

					</header>
				</div>

			<!-- Main -->
				<div id="main-wrapper">
					<div class="container">
						<div class="row 200%">
							<div class="4u 12u$(medium)">
								<div id="sidebar">

									<!-- Sidebar -->
										<section>
											<h3>Choose a visualization type</h3>
											<button id="trend_chart_button" class="visual_button">Trend Chart</button><br>
											<div id="tc_content" class="button_content">
												<p>This data visualization method allows users to make a plot of the number of movies filmed in each year. By specifying the range of the years, the users are able to see the trend of how many movies are produced along the years.</p>
											</div>
											<button id="histogram_button" class="visual_button">Histogram</button><br>
											<div id="h_content" class="button_content">
												<p>This data visualization method plots a histogram based on the number of movies based on genre. Users can specify the genres of the movies to be included in the graph.</p>
											</div>
											<button id="map_button" class="visual_button">Map</button><br>
											<div id="m_content" class="button_content">
												<p>This data visulization method plots the number of films produced by a certain location in a given year. The larger the circle is the more the movies are. Since there are many movies in the database, we only limited our result to include 100 movies.</p>
											</div>
										</section>
								</div>
							</div>
							<div class="8u 12u$(medium) important(medium)">
								<div id="content">
									<!-- Content -->
									<!-- class="visual_active" -->
									<div id="v_title">
										<h2>Nothing to show yet</h2><br>
									</div>
									<div id="trend_chart" class="visual_content">
										<form id="trend_chart_form" action="data_visual/php/dvquery_tc.php" method="POST">
											<h4>Start Year</h4>
											<input type="text" name="start_year">
											<h4>End Year</h4>
											<input type="text" name="end_year">
											<button type="submit">Submit</button>
										</form>
										<div id="trend_chart_result">
											<svg width="700" height="500">
										</div>
									</div>
									<div id="histogram" class="visual_content">
										<form id="histogram_form" action="data_visual/php/dvquery_h.php" method="POST">
											<h4>Genre</h4>
											<label>Genres of movies such as action, comedy, horror, etc. Please separate them by space.</label>
											<input type="text" name="genre">
											<button type="submit">Submit</button>
										</form>
										<div id="histogram_result">
											<svg width="700" height="500"></svg>
										</div>
									</div>
									<div id="map" class="visual_content">
										<form id="map_form" action="data_visual/php/dvquery_m.php" method="POST">
											<h4>Year</h4>
											<input type="text" name="year">
											<h4>Location</h4>
											<input type="text" name="location">
											<button type="submit">Submit</button>
										</form>
										<div id="map_result" style="position: relative; width: 700px; height: 600px;">
										</div>
										<div id="hidden">
											<div id="lat"></div>
											<div id="lon"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		<!-- Scripts -->

			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="data_visual/js/d3.v4.min.js"></script>
			<script type="text/javascript">var d3version4=d3;</script>
			<script src="//cdnjs.cloudflare.com/ajax/libs/d3/3.5.3/d3.min.js"></script>
			<script type="text/javascript">
				var d3version3=d3;
				var testmsg = "aaaaa";
				d3=d3version3;
				$(".visual_button").click(function(){
					var button_id = this.id;
					
		            if (button_id==="map_button") {d3=d3version3;} else {d3=d3version4;}
				});
			</script>
			<script src="data_visual/js/topojson.min.js"></script>
			<script src="data_visual/js/datamaps.world.min.js"></script>
			<script src="data_visual/js/randomcolor.min.js"></script>
			<script src="data_visual/js/customized.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>


	</body>
</html>


