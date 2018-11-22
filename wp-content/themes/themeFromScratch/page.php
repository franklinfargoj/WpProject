<?php get_header(); ?>

<div id="owl-demo" class="carousel slide" data-ride="carousel" xmlns="http://www.w3.org/1999/html">
        <?php
        $query = new WP_Query( array( 'post_type' => 'carosal', 'paged' => $paged ) );
        if ( $query->have_posts() ) : ?>
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <div class="item"><img src=<?php echo get_the_post_thumbnail_url() ?> /></div>
            <?php endwhile; wp_reset_postdata(); ?>
        <?php else : ?>
        <?php endif; ?>
</div>

<body>
<div class="container">
    <div class="row">

        <div class="col-lg-2">
            <?php get_sidebar(); ?>
        </div>

        <div class="col-lg-8">
            <?php
            if ( have_posts() ) {
                while ( have_posts() ) : the_post();
                    ?>
                    <div class="blog-post">
                        <h2 class="blog-post-title"><?php the_title(); ?></h2>
                        <?php
                        if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
                            the_post_thumbnail( 'thumbnail' );
                        }
                        ?>
                        <?php the_content(); ?>
                    </div><!-- /.blog-post -->
                    <?php
                endwhile;
            }
            ?>

            <?php if( get_query_var('pagename') == ''){ //displays the post only on home page
            // The Query
            $query = new WP_Query(array('post_type' => 'my-product'));
            query_posts( $query );
            // The Loop
            while ( $query->have_posts() ) : $query->the_post();// your post content ( title, excerpt, thumb....)
            ?>

                <div>
                <?php
                    if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
                        the_post_thumbnail('thumbnail');
                    }
                ?>
                </div>

<!--                     <div> --><?php //echo $post->ID;?><!-- </div>-->
                     <div> <?php the_title(); ?> </div>
                Rs.  <span id="prod_<?php echo $post->ID; ?>"><?php echo get_post_meta($post->ID, 'my_product_price_value_key', true); ?></span>
                Qty  <INPUT id="txtNumber<?php echo $post->ID; ?>" onkeypress="return isNumberKey(event)" type="number" min="1" value='1' style="width: 50px;">

                </br>
                <a id="add_to_cart" href="javascript:void(0);" class="btn btn-default add-to-cart" data-value="<?php echo $post->ID; ?>">
                       <button>Add to cart</button>
                </a>

                <button>Wishlist</button>
                </br>

                <?php
            endwhile;
            // Reset Query
            wp_reset_query();
            }
            ?>
        </div>

        <div class="col-lg-2">
            <?php get_sidebar('right'); ?>
        </div>

    </div>
</div>
</body>

<?php get_footer(); ?>

