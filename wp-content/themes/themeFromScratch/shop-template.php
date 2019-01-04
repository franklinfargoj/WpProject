<?php
/*Template name: Shop*/
get_header();
?>

    <div class="row">
        <div class="col-lg-2">
        </div>

        <div class="col-lg-8">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; // end of the loop. ?>
        </div>

        <div class="col-lg-2">
        </div>
    </div>

<?php get_footer(); ?>