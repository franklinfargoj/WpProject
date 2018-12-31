<?php
/*Template name: Custom Checkout page*/
if ( is_user_logged_in() ) {
    get_header();
}
?>

<div class="row">
    <div class="col-lg-2">
        <?php get_sidebar(); ?>
    </div>

    <div class="col-lg-8">

        <?php
        if ( is_user_logged_in() ) {
            global $current_user;
            echo "<pre>";
            echo 'Hello, ' . $current_user->display_name . "\n";
            echo 'User display ID: ' .$current_user->ID . "\n";
            $total_items = 0;
            $final_amount = 0;
            if(!empty($_SESSION['cart_items'])){
                foreach ($_SESSION['cart_items'] as $key=>$value){
                    $total_items+= $value['p_qty'];
                    $final_amount+=$value['p_qty']*$value['p_price'];
                }
            }
            ?>

            <h2>Checkout</h2>
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

            <div style="margin-left: 490px;">
                <?php echo $total_items; ?> ITEMS
                <dt style="margin-left: 84px;">Total  Rs.<?php echo $final_amount;  ?></dt>
                <button id="confirmation" type="button" class="btn btn-primary">Continue</button>
            </div>

        <?php
        } else {
            $login =get_site_url().'/login/';
            wp_redirect($login);
            die;
        }
        ?>
    </div>

    <div class="col-lg-2">
        <?php get_sidebar('right'); ?>
    </div>
</div>


<?php get_footer(); ?>
