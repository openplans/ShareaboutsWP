<?php
/**
 * @package WordPress
 * @subpackage ShareaboutsWP
 */
?>

  </div><!-- #main  -->
</div><!-- #page -->

<footer id="colophon" role="contentinfo" class="clearfix">
    <div id="site-credits">
      <p><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?> is awesome!</p>
    </div>
    <nav id="footer-nav" role="article">
      <?php wp_nav_menu( array( 'theme_location' => 'footer' ) ); ?>
    </nav>
</footer><!-- #colophon -->

<!-- Grab Google CDN's jQuery. Fall back to local if necessary -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script>!window.jQuery && document.write(unescape('%3Cscript src="<?php echo get_template_directory_uri(); ?>/js/jquery-1.4.4.min.js"%3E%3C/script%3E'))</script>

<script src="<?php echo get_template_directory_uri(); ?>/leaflet/leaflet.js"></script>

<script type="text/javascript">
  jQuery(function ($) {
    $(document).ready(function() {

      <?php 
      if ( is_page_template("page-map.php") ) { 
        locate_template( '/js/page-map-js-leaflet.php', true ); 
      } 
      elseif ( 'point' == get_post_type() ) { 
        locate_template( '/js/single-point-js-leaflet.php', true ); 
      } ?>

    });
  });
</script>

<?php wp_footer(); ?>

</body>
</html>