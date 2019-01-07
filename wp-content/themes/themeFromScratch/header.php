<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php wp_head();?>
</head>

  <nav class="navbar navbar-expand-sm bg-light">
      <div class="navbar-header">
          <?php if(has_custom_logo()) {
              the_custom_logo();
          } else { ?>
              <a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a>
              <?php
          }
          ?>
          <h5> <?php bloginfo('description'); ?></h5>
      </div>

      <?php

       //  print_r( site_url(). '/wp-content/plugins/wp_ecommerce/jsPage.js');die;
       //  print_r(get_template_directory_uri() . '/jsPage.js');die;
      // echo dirname( __FILE__ ) ;
      // print_r(get_template_directory_uri().'/thankyou-template.php');die;
//      print_r(get_home_url());die;
//      print_r(get_author_posts_url( get_current_user_id() ));die;
//      && get_nav_menu_locations()['primary']=='5'
//      print_r(get_nav_menu_locations());die;
//      echo admin_url();//die;
//      echo get_template_directory_uri();
//      echo get_stylesheet_uri();
    //  echo site_url().'/login/';
/*    $current_user =  wp_get_current_user();
      $menuLocations = get_nav_menu_locations(); // Get our nav locations (set in our theme, usually functions.php)
      echo"<pre>";
      print_r($current_user);
      die;*/
      //print_r( get_user_meta(1, 'comment_shortcuts', true ));

      $args = array(
              'theme_location' => 'primary',
              'container' => 'ul',
              'menu_class' => 'nav nav-tabs menu ml-auto',
          );
          wp_nav_menu($args);
      ?>
  </nav>









































