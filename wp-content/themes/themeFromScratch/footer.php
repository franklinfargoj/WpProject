
<footer class="blog-footer">

    <nav class="site-nav">
        <?php
        $args = array(
            'theme_location' => 'footer'
        );
        ?>
        <?php wp_nav_menu($args); ?>
    </nav>

    <p>
        <a href="#">Back to top</a>
    </p>
</footer>

<!-- Bootstrap core JavaScript
================================================== -->
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>



<!-- Basic stylesheet -->
<link rel="stylesheet" href="<?php echo get_site_url(); ?>/wp-content/themes/themeFromScratch/JS/owl.carousel.css">

<!-- Default Theme -->
<link rel="stylesheet" href="<?php echo get_site_url(); ?>/wp-content/themes/themeFromScratch/JS/owl.theme.css">
<!-- Include js plugin -->
<script src="<?php echo get_site_url(); ?>/wp-content/themes/themeFromScratch/JS/owl.carousel.js"></script>

<?php wp_footer(); ?>

</html>
