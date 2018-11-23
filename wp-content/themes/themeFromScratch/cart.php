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
            $mainarry = $_SESSION['cart_items'];

            foreach ($mainarry as $k => $v){
                $product_id[] = $v['p_id'];
            }

            $uarray = array_unique($product_id);
            foreach ($uarray as $v){
                $qty=0;
                foreach ($mainarry as $k1 => $v1){
                    if($v1['p_id']==$v){
                        $qty +=$v1['p_qty'];
                    }
                }
                array_push($farray, array('p_id'=>$v,'qty'=>$qty));
            }

              // echo "<pre>";
              // print_r($mainarry);
              // print_r($uarray);


            ?>


            <table class="table">
                <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Image</th>
                    <th>Qty.</th>
                    <th>Price</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach($farray as $key => $value){    ?>
                <tr>
                    <td><?php echo get_the_title($value['p_id']); ?></td>

                    <td><?php echo get_the_post_thumbnail( $value['p_id'], 'thumbnail'); ?></td>




                    <td><?php echo $value['qty'];?></td>
                    <td><?php echo get_post_meta($value['p_id'], 'my_product_price_value_key', true); ?></td>
                </tr>
                <?php } ?>

                </tbody>

            </table>



        </div>

        <div class="col-lg-2">
           <?php get_sidebar('right'); ?>
        </div>
    </div>

<?php get_footer(); ?>

