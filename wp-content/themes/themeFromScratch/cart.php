<?php
/*Template name: Custom Cart page*/
get_header();
?>

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
        </div>

        <div class="col-lg-2">
           <?php get_sidebar('right'); ?>
        </div>
    </div>

<?php get_footer(); ?>

