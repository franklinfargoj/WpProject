<?php get_header(); ?>

<div id="owl-demo" class="carousel slide" data-ride="carousel" xmlns="http://www.w3.org/1999/html">
        <?php  if( get_query_var('pagename') == ''){
        $query = new WP_Query( array( 'post_type' => 'carosal', 'paged' => $paged ) );
        if ( $query->have_posts() ) : ?>
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <div class="item"><img src=<?php echo get_the_post_thumbnail_url() ?> /></div>
            <?php endwhile; wp_reset_postdata(); ?>
        <?php else : ?>
        <?php endif;} ?>
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

            <?php  echo do_shortcode("[frontend_products]"); ?>

        </div>

        <div class="col-lg-2">
            <?php get_sidebar('right'); ?>
        </div>

    </div>
</div>
</body>

<?php get_footer(); ?>

