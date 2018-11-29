<?php
/*Template name: Custom Login page*/
if(!is_user_logged_in()){
get_header();
}
?>

<?php if(!is_user_logged_in()){ ?>
    <form method="post" id="loginFormoId">
        <p>
            <label for="username">Username/Email</label>
            <input type="text" id="custom_username" class="custom_username" name="custom_username">
        </p>

        <p>
            <label for="password">Password</label>
            <input type="password" id="custom_password" name="custom_password" class="custom_password">
        </p>
<!--        <p>-->
<!--            <button type="submit" name="btn_submit">Log In</button>-->
<!--        </p>-->
        <div>
            <input type="submit" id="submitButton"  name="submitButton">
        </div>

    </form>
<?php }else{
    $url = get_home_url();
    wp_redirect($url);
}?>

<?php get_footer();?>