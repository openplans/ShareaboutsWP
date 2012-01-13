<?php
/**
 * @package WordPress
 * @subpackage ShareaboutsWP
 */
?><!DOCTYPE html>
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html <?php language_attributes(); ?> class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->

<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="chrome=1">

<title><?php
  /*
   * Print the <title> tag based on what is being viewed.
   */
  global $page, $paged;

  wp_title( '|', true, 'right' );

  // Add the blog name.
  bloginfo( 'name' );

  // Add the blog description for the home/front page.
  $site_description = get_bloginfo( 'description', 'display' );
  if ( $site_description && ( is_home() || is_front_page() ) )
    echo " | $site_description";

  // Add a page number if necessary:
  if ( $paged >= 2 || $page >= 2 )
    echo ' | ' . sprintf( __( 'Page %s', 'shareabouts' ), max( $paged, $page ) );

  ?></title>
  <meta name="description" content="">
  <meta name="author" content="">
  <!--  Mobile Viewport Fix -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
  <!-- Place favicon.ico and apple-touch-icon.png in the images folder -->
  <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico">
  <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/images/apple-touch-icon.png"><!--60X60-->
  
  <link rel="profile" href="http://gmpg.org/xfn/11" />
  <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); echo '?' . filemtime( get_stylesheet_directory() . '/style.css'); ?>" type="text/css" media="screen, projection" />

  <link href='http://fonts.googleapis.com/css?family=Shadows+Into+Light|Satisfy' rel='stylesheet' type='text/css'>

  <?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
  
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/leaflet/leaflet.css" />
  <!--[if lte IE 8]><link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>leaflet/leaflet.ie.css" /><![endif]-->

  <?php wp_head(); ?>
  </head>
  
  <body <?php body_class(); ?>>
  <div id="page" class="hfeed clearfix">
    <header id="branding" role="banner">
        <hgroup>
          <h1 id="site-title"><span><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span></h1>
          <h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
        </hgroup>
        
        <nav id="access" role="article">
          <div class="skip-link visuallyhidden"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'shareabouts' ); ?>"><?php _e( 'Skip to content', 'shareabouts' ); ?></a></div>
          <?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
        </nav><!-- #access -->

        <p id="user-nav"><?php if ( is_user_logged_in() ) { ?>Hello, <?php global $current_user; get_currentuserinfo(); echo $current_user->display_name; ?>. <span class="loginout"><a href="<?php echo wp_logout_url( home_url() ); ?>" title="Logout">Logout</a></span><?php } ?></p>
    </header><!-- #branding -->
  
  
    <div id="main">