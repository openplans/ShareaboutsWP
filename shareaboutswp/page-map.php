<?php
/**
 * Template Name: Map
 * Description: UI for adding and viewing points
 *
 * @package WordPress
 * @subpackage ShareaboutsWP
 */

$postTitle = $_POST['post_title'];
$post = $_POST['post'];
$postPointType = $_POST['post_pointtype'];
$submit = $_POST['submit'];
$location = $_POST['post_latlon'];
$location = str_replace('LatLng(', '', $location);
$location = str_replace(')', '', $location);

if(isset($submit)){
  global $user_ID;
  
  $new_post = array(
    'post_title' => $postTitle,
    'post_content' => $post,
    'post_status' => 'publish',
    'post_date' => date('Y-m-d H:i:s'),
    'post_author' => $user_ID,
    'post_type' => 'point',
    'tax_input' => array( 'pointtype' => array( $postPointType ) )
  );

  $newPostID = wp_insert_post($new_post);

  $update = $wpdb->query("UPDATE $wpdb->posts SET location = '$location' WHERE \"ID\" = '$newPostID'");

  $thankyoumessage = 'show';
}

get_header(); ?>

    <div id="map-container">
      <div id="map"></div><?php 
      if ( $thankyoumessage == 'show' ) {
        echo '<p id="thankyoumessage">Sweet! Your point has been added.</p>';
      } ?>
    </div>

    <div id="map-ui">

      <?php if ( is_user_logged_in() ) { ?>

        <a href="#" id="add-a-point" class="bttn">Add a point!</a>
        <?php
        global $current_user;

        get_currentuserinfo();

        $my_points = new WP_Query( 
          array( 
            'post_type' => 'point',
            'author' => $user_ID,
            'posts_per_page' => '20'
          ) 
        );
        if ( $my_points->have_posts() ) : ?>
        <div class="my-points">
          <h3>My Points</h3>
          <ul class="points">
          <?php while ( $my_points->have_posts() ) : $my_points->the_post(); ?>
            <li>
              <h4 class="title"><a class="link" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'shareabouts' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
              <div>
                <?php 
                $point_terms = wp_get_object_terms($post->ID, 'pointtype');
                if(!empty($point_terms)){
                  if(!is_wp_error( $point_terms )){
                    echo '<span class="taxonomy">';
                    foreach($point_terms as $term){
                      echo $term->name; 
                    }
                    echo '</span>';
                  }
                }
                ?>
                <span class="comments-link"><?php comments_popup_link( __( '0 Replies', 'shareabouts' ), __( '1 Reply', 'shareabouts' ), __( '% Replies', 'shareabouts' ) ); ?></span>
              </div>
              <div>
                <span class="date">Added on <?php echo get_the_date('j/n/Y'); ?></span>
              </div>
            </li>
          <?php endwhile; ?>
          </ul>
        </div>
        <?php else: ?>
        <p class="my-points-empty">Sadface&hellip; :( <br />You haven't added any points. C'mon, it's easy!</p>
        <?php endif; ?>

        <form id="new-point" action="" method="post" class="hidden">
          <h3>Let's add some more information&hellip;</h3>
          <p><label for="post_title" class="hidden">Title</label><input name="post_title" type="text" placeholder="Title" /></p>
          <p><label for="post" class="hidden">Comment</label><input name="post" type="text" placeholder="Comment" /></p>
          <p><input id="post_latlon" name="post_latlon" type="text" readonly="readonly" /></p>
          <p><?php
          $terms = get_terms( 'pointtype', array(
            'hide_empty' => 0
          ));
          $count = count($terms);
          if ( $count > 0 ){
            echo '<select id="post_pointtype" name="post_pointtype">';
            foreach ( $terms as $term ) {
              echo '<option value="' . $term->slug . '">' . $term->name . '</option>';
            }
            echo "</select>";
          }
          ?></p>
          <input name="submit" type="submit" value="Submit" class="bttn" />
          <?php wp_nonce_field( 'new-point' ); ?>
        </form>

      <?php } else { ?> 

        <h2 class="call-to-action"><span class="nowrap">Sign in.</span> <span class="nowrap">Add points.</span></h2>

        <?php if (!(current_user_can('level_0'))){ ?>
        <form action="<?php echo get_option('home'); ?>/wp-login.php" method="post" class="clearfix">
          <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
          <p><label for="log" class="hidden">Username</label><input type="text" name="log" id="log" value="<?php echo wp_specialchars(stripslashes($user_login), 1) ?>" size="20" placeholder="Username" /></p>
          <p><label for="pwd" class="hidden">Password</label><input type="password" name="pwd" id="pwd" size="20" placeholder="Password" /></p>
          <input id="sign-in" type="submit" name="submit" value="Sign In" class="bttn" />
          <p class="remember-me"><input name="rememberme" id="rememberme" type="checkbox" checked="checked" value="forever" /> <label for="rememberme">Remember Me</label></p>
        </form>
        <?php wp_register('<p class="register">Need an account? ', '</p>'); ?> 
        <p class="lost"><a href="<?php echo get_option('home'); ?>/wp-login.php?action=lostpassword">Reset Password</a></p>
        <?php }?>

      <?php } ?> 

    </div>

		<div id="primary">
			<div id="content">

				<?php the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'shareabouts' ), 'after' => '</div>' ) ); ?>
						<?php edit_post_link( __( 'Edit', 'shareabouts' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-<?php the_ID(); ?> -->

				<?php if ( comments_open() ) { comments_template( '', true ); } ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>