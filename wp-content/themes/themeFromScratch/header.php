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
    <?php wp_head(); ?>
</head>




<div id="app" class="container">
       <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
           <div class="container-fluid">
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
               <ul class="navbar-nav">
                   <li>
                       <?php
                       $args = array(
                           'theme_location' => 'primary'
                       );
                       ?>
                       <?php wp_nav_menu($args); ?>
                   </li>
               </ul>
           </div>
       </nav>
</div>











