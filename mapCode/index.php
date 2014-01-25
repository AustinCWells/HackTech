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

      var geocoder;
      var map;
      var heatmapData = []; //heat map
      function initialize() {
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(39.8282, -98.5795);
        var mapOptions = {
          zoom: 4,
          center: latlng
        }
        map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
      }

  function codeAddress() {
   
    var address = document.getElementById("address").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        // var weightedLoc ={
        //  location: results[0].geometry.location,
        //  weight: 0.5
        //  }//heat map
        // heatmapData.push(weightedLoc); //push info to heat map 
        // var heatmap = new google.maps.visualization.HeatmapLayer({
        //   data: heatmapData,
        //   dissipating: false,
        //   radius: 2,
        //   opacity: 0.3,
        //   map: map
        // });
         map.setCenter(results[0].geometry.location);
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
      
    </script>
  </head>
  <body onload="initialize()">
 <div id="map-canvas" style="width: 1000px; height: 600px;"></div>
  <div>
    <input id="address" type="textbox" value="Sydney, NSW">
    <input type="button" value="Encode" onclick="codeAddress()">
  </div>
</body>
</html>