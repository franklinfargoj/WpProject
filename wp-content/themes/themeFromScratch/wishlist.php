<?php
/*Template name: Wishlist page*/
get_header();
?>
    <div class="row">
    <div class="col-lg-2">
        <?php get_sidebar(); ?>
    </div>

    <div class="col-lg-8">
        <h2> Wishlist Items</h2>
        <table class="table">
            <thead>
            <tr>
                <th>Product Name</th>
                <th>Image</th>
                <th>Price</th>
                <th>Action</th>
                <th>Action</th>
            </tr>
            </thead>


            <?php
            $wishlist = json_decode(get_user_meta(get_current_user_id(), 'user_wishlist', true));
            foreach ($wishlist as $key => $value){
            ?>

            <tbody>
            <td><?php echo get_the_title( $value ); ?></td>
            <td><?php echo get_the_post_thumbnail( $value, 'thumbnail' ); ?></td>
            <td><?php echo get_post_meta($value, 'my_product_price_value_key', true); ?></td>
            <td><a href="javascript:void(0);" class=" " data-value=$value><button>Add to cart</button></a></td>
            <td><a href="javascript:void(0);" class="remove_wishlist" data-value=<?php echo $value ?> ><button>Remove</button></a></td>
            <?php } ?>
            </tbody>

        </table>
    </div>

    <div class="col-lg-2">
        <?php get_sidebar('right'); ?>
    </div>

    </div>
<?php get_footer(); ?>