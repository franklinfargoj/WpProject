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
    $qty_cart = 0;
    $total = 0;
    foreach ($_SESSION['cart_items'] as $key=>$value){
        $total+= $value['p_price']* $value['p_qty'];
        $qty_cart+=$value['p_qty'];
    }

    $cart_price = array(
        'product' =>   $_SESSION['cart_items'][$_POST['product_id']],
        'total' => $total,
        'qty_cart'=>$qty_cart
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
    $qty=0;
    $total = 0;
    foreach ($_SESSION['cart_items'] as $key=>$value){
        $total+= $value['p_price']* $value['p_qty'];
        $qty+=$value['p_qty'];
    }

    $cart_price = array(
         'product_id' => $_SESSION['cart_items'],
         'total' => $total,
         'qty' => $qty
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

//Added cart in nav menu externally
add_filter( 'wp_get_nav_menu_items', 'custom_nav_menu_items', 20, 2 );
function custom_nav_menu_items( $items, $menu ){
    $quantity = 0;
    if(!empty($_SESSION['cart_items'])){
        foreach ($_SESSION['cart_items'] as $k => $v) {
            $quantity+= $v['p_qty'];
        }
    }
    $xx= "<span class='header_cart' style='color:black'>".$quantity."</span>";
    $msg = "Cart(".$xx.")";

    $items[] = _custom_nav_menu_item($msg, get_home_url().'/cart/', 4 );
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
    //$item->attr_title = '';
    // $item->description = '';
    // $item->target = '';
    // $item->xfn = '';
    // $item->status = '';
    return $item;
}

//orders table created over theme switch
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
add_action("after_switch_theme", "your_set_tables_function");

//insert order details in order table
function order_confirmation() {
    global $wpdb;

    foreach ($_SESSION['cart_items'] as $k=>$v) {
        $product_qty[] = [$k => $v['p_qty']];
    }

    if ( isset( $_POST['submit'] ) ){
    $data = array(
        'username' => $_POST['user_name'],
        'contact_no' => 9050520415,
        'email' => $_POST['user_email'],
        'shipping_address' => $_POST['shipping_add'],
        'billing_address' => $_POST['billing_add'],
        'payment_mode' => $_POST['paymentMtd'],
        'total_items' => $_POST['cart_item'],
        'total_amount' => $_POST['total_price'],
        'product_id_qty' => json_encode($product_qty)
     );
    $wpdb->insert('orders',$data);
    }
    $lastid = $wpdb->insert_id;

    if($lastid){
        unset($_SESSION["cart_items"]);
        wp_redirect(home_url());
    }
}
add_action('admin_post_nopriv_confirmation', 'order_confirmation' );
add_action('admin_post_confirmation', 'order_confirmation' );

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

if(is_admin())
{
    new Paulund_Wp_List_Table();
}
/**
 * Paulund_Wp_List_Table class will create the page to load the table
 */
class Paulund_Wp_List_Table
{
    /*
     * Constructor will create the menu item
     */
    public function __construct()
    {
        add_action( 'admin_menu', array($this, 'add_menu_orders' ));
    }
    /*
     * Menu item will allow us to load the page to display the table
     */
    public function add_menu_orders()
    {



            // Show my WP_List_Table
            add_menu_page( 'Orders List Table', 'Orders', 'manage_options', 'orders-lists', array($this, 'list_table_page') );




    }
    /**
     * Display the list table page
     * @return Void
     */
    public function list_table_page()
    {
        if(isset($_GET['view_record'])) :
            // Show my edit hotel form
            $order_id = $_GET['view_record'];
            global $wpdb;
            $orders = $wpdb->get_results("SELECT * FROM orders WHERE id= $order_id",ARRAY_A);

            echo '<h3>'."Username".'<span style="display:inline-block; width: 65px;"></span>'.$orders[0]['username'].'</h3>';
            echo '<h3>'."Phone number".'<span style="display:inline-block; width: 40px;"></span>'.$orders[0]['contact_no'].'</h3>';
            echo '<h3>'."User Email".'<span style="display:inline-block; width: 70px;"></span>'.$orders[0]['email'].'</h3>';
            echo '<h3>'."Shipping address".'<span style="display:inline-block; width: 32px;"></span>'.$orders[0]['shipping_address'].'</h3>';
            echo '<h3>'."Billing address".'<span style="display:inline-block; width: 50px;"></span>'.$orders[0]['billing_address'].'</h3>';
            echo '<h3>'."Mode of payment".'<span style="display:inline-block; width: 30px;"></span>'.$orders[0]['payment_mode'].'</h3>';
            echo '<h3>'."Cart items".'<span style="display:inline-block; width: 92px;"></span>'.$orders[0]['total_items'].'</h3>';
            echo '<h3>'."Total cost".'<span style="display:inline-block; width: 94px;"></span>'.$orders[0]['total_amount'].'</h3>';

            $products_qty = json_decode($orders[0]['product_id_qty']);
            echo '<table class="table table-dark">';
            echo '<thead><tr>
                        <th><h3>Product name</th>
                        <th><h3>Qty</th>
                 </tr></thead>';

            foreach($products_qty as $k=>$value)
            {
                $x = (array)$value;
                $product_id = array_keys($x)[0];
                $qty = array_values($x)[0];

                echo "<tbody>
                      <tr>
                          <td>".get_the_title($product_id ) ."</td>
                          <td>  $qty </td>
                      </tr>
                      </tbody>";

            }
            echo '</table>';

        else :

            $exampleListTable = new Example_List_Table();
            $exampleListTable->prepare_items();
            ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2>Customer orders</h2>
                <?php $exampleListTable->display(); ?>
            </div>
            <?php

        endif;
    }
}
// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/*
 * Create a new table class that will extend the WP_List_Table
 */
class Example_List_Table extends WP_List_Table
{
    /*
     * Prepare the items for the table to process
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $data = $this->table_data();

        $perPage = 3;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
        $this->_column_headers = array($columns);
        $this->items = $data;
    }

    /*
     * Override the parent columns method. Defines the columns to use in your listing table
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'id'          => 'Order Id',
            'username'       => 'Customer name',
            'total_itm' => 'Total items',
            'amount'        => 'Total amount',
            'view' => 'View'
        );
        return $columns;
    }

    /*
     * Get the table data
     * @return Array
     */
    private function table_data()
    {
        $data = [];
        global $wpdb;
        $orders = $wpdb->get_results("SELECT id,username,total_items,total_amount FROM orders",ARRAY_A);
        foreach($orders as $key =>$getRow ){
            $data[] = array(
                'id'          => $getRow['id'],
                'username'       => $getRow['username'],
                'total_itm' => $getRow['total_items'],
                'amount'        => $getRow['total_amount'],
                'view' => '<a href="'. admin_url('admin.php?page=orders-lists&view_record='.$getRow['id'] ).'">View details</a>'
                );
        }
        return $data;
    }

    /*
     * Define what data to show on each column of the table
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            case 'username':
            case 'total_itm':
            case 'amount':
            case 'view':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }
}

?>