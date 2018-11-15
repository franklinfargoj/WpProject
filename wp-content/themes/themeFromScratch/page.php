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


        <ul class="col-lg-8">
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



            <?php
            // The Query
            $query = new WP_Query(array('post_type' => 'my-product'));
            query_posts( $query );
            // The Loop
            while ( $query->have_posts() ) : $query->the_post();// your post content ( title, excerpt, thumb....)
            ?>


                <?php
                    if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
                        the_post_thumbnail('thumbnail');
                    }
                ?>


                     <?php
                        the_title();
                     ?>
                     INR.
                     <?php
                        echo get_post_meta($post->ID, 'my_product_price_value_key', true);
                     ?>

            <?php
            endwhile;
            // Reset Query
            wp_reset_query();
            ?>
        </div>


        <div class="col-lg-2">
            <?php get_sidebar('right'); ?>
        </div>
    
    </div>
</div>
</body>

<?php get_footer(); ?>

<script>
    $(document).ready(function() {
        $("#owl-demo").owlCarousel({
            navigation : true, // Show next and prev buttons
            autoPlay: 3000,
            slideSpeed : 50,
            paginationSpeed : 50,
            items : 1,
            responsive : {
                480 : { items : 1  }, // from zero to 480 screen width 4 items
                768 : { items : 2  }, // from 480 screen widthto 768 6 items
                1024 : { items : 3   // from 768 screen width to 1024 8 items
                }
            },
            itemsDesktop : false,
            itemsDesktopSmall : false,
            itemsTablet: false,
            itemsMobile : false
        });
    });
</script>
