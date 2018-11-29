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

//access to the session
function myStartSession() {
    session_start();
}
add_action('init', 'myStartSession', 1);

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

//Set session for products added to cart
function my_user_cart() {

    if(!empty($_SESSION['cart_items'])){

        if(array_key_exists($_POST['product_id'],$_SESSION['cart_items'])){
            $_SESSION['cart_items'][$_POST['product_id']]['p_qty']+=1;
        }else{
            $_SESSION['cart_items'][$_POST['product_id']] = array(
                'p_id'   => $_POST['product_id'],
                'p_price' => $_POST['price'],
                'p_qty' =>  $_POST['quantity']
            );
        }
    }else{

        $_SESSION['cart_items'][$_POST['product_id']] = array(
            'p_id'   => $_POST['product_id'],
            'p_price' => $_POST['price'],
            'p_qty' =>  $_POST['quantity']
        );
    }
    $result = 1;

    $total = 0;
    foreach ($_SESSION['cart_items'] as $key=>$value){
        $total+= $value['p_price']* $value['p_qty'];
    }


    $cart_price = array(
        'product' =>   $_SESSION['cart_items'][$_POST['product_id']],
        'total' => $total
    );

   // echo json_encode($_SESSION['cart_items'][$_POST['product_id']]);
    echo json_encode($cart_price);
    die;
}
add_action("wp_ajax_add_to_cart", "my_user_cart");
add_action("wp_ajax_nopriv_add_to_cart", "my_user_cart");

add_action("wp_ajax_cart_qty_increase","my_user_cart");
add_action("wp_ajax_nopriv_cart_qty_increase","my_user_cart");

//Reduce array by prouct id from cart session
function downgrade_cart_qty(){



   // print_r($_SESSION['cart_items'][$_POST['product_id']]['p_qty']);die;
    if($_SESSION['cart_items'][$_POST['product_id']]['p_qty'] > 1){
        if(array_key_exists($_POST['product_id'],$_SESSION['cart_items'])){
            $_SESSION['cart_items'][$_POST['product_id']]['p_qty']-=1;
        }
    }

    $total = 0;
    $p_price =0;
    foreach ($_SESSION['cart_items'] as $key=>$value){
        $total+= $value['p_price']* $value['p_qty'];
        $p_price = $value['p_price']* $value['p_qty'];
    }

    $cart_price = array(
        'sub_total' => $_SESSION['cart_items'][$_POST['product_id']],
        'total' => $total
    );


    echo json_encode($cart_price);
    die;
}
add_action("wp_ajax_cart_qty_decrease","downgrade_cart_qty");
add_action("wp_ajax_nopriv_cart_qty_decrease","downgrade_cart_qty");

//Remove array out of the cart session (fadeout in jquery)
function out_of_cart() {
    foreach ($_SESSION['cart_items'] as $key => $value){
        if($value['p_id'] == $_POST['product_id']){
            unset($_SESSION['cart_items'][$key]);
        }
    }

    $total = 0;
    foreach ($_SESSION['cart_items'] as $key=>$value){
        $total+= $value['p_price']* $value['p_qty'];
    }

    $cart_price = array(
         'product_id' => $_SESSION['cart_items'],
         'total' => $total
    );

    echo json_encode($cart_price);
    die;
}
add_action("wp_ajax_delete_from_cart", "out_of_cart");
add_action("wp_ajax_nopriv_delete_from_cart", "out_of_cart");

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


//function admin_default_page() {
//    return 'https://careers.slb.com/';
//}
//add_filter('login_redirect', 'admin_default_page');

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


// My-Products Custom Post Type
function product_init() {

    // set up product labels
    $labels = array(
        'name' => 'Products',
        'singular_name' => 'Products',
        'add_new' => 'Add New Product',
        'add_new_item' => 'Add New Product',
        'edit_item' => 'Edit Product',
        'new_item' => 'New Product',
        'all_items' => 'All Products',
        'view_item' => 'View Product',
        'search_items' => 'Search Products',
        'not_found' =>  'No Products Found',
        'not_found_in_trash' => 'No Products found in Trash',
        'parent_item_colon' => '',
        'menu_name' => 'Products',
    );

    // register post type
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array('slug' => 'product'),
        'query_var' => true,
        'show_in_nav_menus' => true,
        'menu_icon' => 'dashicons-randomize',

        'supports' => array(
            'title',
            'editor',
            'author',
            'excerpt',
            'trackbacks',
            'custom-fields',
            'comments',
            'revisions',
            'thumbnail',
            'page-attributes'
        )
    );
    register_post_type( 'my-product', $args );

    // register taxonomy
    register_taxonomy('product_category', 'product', array('hierarchical' => true, 'label' => 'Category', 'query_var' => true, 'rewrite' => array( 'slug' => 'product-category' )));
}
add_action( 'init', 'product_init' );

//back end product details for the custom post
function my_product_columns($columns){
    //unset( $columns['author'],$columns['title'] );
    $newColumns = array();
    $newColumns['title'] = 'Name';
    $newColumns['price'] = 'Price';
    $newColumns['author'] = 'Author';
    $newColumns['date'] = 'Date';
    return $newColumns;
}
add_filter('manage_my-product_posts_columns','my_product_columns');

//add price column in listing of custom post Products
function my_product_custom_column($column,$post_id){
    switch ($column){
        case 'price':
            echo get_post_meta ( $post_id, 'my_product_price_value_key', true );
            break;
    }
}
add_action('manage_my-product_posts_custom_column','my_product_custom_column',10,2);

//echo get_the_excerpt();
function my_product_add_meta_box(){
    add_meta_box('price','Price','my_product_price_callback','my-product','normal');
}
add_action('add_meta_boxes','my_product_add_meta_box');

function my_product_price_callback($post){
    wp_nonce_field('my_product_save_price','my_product_price_meta_box_nonce');
    $value = get_post_meta($post->ID,'my_product_price_value_key',true);
    echo '<label for="my_product_price_field">Product price:</label>';
    echo '<input type="text" id="my_product_price_field" name="my_product_price_field" value="' . esc_attr($value) .'" size="25">';
}

//saves the product price of custom post products in db.
function my_product_save_price($post_id){

    if(! isset( $_POST['my_product_price_meta_box_nonce'])){
        return;
    }

    if(! wp_verify_nonce( $_POST['my_product_price_meta_box_nonce'],'my_product_save_price')){
        return;
    }

    if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
        return;
    }

    if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
        return;
    }

    $my_data = sanitize_text_field($_POST['my_product_price_field']);
    update_post_meta($post_id,'my_product_price_value_key',$my_data);
}
add_action('save_post','my_product_save_price');

?>



