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
     <script>
    /* global variables for access */ 
    var geocoder;
    var map;
    var heatmapData = []; //heat map


     /* collect data from form for processing */ 
    function prepData(){
      var info = new Object();
      info.location = document.getElementById("location").value;
      info.keyword = document.getElementById("keyword").value; 
      info.zoom = document.getElementById("zoom").value;
      console.log(info);

      /* send information for processing from Wisper API */
      var hits = getValues(info.keyword); 

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
        codeAddress(hits[index]);
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

     /* function to call values from wisper API */ 
     function getValues(keyword)
     {
      var hits = new Array(); 
      hits[0] = "New Orleans, Louisiana";
      hits[1] = "Dallas, Texas";
      hits[2] = "Austin, Texas";
      hits[3] = "Seattle, Washington"; 

      return hits; 
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

  function codeAddress(address) {
   
  
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
      
    </script>
  </head>
  <body onload="initialize()">
 <div id="map-canvas" style="width: 1000px; height: 600px;"></div>
  <div>
    <input id="keyword" type="textbox" placeholder="Keyword">
    <input id="location" type="textbox" placeholder="Location">
    <select id="zoom">
      <option value="3">Zoom: 1x</option>
      <option value="4">Zoom: 2x</option>
      <option value="5">Zoom: 3x</option>
      <option value="6">Zoom: 4x</option>
      <option value="10">Zoom: 5x</option>
  </select>
    <input type="button" value="Encode" onclick="prepData()">
  </div>
</body>
</html>