<?php
/*Template name:Confirmation page*/
get_header();
?>
<div class="row">
    <div class="col-lg-2">
        <?php get_sidebar(); ?>
    </div>

    <div class="col-lg-8">

        <h3>Order Confirmation<h3>
        <p><?php echo 'Hello, ' . $current_user->display_name . "\n";  ?></p>


        <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">

            <input type="hidden" id="user_name" name="user_name" value="<?php echo $current_user->display_name;  ?>">

            <div class="form-row">
              <textarea placeholder="Shipping address" id="shipping_add" name="shipping_add" cols="22" rows="3" required></textarea>
              <span style="display:inline-block; width: 10px;"></span>
              <textarea placeholder="Billing address" id="billing_add" name="billing_add" cols="22" rows="3" required></textarea>
            </div>
            <?php // echo 'User email: ' . $current_user->user_email . "\n";?>

           <br>
           Email
           <input class="form-control" type="text" placeholder="<?php echo $current_user->user_email;  ?>" readonly>
           <input type="hidden" name="user_email" id="user_email" value="<?php echo $current_user->user_email; ?>">

           <br>
           <div class="form-group col-md-4">
                <label for="inputState">Payment options</label>
                <select id="paymentMtd" name="paymentMtd" class="form-control" >
                    <option value="cod">COD</option>
                    <option value="card">Credit/Card</option>
                    <option value="netbank">Net banking</option>
                </select>
            </div>
            <br>

            <?php
            $total_price=0;
            $total_quantity=0;
            if(!empty($_SESSION['cart_items'])){
                foreach($_SESSION['cart_items'] as $key=> $value){
                       $total_price+=$value['p_price']*$value['p_qty'];
                       $total_quantity+=$value['p_qty'];
                }
            }
            ?>

            <div class="form-row">
                <input type="hidden" id="cart_item" name="cart_item" value="<?php echo $total_quantity; ?>">
                <span style="margin-left: 320px;"><?php echo $total_quantity;?> - ITEMS</span>

                <input type="hidden" name="total_price" id="total_price" value="<?php echo $total_price; ?>">
                <span style="margin-left: 75px;" id="total_price" name="total_price">Total Payable   Rs. <?php echo $total_price; ?></span>

                <input type="hidden" name="action" value="confirmation">

                <button type="submit" name="submit" class="btn btn-primary mb-2" style="margin-left: 635px;">Submit Order</button>

            </div>
        </form>


    </div>

    <div class="col-lg-2">
        <?php get_sidebar('right'); ?>
    </div>
</div>
<?php get_footer(); ?>
