//animation for buttons 
(function($) {
    $(function() {
        var b_content_id_map={
            'trend_chart_button': 'tc_content',
            'histogram_button': 'h_content',
            'map_button': 'm_content'
        };
        var v_content_id_map={
            'trend_chart_button':'trend_chart',
            'histogram_button':'histogram',
            'map_button':'map'
        };
        var v_title_map={
            'trend_chart_button':'Trend Chart',
            'histogram_button':'Histogram',
            'map_button':'World Map'
        };
        $(".button_content").hide();
        $(".visual_content").hide();
        $(".visual_button").click(function(){

            var button_id = this.id;
            var b_content_id = b_content_id_map[button_id];
            var v_content_id = v_content_id_map[button_id];
            var v_title = v_title_map[button_id];
            $(".button_content_active").toggle("slow");
            $(".visual_active").toggle("slow");
            $(".button_content_active").removeClass('button_content_active');
            $("#"+b_content_id).addClass('button_content_active');
            $(".visual_active").removeClass('visual_active');
            $("#"+v_content_id).addClass('visual_active');
            $("#v_title h2").text(v_title);
            $("#"+b_content_id).toggle("slow");
            $("#"+v_content_id).toggle("slow");
        });
	var $loading = $('#loading').hide();
    }); 
})(jQuery);

//Event handlers for choosing data visualization types
$("#trend_chart_form").submit(function(event) 
 {
     /* stop form from submitting normally */
     event.preventDefault();

     /* get some values from elements on the page: */
     var $form = $( this ),
         $submit = $form.find( 'button[type="submit"]' ),
         start_value = $form.find( 'input[name="start_year"]' ).val(),
         end_value = $form.find( 'input[name="end_year"]' ).val(),
         url = $form.attr('action');

     /* Send the data using post */
     var posting = $.post( url, { 
                       start: start_value, 
                       end: end_value
                   });
	$submit.text("loading");
     posting.done(function( data )
     {
	$submit.text("submit");
        $("#trend_chart_result svg").empty();
        var data = JSON.parse(data);
        draw_trend_chart("#trend_chart_result",data);
        // $( "#trend_chart_result" ).html(data[0]["frequency"]);
     });
});

$("#histogram_form").submit(function(event) 
{
     /* stop form from submitting normally */
     event.preventDefault();
     
     /* get some values from elements on the page: */
     var $form = $( this ),
         $submit = $form.find( 'button[type="submit"]' ),
         genre_value = $form.find( 'input[name="genre"]' ).val(),
         url = $form.attr('action');

     /* Send the data using post */
     var posting = $.post( url, { 
                       genre: genre_value, 
                   });
	$submit.text("loading");
     posting.done(function( data )
     {
	$submit.text("submit");
        $("#histogram_result svg").empty();
        /* Put the results in a div */
        /* This is a drawing function implemented with d3*/
	//$("#histogram_result").html(data);
        var data = JSON.parse(data);
        //convert the frequency column from string to numeric
        for(var i = 0; i < data.length; i++) {
          data[i]["frequency"] = +data[i]["frequency"];
        }
        draw_histogram("#histogram_result", data);
     });
});


$("#map_form").submit(function(event) 
{
     /* stop form from submitting normally */
     event.preventDefault();

     /* get some values from elements on the page: */
     var $form = $( this ),
         $submit = $form.find( 'button[type="submit"]' ),
         year_value = $form.find( 'input[name="year"]' ).val(),
         location_value = $form.find('input[name="location"]').val(),
         url = $form.attr('action');
	
     /* Send the data using post */
     var posting = $.post( url, { 
                       year: year_value,
                       location: location_value
                   });
	$submit.text("loading");
     posting.done(function( data )
     { 
	$submit.text("submit");
         $("#hidden").hide();
         $("#map_result").empty();
         var data = JSON.parse(data);
         $("#lat").text(parseFloat(data["center"][0]));
         $("#lon").text(parseFloat(data["center"][1]));
         var fill_map = build_fill_map(data["data"].length);
         var bubble_data = build_bubble_data(fill_map, data["data"]);
         draw_map("map_result", fill_map, bubble_data);
     });
});


//draw functions for different visualization types
function draw_histogram(reference, data) {
    var svg = d3.select(reference+" svg"),
    margin = {top: 20, right: 20, bottom: 60, left: 50},
    width = +svg.attr("width") - margin.left - margin.right,
    height = +svg.attr("height") - margin.top - margin.bottom;

    var x = d3.scaleBand().rangeRound([0, width]).padding(0.1),
        y = d3.scaleLinear().rangeRound([height, 0]);

    var g = svg.append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

      x.domain(data.map(function(d) { return d.country; }));
      y.domain([0, d3.max(data, function(d) { return d.frequency; })]);

      g.append("g")
          .attr("class", "axis axis--x")
          .attr("transform", "translate(0," + height + ")")
          .call(d3.axisBottom(x))
	.selectAll("text")
		.attr("dx", "-2.5em")
		.attr("transform", function(d) {
			return "rotate(-65)"});

      g.append("g")
          .attr("class", "axis axis--y")
          .call(d3.axisLeft(y).ticks(10))
        .append("text")
          .attr("transform", "rotate(-90)")
          .attr("y", 6)
          .attr("dy", "0.71em")
          .attr("text-anchor", "end")
          .text("Frequency");

      g.selectAll(".bar")
        .data(data)
        .enter().append("rect")
          .attr("class", "bar")
          .attr("x", function(d) { return x(d.country); })
          .attr("y", function(d) { return y(d.frequency); })
          .attr("width", x.bandwidth())
          .attr("height", function(d) { return height - y(d.frequency); });

};
function draw_trend_chart(reference, data) {
  var svg = d3.select(reference+" svg"),
      margin = {top: 20, right: 20, bottom: 40, left: 50},
      width = +svg.attr("width") - margin.left - margin.right,
      height = +svg.attr("height") - margin.top - margin.bottom,
      g = svg.append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");

  var parseTime = d3.timeParse("%Y"),
      bisectDate = d3.bisector(function(d){return d.year}).left;

  var x = d3.scaleTime()
      .rangeRound([0, width]);

  var y = d3.scaleLinear()
      .rangeRound([height, 0]);

  var line = d3.line()
      .x(function(d) { return x(d.year); })
      .y(function(d) { return y(d.frequency); });

  for(var i = 0; i < data.length; i++) {
    data[i]["year"] = parseTime(data[i]["year"]);
    data[i]["frequency"] = +data[i]["frequency"];
  }
  x.domain(d3.extent(data, function(d) { return d.year; }));
  y.domain([0, d3.max(data, function(d) { return d.frequency; })]);

  g.append("g")
      .attr("class", "axis axis--x")
      .attr("transform", "translate(0," + height + ")")
      .call(d3.axisBottom(x).ticks(data.length))
	.selectAll("text")
		.attr("dx", "-1.8em")
		.attr("dy", "-0.5em")
		.attr("transform", function(d) {
			return "rotate(-90)"});

  g.append("g")
      .attr("class", "axis axis--y")
      .call(d3.axisLeft(y))
    .append("text")
      .attr("fill", "#000")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", "0.71em")
      .style("text-anchor", "end")
      .text("Number of movies filmed");

  g.append("path")
      .attr("class", "line")
      .attr("d", line(data));

  var focus = g.append("g")
      .attr("class", "focus")
      .style("display", "none");

  focus.append("circle")
      .attr("r", 4.5);

  focus.append("text")
      .attr("x", 9)
      .attr("dy", ".35em");

  g.append("rect")
      .attr("class", "overlay")
      .attr("width", width)
      .attr("height", height)
      .on("mouseover", function() { focus.style("display", null); })
      .on("mouseout", function() { focus.style("display", "none"); })
      .on("mousemove", mousemove);

  function mousemove() {
    var x0 = x.invert(d3.mouse(this)[0]),
        i = bisectDate(data, x0, 1),
        d0 = data[i - 1],
        d1 = data[i],
        d = x0 - d0.year > d1.year - x0 ? d1 : d0;
    focus.attr("transform", "translate(" + x(d.year) + "," + y(d.frequency) + ")");
    focus.select("text").text(d.frequency);
  }

};

function build_fill_map(num_bubbles) {
  var fill_map = {};
  fill_map["defaultFill"]="#ABDDA4";
  for(var i = 0; i < num_bubbles; i++) {
    fill_map[i] = randomColor();
  }
  return fill_map;
};

function build_bubble_data(fill_map, query_data) {
  var bubble_data = new Array();
  var magnify_size = 10;
  for(var i = 0; i < query_data.length; i++) {
    var temp = {};
    temp.name = query_data[i].locations;
    temp.radius = Math.min(parseInt(query_data[i].frequency)*magnify_size,80);
    temp.latitude = parseFloat(query_data[i].latitude);
    temp.longitude = parseFloat(query_data[i].longitude);
    temp.frequency = parseInt(query_data[i].frequency)
    temp.fillKey = i;
    bubble_data.push(temp);
  }
  return bubble_data;

};
function draw_map(reference, fills_m, bubble_data) {
  var zoom = new Datamap({
  element: document.getElementById(reference),
  scope: 'world',
  setProjection: function(element) {
    var lat = $("#lat").text();
    var lon = $("#lon").text();

    var projection = d3.geo.equirectangular()
      .center([lon,lat])
      .rotate([4.4, 0])
      .scale(400)
      .translate([element.offsetWidth / 2, element.offsetHeight / 2]);
    var path = d3.geo.path()
      .projection(projection);

    return {path: path, projection: projection};
  },
  fills: fills_m
  });
  zoom.bubbles(bubble_data,
  {
    popupTemplate: function(geo, data) {
      return "<div class='hoverinfo'>Location: " + data.name + "<br>" +"#Movies: " + data.frequency;
  }
  });
};
