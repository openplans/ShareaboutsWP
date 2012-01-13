var map = new L.Map('map', {
      scrollWheelZoom: false
      });
var cloudmadeUrl = 'http://{s}.tile.cloudmade.com/03cdfe364cd44464ae4d126009e52117/997/256/{z}/{x}/{y}.png',
    cloudmadeAttrib = 'Map data &copy; 2011 OpenStreetMap contributors, Imagery &copy; 2011 CloudMade',
    cloudmade = new L.TileLayer(cloudmadeUrl, {
      maxZoom: 18,
      minZoom: 2
      });
    <?php 
    /* see if the user just added a point if so, center map 
       on that point otherwise, geolocate & center on that point */
    $location = $_POST['post_latlon'];
    $location = str_replace('LatLng(', '', $location);
    $location = str_replace(')', '', $location);
    if ( $location ) { ?>
      var mynewpoint = new L.LatLng(<?php echo $location; ?>); 
      map.setView(mynewpoint, 16).addLayer(cloudmade);
    <?php } else { ?>
      map.locateAndSetView(12).addLayer(cloudmade);
    <?php } ?>

var CustomIcon = L.Icon.extend({ iconUrl: '<?php echo get_template_directory_uri(); ?>/leaflet/images/marker-4d68e9.png' });
var icon = new CustomIcon();

<?php 

global $post,$wpdb;

$points = $wpdb->get_results("SELECT \"ID\", $wpdb->posts.post_title, $wpdb->posts.post_content, $wpdb->posts.comment_count, $wpdb->posts.post_date, $wpdb->posts.post_author, location FROM $wpdb->posts WHERE $wpdb->posts.post_type = 'point' AND post_status = 'publish';");
foreach($points as $point) { 
  //  print_r($point);
  if ( $point->ID && $point->location ) { ?>

    var marker<?php echo $point->ID;?> = new L.Marker(new L.LatLng<?php echo $point->location; ?>, {icon: icon});
    map.addLayer(marker<?php echo $point->ID;?>);
    marker<?php echo $point->ID;?>.bindPopup('<h6><a href="<?php echo get_permalink( $point->ID ); ?>"><?php echo htmlspecialchars($point->post_title, ENT_QUOTES); ?></a></h6><div class="post-meta">Added by <?php $user_info = get_userdata($point->post_author); echo $user_info->display_name ; ?> on <?php echo mysql2date("M j, Y", $point->post_date) ?></div><div class="post-content"><?php echo htmlspecialchars($point->post_content, ENT_QUOTES); ?></div>');

  <?php }
}

?>

$('#new-point').prepend('<p class="alignright"><a href="#" id="unconfirm"><span>&times;</span>Cancel</a></p>');
$('#add-a-point').click(function(e) {
  e.preventDefault();

  $(this).hide();
  $(this).parent().prepend('<a href="#" id="confirm-point-disabled" class="bttn">Add a point!</a>');
  $('#confirm-point-disabled').click(function() { return false; });

  var PlusIcon = L.Icon.extend({ iconUrl: '<?php echo get_template_directory_uri(); ?>/leaflet/images/marker-plus.png' });
  var plusicon = new PlusIcon();

  var mymarker = new L.Marker(map.getCenter(), {
    icon: plusicon, 
    draggable: true
  });
  map.addLayer(mymarker);

  mymarker.bindPopup('<div class="tooltip">Drag me to <br />a cool spot!</div>').openPopup().on('dragend', dragend);

  function dragend() {
    $('#confirm-point-disabled').attr("id","confirm-point").html("Confirm");

    var post_latlon = mymarker.getLatLng();
    $('#post_latlon').attr('value', post_latlon);


    $('#confirm-point').click(function(e) { 
      e.preventDefault();

      mymarker.bindPopup('<div class="tooltip">Confirmed!</div>').openPopup().dragging.disable();

      $(this).hide();
      $('.my-points').addClass('hidden');
      $('.my-points-empty').addClass('hidden');
      $('#new-point').removeClass('hidden');

      $('#unconfirm').click(function(e) { 
        e.preventDefault();

        $('.my-points').removeClass('hidden');
        $('.my-points-empty').removeClass('hidden');
        $('#new-point').addClass('hidden');
        $('#confirm-point').show();
        mymarker.bindPopup('<div class="tooltip">Drag me to <br />a cool spot!</div>').openPopup().dragging.enable().on('dragend', dragend);
      });

    });
  }

});
