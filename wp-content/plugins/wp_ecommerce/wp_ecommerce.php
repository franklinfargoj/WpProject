<?php
/*
Plugin Name: WP Ecommerce
Description: A Ecommerce plugin to create and list  product and orders
Author: Franklin F.
Version: 0.1
*/

// function to create the DB table on plugin activation
function create_orders_table_on_plugin_activation() {
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
                           product_id_qty varchar(100) NOT NULL,
                           status varchar(100) NOT NULL,
                           PRIMARY KEY (id)
                          ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
// run the install scripts upon plugin activation
register_activation_hook(__FILE__,'create_orders_table_on_plugin_activation');

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
        'menu_name' => 'WP_Ecommerce',
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
add_action('init','product_init');

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


//order listing
if(is_admin())
{
    new Orders_Wp_List_Table();
}
/**
 * Orders_Wp_List_Table class will create the page to load the table
 */
class Orders_Wp_List_Table
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
        add_submenu_page( 'edit.php?post_type=my-product', 'Orders List Table', 'Orders', 'manage_options', 'orders-lists', array($this, 'list_table_page') );
    }
    /**
     * Display the list table page
     * @return Void
     */
    public function list_table_page()
    {
        if(isset($_GET['view_record'])) {

            // Show my edit orders form
            $order_id = $_GET['view_record'];
            global $wpdb;
            $orders = $wpdb->get_results("SELECT * FROM orders WHERE id= $order_id",ARRAY_A);

            if(isset($_POST['update_button'])){
                global $wpdb;
                $status = $_POST['status'];
                $update =  $wpdb->query("UPDATE orders SET status=".'"'.$status.'"'." "."WHERE id=$order_id");
            }

            echo '<h3>'."Username".'<span style="display:inline-block; width: 65px;"></span>'.$orders[0]['username'].'</h3>';
            echo '<h3>'."Phone number".'<span style="display:inline-block; width: 40px;"></span>'.$orders[0]['contact_no'].'</h3>';
            echo '<h3>'."User Email".'<span style="display:inline-block; width: 70px;"></span>'.$orders[0]['email'].'</h3>';
            echo '<h3>'."Shipping address".'<span style="display:inline-block; width: 32px;"></span>'.$orders[0]['shipping_address'].'</h3>';
            echo '<h3>'."Billing address".'<span style="display:inline-block; width: 50px;"></span>'.$orders[0]['billing_address'].'</h3>';
            echo '<h3>'."Mode of payment".'<span style="display:inline-block; width: 30px;"></span>'.$orders[0]['payment_mode'].'</h3>';
            echo '<h3>'."Cart items".'<span style="display:inline-block; width: 92px;"></span>'.$orders[0]['total_items'].'</h3>';
            echo '<h3>'."Total cost".'<span style="display:inline-block; width: 94px;"></span>'.$orders[0]['total_amount'].'</h3>';

            if(!empty($orders[0]['status'])){
                echo '<h2>'."Order status".'<span style="display:inline-block; width: 94px;"></span>'.$orders[0]['status'].'</h2>';
            }

            $products_qty = json_decode($orders[0]['product_id_qty']);
            echo '<table>';
            echo '<thead><tr>
                        <th><h5>Product name</th>
                        <th><h5>Qty</th>
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

            echo '<h4>Order status<h4>';
            echo '<form method="post">
                    <select name="status">                  
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="completed">Completed</option>                 
                    </select>
                    <br><br> 
                    <input type="submit" value="Update" id="update_button" name="update_button" class="update_button"/>
                    </form>';
        }else{
            $ordersListTable = new Orders_List_Table();
            $ordersListTable->prepare_items();
            ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2>Customer orders</h2>
                <?php $ordersListTable->display(); ?>
            </div>
            <?php
        }

        if($update == 1){
            wp_redirect(admin_url('admin.php?page=orders-lists&view_record='));
            exit;
        }

    }
}

// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/*
 * Create a orders table class that will extend the WP_List_Table
 */
class Orders_List_Table extends WP_List_Table
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
        get_user_meta( $user_id, $key, $single );
    }
}

//insert order details in order table
function order_confirmation() {
    global $wpdb;

    foreach ($_SESSION['cart_items'] as $k=>$v) {
        $product_qty[] = [$k => $v['p_qty']];
    }

    if(isset( $_POST['submit'] )){
        $data = array(
            'username' => $_POST['user_name'],
            'contact_no' => '',
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
        $template = locate_template('thankyou-template.php');
        load_template($template);
    }
}
add_action('admin_post_confirmation', 'order_confirmation' );
add_action('admin_post_nopriv_confirmation', 'order_confirmation' );

function listing_products() {
    if(get_query_var('pagename')==''){
        global $wpdb;
        $custom_post_type = 'post';
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = 'my-product' and post_status = 'publish'", $custom_post_type ), ARRAY_A );

        $output = '';
        foreach( $results as $index => $post ) {
            $output .= '<div>'.$post['post_title'] .'<p>';
            $output .= get_the_post_thumbnail( $post['ID'], 'thumbnail').'<br>';
            $output .= 'Rs.<span id="prod_'.$post['ID'].'">'.get_post_meta($post['ID'], 'my_product_price_value_key', true).'</span>';
            $output .='&emsp;'.'Qty<INPUT id="txtNumber'.$post['ID'].'" onkeypress="return isNumberKey(event)" type="number" min="1" value="1" style="width: 50px;">';
            $output .= '</div><p>';
            $output .='<div> <div><span style="color:#FE980F"></span></div></div>';
            $output .= '<a href="javascript:void(0);" class="btn add-to-cart" data-value='.$post['ID'].'><button>Add to cart</button></a>
                 <input type="hidden" id="custId" name="custId" value='.get_current_user_id().'>
                        <a href="javascript:void(0);" class="btn addto-wishlist" data-value='.$post['ID'].'><button>Wishlist</button></a> 
                        </br>
                        </br>';
        }
    }
 return $output;
}
add_shortcode( 'frontend_products','listing_products');

function wishlist_products(){

    $wishlist = json_decode(get_user_meta(get_current_user_id(), 'user_wishlist', true));
    $output = '';

    $output .= '<h2> Wishlist Items</h2>
                 <table class="table">
                 <thead>
                 <tr>
                 <th>Product Name</th>
                 <th>Image</th>
                 <th>Price</th>
                 <th>Action</th>
                 <th>Action</th>
                 </tr>
                 </thead>';

    foreach ($wishlist as $key => $value) {
        $output .= '<tbody><td>'.get_the_title($value).'</td>';
        $output .= '<td>'.get_the_post_thumbnail($value,'thumbnail').'</td>';
        $output .= '<td>'.get_post_meta($value,'my_product_price_value_key', true).'</td>';
        $output .= '<td><a href="javascript:void(0);" class="" data-value=$value><button>Add to cart</button></a></td>';
        $output .= '<td><a href="javascript:void(0);" class="remove_wishlist" data-value='.$value.'><button>Remove</button></a></td></tbody>';
    }
    $output .='<div></table>';

    return $output;
}
add_shortcode('frontend_wishlist','wishlist_products');

//access to the session
function myStartSession() {
    session_start();
}
add_action('init', 'myStartSession', 1);

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

function my_user_wishlist() {
      $product_ids = get_user_meta(get_current_user_id(), 'user_wishlist', true);
      if(empty($product_ids)){
          add_user_meta( get_current_user_id(), 'user_wishlist', json_encode(array($_POST['product_id'])));
      }else{
          if (!in_array($_POST['product_id'], json_decode($product_ids) ))
          {
              $added_wislist= json_decode($product_ids);
              $current_wishlisted_product_id = $_POST['product_id'];
              array_push($added_wislist,$current_wishlisted_product_id);
              update_user_meta( get_current_user_id(), 'user_wishlist',json_encode($added_wislist));
          }
      }
  die;
}

add_action("wp_ajax_add_to_wishlist", "my_user_wishlist");
add_action("wp_ajax_nopriv_add_to_wishlist", "my_user_wishlist");

function wishlist_remove_product() {
    $wishlist = json_decode(get_user_meta(get_current_user_id(), 'user_wishlist', true));

    foreach ($wishlist as $k => $v) {
            if ($v === $_POST['product_id']) {
                unset($wishlist[$k]);
            }
    }
    update_user_meta(get_current_user_id(), 'user_wishlist', json_encode(array_values($wishlist)));
    die();
}
add_action("wp_ajax_remove_from_wishlist", "wishlist_remove_product");
add_action("wp_ajax_nopriv_remove_from_wishlist", "wishlist_remove_product");

?>