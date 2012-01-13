<?php
/**
 * @package WordPress
 * @subpackage ShareaboutsWP
 */

/**
 * Make theme available for translation
 * Translations can be filed in the /languages/ directory
 */
load_theme_textdomain( 'shareabouts', TEMPLATEPATH . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
  require_once( $locale_file );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
  $content_width = 600;

/**
 * Remove code from the <head>
 */
remove_filter( 'the_content', 'capital_P_dangit' ); // Get outta my Wordpress codez dangit!
remove_filter( 'the_title', 'capital_P_dangit' );
remove_filter( 'comment_text', 'capital_P_dangit' );

// Hide the version of WordPress you're running from source and RSS feed // Want to JUST remove it from the source? Try: remove_action('wp_head', 'wp_generator');
function hcwp_remove_version() {return '';}
add_filter('the_generator', 'hcwp_remove_version');

// Remove the comment inline css
function remove_recent_comments_style() {
  global $wp_widget_factory;
  remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'remove_recent_comments_style' );

/**
 * This theme uses wp_nav_menus() for the header menu, utility menu and footer menu.
 */
register_nav_menus( array(
  'primary' => __( 'Primary Menu', 'shareabouts' ),
  'footer' => __( 'Footer Menu', 'shareabouts' ),
) );

/** 
 * Add default posts and comments RSS feed links to head
 */
add_theme_support( 'automatic-feed-links' );

/**
 * This theme uses post thumbnails
 */
add_theme_support( 'post-thumbnails' );

/**
 * Disable the admin bar in 3.1
 */
show_admin_bar( false );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function shareabouts_widgets_init() {
  register_sidebar( array (
    'name' => __( 'Default Sidebar', 'shareabouts' ),
    'id' => 'sidebar',
    'before_widget' => '<aside id="%1$s" class="widget %2$s" role="complementary">',
    'after_widget' => "</aside>",
    'before_title' => '<h4 class="widget-title">',
    'after_title' => '</h4>',
  ) );
  register_sidebar( array (
    'name' => __( 'Homepage Sidebar', 'shareabouts' ),
    'id' => 'homesidebar',
    'before_widget' => '<aside id="%1$s" class="widget %2$s" role="complementary">',
    'after_widget' => "</aside>",
    'before_title' => '<h4 class="widget-title">',
    'after_title' => '</h4>',
  ) );
}
add_action( 'init', 'shareabouts_widgets_init' );

/**
 * Remove senseless dashboard widgets for non-admins. (Un)Comment or delete as you wish.
 */
function remove_dashboard_widgets() {
  global $wp_meta_boxes;

  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // Plugins widget
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // WordPress Blog widget
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // Other WordPress News widget
}
if (!current_user_can('manage_options')) {
  add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );
}

/**
 * Custom Post Type: Point
 */
add_action('init', 'create_point_cpt');
function create_point_cpt() {
  $labels = array(
    'name' => _x('Points', 'post type general name'),
    'singular_name' => _x('point', 'post type singular name'),
    'add_new' => _x('Add New', 'point'),
    'add_new_item' => __('Add New Point'),
    'edit_item' => __('Edit Point'),
    'new_item' => __('New Point'),
    'view_item' => __('View Point'),
    'search_items' => __('Search Points'),
    'not_found' =>  __('No points found'),
    'not_found_in_trash' => __('No points found in Trash'),
    'parent_item_colon' => '',
    'menu_name' => 'Points'
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => array("slug" => "point"),
    'capability_type' => 'post',
    'has_archive' => false,
    'hierarchical' => false,
    'menu_position' => 5,
    'supports' => array('title','editor','author','comments')
  ); 
  register_post_type('point',$args);
}

/* Add meta box: location */
function location_add_custom_box() {
  add_meta_box( 'thelocation', 'Location', 'location_inner_custom_box', 'point', 'side', 'default');
}
add_action( 'add_meta_boxes', 'location_add_custom_box' );

function location_inner_custom_box( $post ) {
  global $post,$wpdb;

  $location = $wpdb->get_var("SELECT location FROM $wpdb->posts WHERE \"ID\" = '$post->ID'");
  $location = str_replace('(', '', $location);
  $location = str_replace(')', '', $location);
  $location = explode(',', $location);
  $location_lat = $location[0];
  $location_lon = $location[1];

  echo '<p><label for="location_lat">Latitude:</label><input id="location_lat" name="location_lat" value="';
  if( $location_lat ) { echo $location_lat; };
  echo '" /></p>';
  
  echo '<p><label for="location_lon">Longitude:</label><input id="location_lon" name="location_lon" value="';
  if( $location_lon ) { echo $location_lon; };
  echo '" /></p>';
}

/* When the post is saved, saves our custom data */
function location_save_postdata( $post_id ) {
  global $wpdb,$post;
  if( $_POST ) {
    $latitude = $_POST['location_lat'];
    $longitude = $_POST['location_lon'];
    $update = $wpdb->query("UPDATE $wpdb->posts SET location = '$latitude,$longitude' WHERE \"ID\" = '$post->ID'");
  }
}
add_action( 'save_post', 'location_save_postdata' );
/* ----------------------------------------------------------- end CPT: Point */

/*
 * CPT icons
 */
add_action( 'admin_head', 'cpt_icons' );
function cpt_icons() {
  ?>
  <style type="text/css" media="screen">
      #menu-posts-point .wp-menu-image {
        background: url(<?php bloginfo('template_url') ?>/cpt-icons/marker.png) no-repeat 6px -17px !important;
      }
      #menu-posts-point:hover .wp-menu-image, #menu-posts-shareaboutstemplaterobot.wp-has-current-submenu .wp-menu-image {
        background-position:6px 7px!important;
      }
  </style>
  <?php 
}

/*
 * Theme Setup
 *
 */
function shareaboutswp_init() {

  // add locations to database
  global $wpdb;

  $location_exists = $wpdb->get_var("SELECT count(*) FROM information_schema.COLUMNS WHERE TABLE_NAME = '$wpdb->posts' AND COLUMN_NAME = 'location'");
  if ( !$location_exists ) {
    $wpdb->query("ALTER TABLE $wpdb->posts ADD COLUMN location point");
  }

}
add_action( 'after_setup_theme', 'shareaboutswp_init' );

/*
 * Recent Points Widget
 *
 */
function register_points_widget(){
  $options = array('description' => 'Shows a list of recenty submitted points.');
  wp_register_sidebar_widget('recent_points_1','Recent Points','recent_points_display',$options);
}
function recent_points_display($theme_info){
  echo $theme_info['before_widget'];
  echo $theme_info['before_title'] . "Recent Points" . $theme_info['after_title'];

  $the_points = new WP_Query(array('posts_per_page' => '4', 'post_type' => 'point', 'post_status' => 'publish' ));
  if ($the_points->have_posts()) : 
  ?><ul class="points">
    <?php while ($the_points->have_posts()) : $the_points->the_post(); ?>
    <li>
      <h4 class="title"><a class="link" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'shareabouts' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
      <div>
        <span class="comments-link"><?php comments_popup_link( __( '0 Replies', 'shareabouts' ), __( '1 Reply', 'shareabouts' ), __( '% Replies', 'shareabouts' ) ); ?></span>
        <span class="votes-link"><a href="#">3 Votes</a></span>
      </div>
      <div>
        <span class="date">Added by <?php the_author(); ?> on <?php echo get_the_date('j/n/Y'); ?></span>
      </div>
    </li>
    <?php endwhile; ?>
  </ul><?php
  endif;

  echo $theme_info['after_widget'];
}
add_action('init','register_points_widget');


/*
 * Add Points to Author Archives
 *
 */
function __set_wiki_for_author( &$query ) {
  if ( $query->is_author ) $query->set( 'post_type', array('post','point') );
  remove_action( 'pre_get_posts', '__set_wiki_for_author' ); // run once!
}
add_action( 'pre_get_posts', '__set_wiki_for_author' );

?>