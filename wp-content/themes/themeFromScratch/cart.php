<?php
/*Template name: Custom Cart page*/
get_header();
?>

    <div class="row">
        <div class="col-lg-2">
            <?php get_sidebar(); ?>
        </div>

        <div class="col-lg-8">
            <h2> Cart Items</h2>
            <table class="table">
                <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Image</th>
                    <th>Qty.</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
                </thead>

                <tbody>
                <?php
                if(!empty($_SESSION['cart_items']))
                foreach($_SESSION['cart_items'] as $key => $value){ ?>
                <tr class="cart_list_<?php echo $value['p_id'];?>">
                    <td><?php echo get_the_title($value['p_id']); ?></td>
                    <td><?php echo get_the_post_thumbnail( $value['p_id'], 'thumbnail'); ?></td>
                    <td>
                        <div>
                            <a id="cart_qty_up" href="javascript:void(0);" class="cart_qty_up" data-value="<?php echo $value['p_id'];?>" data-value1="<?php echo get_post_meta($value['p_id'], 'my_product_price_value_key', true);?>">
                            <button class="btn">+</button>
                            </a>

                            <span class="cart_qty_<?php echo $value['p_id'];?>">
                            <?php echo $value['p_qty'];?>
                            </span>

                            <a class="cart_qty_down btn" href="javascript:void(0);" data-value="<?php echo $value['p_id']; ?>">
                            <button class="btn">-</button>
                            </a>
                        </div>
                    </td>
                    <td>
                        <span id="per_product_price<?php echo $value['p_id'];?>" class="per_product_price<?php echo $value['p_id'];?>">
                        <?php  echo get_post_meta($value['p_id'], 'my_product_price_value_key', true) * $value['p_qty']; ?>
                        </span>
                    </td>

                    <td><a href="javascript:void(0);" class="btn remove_from_cart" data-value="<?php echo $value['p_id']; ?>">
                        <button>Remove</button></a>
                    <td>
                </tr>
                <?php  }  ?>
                </tbody>
            </table>

            <div style="margin-left: 490px;">
            <dt>
                <?php    if (!empty($_SESSION['cart_items'])) {   ?>
                Total  Rs.
                <?php } ?>
                <span  id="final_amount" class="final_amount">
                             <?php
                             if (!empty($_SESSION['cart_items'])) {
                             $total = 0;
                             foreach ($_SESSION['cart_items'] as $k=>$v){
                                  $total+= $v['p_price']*$v['p_qty'];
                             }
                             echo $total;
                             } else {
                                echo '';
                             } ?>
                </span>
            </dt>
                <?php    if (!empty($_SESSION['cart_items'])) {   ?>
                <button id="checkout" class="btn btn-primary">Place order</button>
                <?php } ?>
            </div>

        </div>

        <div class="col-lg-2">
           <?php get_sidebar('right'); ?>
        </div>
    </div>

<?php get_footer(); ?>

