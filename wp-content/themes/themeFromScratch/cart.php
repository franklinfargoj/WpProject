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
            <?php
            $product_id = array();
            $sum = 0;
            $farray = array();
            $cart_list = $_SESSION['cart_items'];

            foreach ($cart_list as $k => $v){
                $product_id[] = $v['p_id'];
            }

            $cart_unique_product_id = array_unique($product_id);
            foreach ($cart_unique_product_id as $v){


                $qty=0;
                $amount=0;
                foreach ($cart_list as $k1 => $v1){
                    if($v1['p_id']==$v){
                        $qty +=$v1['p_qty'];
                    }
                     $amount += $v1['p_price'];
                }
                array_push($farray, array('p_id'=>$v,'qty'=>$qty,'total_amount'=>$amount));
            }
               // echo "<pre>";
               // print_r($mainarry);
               // print_r($farray);die;
            ?>

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
                <?php foreach($farray as $key => $value){ ?>
                <tr>
                    <td><?php echo get_the_title($value['p_id']); ?></td>
                    <td><?php echo get_the_post_thumbnail( $value['p_id'], 'thumbnail'); ?></td>
                    <td>
                        <div>
                            <a id="cart_qty_up" href="javascript:void(0);" class="cart_qty_up" data-value="<?php echo $value['p_id'];?>" data-value1="<?php echo get_post_meta($value['p_id'], 'my_product_price_value_key', true);?>">
                            <button class="btn">+</button>
                            </a>
                            <?php echo $value['qty'];?>
                            <a id="cart_qty_down" href="javascript:void(0);" class="cart_qty_down" data-value="<?php echo $value['p_id']; ?>">
                            <button class="btn">-</button>
                            </a>
                        </div>
                    </td>
                    <td>
                        <?php  echo get_post_meta($value['p_id'], 'my_product_price_value_key', true) * $value['qty']; ?>
                    </td>

                    <td><a id="remove_from_cart" href="javascript:void(0);" class="btn remove_from_cart" data-value="<?php echo $value['p_id']; ?>">
                        <button>Remove</button>
                    </a><td>
                </tr>
                <?php  } ?>
                </tbody>
            </table>
            <div style="margin-left: 610px;"><h3>Total amount</h3><p>Rs.<?php echo $farray[0]['total_amount']; ?></p></div>

        </div>

        <div class="col-lg-2">
           <?php get_sidebar('right'); ?>
        </div>
    </div>

<?php get_footer(); ?>

