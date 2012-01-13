<?php
/**
 * @package WordPress
 * @subpackage ShareaboutsWP
 */
?>
    <div id="secondary" class="widget-area">

      <?php if ( is_front_page() ) { ?>

          <?php if ( ! dynamic_sidebar( 'homesidebar' ) ) : ?>
          <?php endif; /* end sidebar widget area */ ?>

      <?php } elseif ( 'point' == get_post_type() && !is_archive() ) { ?>

          <div id="map-container">
            <div id="map"></div>
          </div>

          <aside role="complementary" class="widget widget_nearby_points">
            <h4 class="widget-title">Nearby Points</h4>
            <ul>
              <?php
              global $post,$wpdb;

              $centerpoint = $wpdb->get_var("SELECT location FROM $wpdb->posts WHERE \"ID\" = '$post->ID'");

              /* GiST nearest neighbor -- http://developer.postgresql.org/pgdocs/postgres/indexes-types.html */
              $nearby_points = $wpdb->get_results("SELECT \"ID\", $wpdb->posts.post_title, $wpdb->posts.comment_count, $wpdb->posts.post_date, $wpdb->posts.post_author, location FROM $wpdb->posts WHERE $wpdb->posts.post_type = 'point' AND post_status = 'publish' AND \"ID\" != '$post->ID' ORDER BY location <-> point $centerpoint LIMIT 3;");
              foreach($nearby_points as $point) { ?>
                  <li>
                    <a title="<?php echo htmlspecialchars($point->post_title, ENT_QUOTES); ?>" href="<?php echo get_permalink( $point->ID ); ?>"><?php echo htmlspecialchars($point->post_title, ENT_QUOTES); ?></a>
                    Added by <?php the_author($point->post_author); ?> on <?php echo mysql2date("M j, Y", $point->post_date) ?>
                    <span class="meta-separator">|</span>
                    <a href="<?php echo get_permalink( $point->ID ); ?>#comments">
                      <?php echo $point->comment_count; ?>
                      <?php 
                      if ( $point->comment_count == 1 ) {
                        echo "Comment";
                      } else {
                        echo "Comments";
                      } ?>
                    </a>
                  </li>
                <?php } ?>
            </ul>
          </aside>

          <?php if ( ! dynamic_sidebar( 'sidebar' ) ) : ?>
          <?php endif; /* end sidebar widget area */ ?>

      <?php } else { ?>

          <?php if ( ! dynamic_sidebar( 'sidebar' ) ) : ?>
          <?php endif; /* end sidebar widget area */ ?>

      <?php } ?>

    </div><!-- #secondary .widget-area -->