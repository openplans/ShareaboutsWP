<?php
  global $post,$wpdb;

  $location = $wpdb->get_var("SELECT location FROM $wpdb->posts WHERE \"ID\" = '$post->ID'");
  $location = str_replace('(', '', $location);
  $location = str_replace(')', '', $location);

?>var map = new L.Map('map', {
      scrollWheelZoom: false
      });
var cloudmadeUrl = 'http://{s}.tile.cloudmade.com/03cdfe364cd44464ae4d126009e52117/997/256/{z}/{x}/{y}.png',
    cloudmadeAttrib = 'Map data &copy; 2011 OpenStreetMap contributors, Imagery &copy; 2011 CloudMade',
    cloudmade = new L.TileLayer(cloudmadeUrl, {
      maxZoom: 18,
      minZoom: 5
      });
var centerpoint = new L.LatLng(<?php echo $location; ?>);
map.setView(centerpoint, 12).addLayer(cloudmade);

var CustomIcon = L.Icon.extend({ iconUrl: '<?php echo get_template_directory_uri(); ?>/leaflet/images/marker-4d68e9.png' });
var icon = new CustomIcon();

var marker<?php echo $post->ID;?> = new L.Marker(new L.LatLng(<?php echo $location; ?>), {icon: icon});
map.addLayer(marker<?php echo $post->ID;?>);

// ui to make the map taller/wider
var center = new L.LatLng(<?php echo $location; ?>);
$('#map-container').append('<a href="#" id="map-h-sizer">&#9660;</a>');
$('#map-h-sizer').click(function(){
    $('#map-container').toggleClass('tall');
    $('#map-container.tall').find('#map-h-sizer').html('&#9650;');
    $('#map-container:not(.tall)').find('#map-h-sizer').html('&#9660;');
    map.invalidateSize().panTo(center); 
    return false;
});
$('#map-container').append('<a href="#" id="map-w-sizer">&#9668;</a>');
$('#map-w-sizer').click(function(){
    $('#map-container').toggleClass('wide');
    $('#map-container.wide').find('#map-w-sizer').html('&#9658;');
    $('#map-container:not(.wide)').find('#map-w-sizer').html('&#9668;');
    $('#map-container.wide').prependTo('#main');
    $('#map-container:not(.wide)').prependTo('#secondary');
    map.invalidateSize().panTo(center);
    return false;
});
