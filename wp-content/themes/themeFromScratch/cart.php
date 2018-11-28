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
            global $current_user;
            $current_user->display_name;
            $product_id = array();
            $sum = 0;
            $farray = array();
            $cart_list = $_SESSION['cart_items'];

            if(!empty($cart_list)){
            foreach ($cart_list as $k => $v){
                $product_id[] = $v['p_id'];
            }
            }

            $cart_unique_product_id = array_unique($product_id);
            foreach ($cart_unique_product_id as $v){

                $qty = 0;
                $amount = 0;
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

             $_SESSION['checkout'] =  $farray;
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
                <?php

                if(!empty($farray))
                foreach($farray as $key => $value){ ?>
                <tr class="cart_list_<?php echo $value['p_id'];?>">
                    <td><?php echo get_the_title($value['p_id']); ?></td>
                    <td><?php echo get_the_post_thumbnail( $value['p_id'], 'thumbnail'); ?></td>
                    <td>
                        <div>
                            <a id="cart_qty_up" href="javascript:void(0);" class="cart_qty_up" data-value="<?php echo $value['p_id'];?>" data-value1="<?php echo get_post_meta($value['p_id'], 'my_product_price_value_key', true);?>">
                            <button class="btn">+</button>
                            </a>
                            <?php echo $value['qty'];?>

                            <?php if($value['qty'] == 1) { ?>
                                <a class="btn disabled"><button class="btn">-</button></a>
                            <?php }else{ ?>
                                <a class="cart_qty_down btn" href="javascript:void(0);" data-value="<?php echo $value['p_id']; ?>">
                                <button class="btn">-</button>
                                </a>
                            <?php } ?>
                        </div>
                    </td>
                    <td>
                        <?php  echo get_post_meta($value['p_id'], 'my_product_price_value_key', true) * $value['qty']; ?>
                    </td>

                    <td><a href="javascript:void(0);" class="btn remove_from_cart" data-value="<?php echo $value['p_id']; ?>">
                        <button>Remove</button>
                    </a><td>
                </tr>
                <?php  }



                ?>
                </tbody>
            </table>

            <?php if(!empty($_SESSION['cart_items'])){ ?>
            <div style="margin-left: 490px;">
                <dt>Total  Rs.<?php echo $farray[0]['total_amount']; ?></dt>

                <button id="checkout" class="btn btn-primary">Place order</button>


            </div>
            <?php }  ?>

        </div>

        <div class="col-lg-2">
           <?php get_sidebar('right'); ?>
        </div>
    </div>

<?php get_footer(); ?>

