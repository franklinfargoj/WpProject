<?php get_header(); ?>

<body>

    <div id="owl-demo" class="owl-carousel owl-theme">
        <?php
        $query = new WP_Query( array( 'post_type' => 'carosal', 'paged' => $paged ) );
        if ( $query->have_posts() ) : ?>
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <div class="item"><img src=<?php echo get_the_post_thumbnail_url() ?> /></div>
            <?php endwhile; wp_reset_postdata(); ?>
        <?php else : ?>
        <?php endif; ?>
    </div>

    <div class="row">
            <div class="col-lg-2" style="background-color:peachpuff;">
                <?php get_sidebar(); ?>
            </div>

            <div class="col-lg-8" style="background-color:peachpuff;">
                <?php
                if ( have_posts() ) {
                    while ( have_posts() ) : the_post();
                        ?>
                        <div class="blog-post">
                            <h2 class="blog-post-title"><?php the_title(); ?></h2>
                            <p class="blog-post-meta"><?php the_date(); ?> by <?php the_author(); ?></p>
                            <?php the_content();




                            echo get_site_url(); ?>
                        </div><!-- /.blog-post -->
                        <?php
                    endwhile;
                }
                ?>
            </div>

            <div class="col-lg-2 " style="background-color:peachpuff;">
                <?php get_sidebar('right'); ?>
            </div>
    </div>

</body>

<?php get_footer(); ?>

<script>
    $(document).ready(function() {
        $("#owl-demo").owlCarousel({
            navigation : true, // Show next and prev buttons
            slideSpeed : 300,
            paginationSpeed : 400,
            items : 1,
            itemsDesktop : false,
            itemsDesktopSmall : false,
            itemsTablet: false,
            itemsMobile : false
        });
    });
</script>
