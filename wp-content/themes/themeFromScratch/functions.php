<?php

//Load bootstrap css and js
function bootstrapstarter_enqueue_styles() {
    wp_register_style('bootstrap', get_template_directory_uri() . '/bootstrap/css/bootstrap.min.css' );
    $dependencies = array('bootstrap');
    wp_enqueue_style( 'bootstrapstarter-style', get_stylesheet_uri(), $dependencies );
}
function bootstrapstarter_enqueue_scripts() {
    $dependencies = array('jquery');
    wp_enqueue_script('bootstrap', get_template_directory_uri().'/bootstrap/js/bootstrap.min.js', $dependencies, '4.9.8', true );
}
add_action( 'wp_enqueue_scripts', 'bootstrapstarter_enqueue_styles' );
add_action( 'wp_enqueue_scripts', 'bootstrapstarter_enqueue_scripts' );

// show the title in tab
add_theme_support( 'title-tag' );

//show featured image
add_theme_support( 'post-thumbnails' );

/*function myEndSession() {
     session_destroy ();
}
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');*/

//includes javascript page and Ajax used in JS.
function my_theme_scripts_function() {
    wp_enqueue_script( 'myscript', get_template_directory_uri() . '/jsPage.js');
    wp_localize_script( 'myscript', 'ajax_params',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('wp_enqueue_scripts','my_theme_scripts_function');



//checks if user exist exist and redirects to checkout else returns the login page
function userlogin(){
    if($_POST){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $login_array = array();
        $login_array['user_login'] = $username;
        $login_array['user_password'] = $password;
        $verify_user = wp_signon($login_array,true);

        if(!is_wp_error($verify_user)){
            echo json_encode('success');
        }else{
            echo json_encode('fail');
        }
        die;
    }
}
add_action("wp_ajax_userlogin","userlogin");
add_action("wp_ajax_nopriv_userlogin","userlogin");

//custom post for corosal
function create_post_type() {
    register_post_type( 'carosal',
        array(
            'labels' => array(
                'name' => __( 'Carosal slider' )
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail')
        )
    );
}
add_action( 'init', 'create_post_type' );

//left and right side bar in the theme
function my_custom_sidebar() {
    register_sidebar(
        array (
            'name' => ( 'Left Sidebar' ),
            'id' => 'left-side-bar',
            'description' => __( 'Custom Left Sidebar', 'your-theme-domain' ),
            'before_widget' => '<div>',
            'after_widget' => "</div>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );
    register_sidebar(
        array (
            'name' => ( 'Right Sidebar' ),
            'id' => 'right-side-bar',
            'description' => __( 'Custom Right Sidebar', 'your-theme-domain' ),
            'before_widget' => '<div>',
            'after_widget' => "</div>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );
}
add_action( 'widgets_init', 'my_custom_sidebar' );

//custom logo
function theme_prefix_setup() {
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-width' => true,
    ) );
}
add_action( 'after_setup_theme', 'theme_prefix_setup' );

//Register Navigations
function my_custom_menus() {
    register_nav_menus(
        array(
            'primary' => __( 'Primary menu' ),
            'footer' => __( 'Footer menu' )
        )
    );
}
add_action( 'init', 'my_custom_menus' );

//copyright symbol and current year
add_shortcode('copyright-year', function($atts, $content)
{
    extract(shortcode_atts(array(
        'sign' => 'true',
        'start' => '',
    ), $atts));

    $current_year = date('Y');
    $print_sign = ($sign === 'true') ? '&copy;' : '';

    if($start === $current_year || $start === '')
        return "{$print_sign} {$current_year}";
    else
        return "{$print_sign} {$start} - {$current_year}";
});

//back end orders details for the custom post
function orders_columns($columns){
    $newColumns = array();
    $newColumns['id'] = 'Order ID';
    $newColumns['total_items'] = 'Total items';
    $newColumns['total_amount'] = 'Total cost';
    $newColumns['payment_mode'] = 'Payment Method';
    return $newColumns;
}
add_filter('manage_orders_posts_columns','orders_columns');

//Added cart in nav menu in custom way
add_filter( 'wp_get_nav_menu_items', 'custom_nav_menu_items', 20, 2 );

function custom_nav_menu_items( $items, $menu ){
    if($menu->slug=='primary-menu-links'){  //display cart only in header
        $quantity = 0;
        if(!empty($_SESSION['cart_items'])){
            foreach ($_SESSION['cart_items'] as $k => $v) {
                $quantity+= $v['p_qty'];
            }
        }
        $quantity= "<span class='header_cart' style='color:black'>".$quantity."</span>";
        $msg = "Cart(".$quantity.")";
        $items[] = _custom_nav_menu_item($msg, get_home_url().'/cart/', 5 );
    }
    return $items;
}

function _custom_nav_menu_item( $title, $url, $order=0, $parent = 5 ){
    $item = new stdClass();
    $item->title = $title;
    $item->url = $url;
    $item->ID = $parent;
    $item->db_id = $item->ID;
    $item->menu_order = $order;
    $item->menu_item_parent = $item->ID;
    $item->type = '';
    $item->object = 'Primary menu';
    $item->object_id = '';
    $item->classes = array();
    return $item;
}

/*//orders table created over theme switch
function your_set_tables_function(){
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE `orders` (
                           id int(11) NOT NULL AUTO_INCREMENT,
                           username 	varchar(100)  NOT NULL,
                           contact_no BIGINT(20) NOT NULL,
                           email varchar(50) NOT NULL,
                           shipping_address varchar(255) NOT NULL,
                           billing_address varchar(255) NOT NULL,
                           payment_mode varchar(10) NOT NULL,
                           total_items INT(10) NOT NULL,
                           total_amount INT(10) NOT NULL,
                           PRIMARY KEY (id)
                          ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action("after_switch_theme", "your_set_tables_function");*/

//add_action( 'admin_menu', 'register_my_custom_menu_page' );
//function register_my_custom_menu_page() {
//    // add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
//    add_menu_page( 'Custom Menu Page Title', 'Orders', 'manage_options', 'orders.php', 'admin_orders_page', 'dashicons-welcome-widgets-menus', 90 );
//}
//
//function admin_orders_page(){
//    global $wpdb;
//    $orders = $wpdb->get_results("SELECT id,username,total_items,total_amount FROM orders");
//    echo '<table id="ordersTable">';
//    echo "<thead><tr><th>Order Id</th><th>Customer name</th><th>Total items</th><th>Total amount</th><th>Order details</th></tr></thead>";
//    foreach($orders as $key =>$getRow ){
//        echo "<tbody>
//                  <tr>
//                  <td> $getRow->id </td>
//                  <td> $getRow->username </td>
//                  <td> $getRow->total_items </td>
//                  <td> $getRow->total_amount </td>
//                  <td><a href=''>View</a> </td>
//                  </tr>
//              </tbody>";
//    }
//    echo "</table>";
//}

?>