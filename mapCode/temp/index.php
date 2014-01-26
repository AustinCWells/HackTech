<!DOCTYPE html>
<html>
  <head>
    <style>
      #map_canvas {
        width: 1000px;
        height: 600px;
      }
    </style>
    <script src="http://maps.googleapis.com/maps/api/js?libraries=geometry,visualization&sensor=false"></script>
     <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
	</script>

     <script>
    
    /* global variables for access */ 
    var geocoder;
    var map;
    var heatmapData = []; //heat map
    var latLngs = new Array(); 
    var markers = [];
    var iterator = 0;


     /* collect data from form for processing */ 
    function prepData(){
      var info = new Object();
      info.location = document.getElementById("location").value;
      info.keyword = document.getElementById("keyword").value; 
      info.zoom = document.getElementById("zoom").value;
      console.log(info);

      /* send information for processing from Wisper API */
      var hits = getValues(info.keyword); 
      console.log(hits);
      
      /* initialize map*/
      map.setZoom(parseInt(info.zoom)); 
      var latLong = getLatLong(info.location);
      console.log(latLong);
      map.setCenter(new google.maps.LatLng(latLong.lat, latLong.lng));

      /* place hot spots */
      placeHotSpots(hits);

     }
    function placeHotSpots(hits)
    {
      for (index = 0; index < hits.length; ++index) {
        console.log(hits[index]);
        codeHeatMap(hits[index]);
}
    }

     /*use geocoding to get the lattitude and longitude*/
     function getLatLong(location)
     {
      location = location.replace(/ /g,"+");
      var xmlHttp = null;
      var theUrl ='http://maps.googleapis.com/maps/api/geocode/json?address='+ location + '&sensor=false';
      console.log(theUrl);
      xmlHttp = new XMLHttpRequest();
      xmlHttp.open( "GET", theUrl, false );
      xmlHttp.send( null );
      var response = xmlHttp.responseText;
      var parsedResponse = JSON.parse(response)
      console.log(parsedResponse.results[0].geometry.location);
      return parsedResponse.results[0].geometry.location; 
     }

     /* function to call values from whisper API */ 
     function getValues(keyword)
     {
     	var hits = new Array();
     	var serverContents;
      $.ajaxSetup({async:false});
      $.post("fetch.php", {query: keyword}, function(result)
      {
        serverContents = jQuery.parseJSON(result);
      });
      // $.ajax({
      // 		type:"POST",
      // 		url:"fetch.php",
      // 		data:{query: keyword},
      //     dataType:"json",
      //     async:false,
      // 		success:function(result){
      // 		  serverContents = jQuery.parseJSON(result);
      // 		}      		
      // 	});
      	for(var key in serverContents)
      		hits.push(serverContents[key]);
      	console.log("Hits: " + hits);
      	console.log("Server Contents: " + serverContents);
      	return hits; 
     }

      function drop() {
        for (var i = 0; i < latLngs.length; i++) {
          setTimeout(function() {
            addMarker();
          }, i * 200);
        }
        iterator=0; 
      }

      function addMarker() {
        markers.push(new google.maps.Marker({
          position: latLngs[iterator],
          map: map,
          draggable: false,
          animation: google.maps.Animation.DROP
        }));
        iterator++;
      }

      function startFeed()
      {

          window.setInterval(function(){
            GO()
      }, 2000);

      }

      function GO(){
        var cities = fetchFeed();
        fillLatLngArray(cities);
        drop();
        console.log(latLngs); 
        LatLngs = []; 
      }


      function fetchFeed()
       {
        var hits = new Array(); 
        hits[0] = "New Orleans, Louisiana";
        hits[1] = "Dallas, Texas";
        hits[2] = "Austin, Texas";
        hits[3] = "Seattle, Washington"; 
        hits[4] = "Newyork, newyork";
        hits[5] = "Michigan";
        hits[6] = "Florida"; 
        hits[7] = "California";
        hits[8] = "Oregon";

        return hits; 
       }

      function fillLatLngArray(cities)
      {
       
        for(var i = 0; i < cities.length; i++)
        {
          var currentLocation = getLatLong(cities[i]);
          latLngs.push(new google.maps.LatLng(currentLocation.lat, currentLocation.lng));
        }
        
      }    

      function initialize() {
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(39.8282, -98.5795);
        var mapOptions = {
          zoom: 4,
          center: latlng
        }
        map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
      }

  function codeHeatMap(address) {
   
  console.log(address);
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        var weightedLoc ={
         location: results[0].geometry.location,
         weight: 0.2
         }//heat map
        heatmapData.push(weightedLoc); //push info to heat map 
        var heatmap = new google.maps.visualization.HeatmapLayer({
          data: heatmapData,
          dissipating: false,
          radius: 1,
          opacity: 0.1,
          map: map
        });
        // var marker = new google.maps.Marker({
        //     map: map,
        //     position: results[0].geometry.location,
        //     icon: getCircle()
        // }); 
       
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });

     
  }

  function codeLiveStream(address){
     geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location,
            icon: getCircle()
        }); 
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }


  function getCircle() {
  var circle = {
    path: google.maps.SymbolPath.CIRCLE,
    fillColor: 'red',
    fillOpacity: .2,
    scale: 30,
    strokeColor: 'white',
    strokeWeight: .5
  };
  return circle;
}
      
google.maps.event.addDomListener(window, 'load', initialize);
    </script>
  </head>
  <body onload="initialize()">
 <div id="map-canvas" style="width: 1000px; height: 600px;"></div>
  <div>
    <input id="keyword" type="textbox" placeholder="Keyword">
    <input id="location" type="textbox" placeholder="Location" value="Dallas">
    <select id="zoom">
      <option value="3">Zoom: 1x</option>
      <option value="4">Zoom: 2x</option>
      <option value="5">Zoom: 3x</option>
      <option value="6">Zoom: 4x</option>
      <option value="10">Zoom: 5x</option>
  </select>
    <input type="button" value="Encode" onclick="prepData()">
    <input type="button" value="Stream" onclick="startFeed()">
  </div>
</body>
</html>