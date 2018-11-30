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

        <table class="table">
            <thead>
            <tr>
                <th>Product Name</th>
                <th>Image</th>
                <th>Qty.</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(!empty($_SESSION['cart_items'])){
                foreach ($_SESSION['cart_items'] as $key=> $value){ ?>
                    <tr>
                        <td><?php echo get_the_title($value['p_id']); ?></td>
                        <td><?php echo get_the_post_thumbnail( $value['p_id'], 'thumbnail'); ?></td>
                        <td><?php echo $value['p_qty']; ?></td>
                    </tr>
                <?php  }
            }
            ?>
            </tbody>
        </table>

        <form>
            <div class="form-row">
              <textarea placeholder="Shipping address"id="shipping" name="shipping" cols="22" rows="3"></textarea>
              <span style="display:inline-block; width: 10px;"></span>
              <textarea placeholder="Billing address" id="billing" name="billing" cols="22" rows="3"></textarea>
            </div>
            <?php // echo 'User email: ' . $current_user->user_email . "\n";?>

            <br>
            <div class="form-group col-md-4">
                <label for="inputState">Payment options</label>
                <select id="inputState" class="form-control" >
                    <option selected>Select</option>
                    <option value="cod">COD</option>
                    <option value="card">Credit/Card</option>
                    <option value="netbank">Net banking</option>
                </select>
            </div>


            <div class="form-row">
            <span style="margin-left: 320px;"><?php echo (count($_SESSION['cart_items'])); ?> ITEMS</span>
            <?php
            $total_price=0;
            foreach($_SESSION['cart_items'] as $key=> $value){
                   $total_price+=$value['p_price']*$value['p_qty'];
            }
            ?>
            <span style="margin-left: 75px;">Total Payable   Rs. <?php echo $total_price; ?></span>

            <button type="submit" class="btn btn-primary mb-2" style="margin-left: 635px;">Submit Order</button>
            </div>
        </form>

    </div>

    <div class="col-lg-2">
        <?php get_sidebar('right'); ?>
    </div>
</div>
<?php get_footer(); ?>
