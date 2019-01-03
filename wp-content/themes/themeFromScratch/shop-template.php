<?php
/*Template name: Shop*/
get_header();
?>

    <div class="row">
        <div class="col-lg-2">
        </div>

        <div class="col-lg-8">
            <?php
            echo do_shortcode("[frontend_products]");
            ?>
        </div>

        <div class="col-lg-2">
        </div>
    </div>

<?php get_footer(); ?>