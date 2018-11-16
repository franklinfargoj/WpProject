<?php
/*Template name: Custom Login page*/
global $user_ID;

if($user_ID){

    if($_POST){

        $username = $wpdb->prepare($_POST['username']);
        $password = $wpdb->prepare($_POST['password']);

        $login_array = array();
        $login_array['user_login'] = $username;
        $login_array['user_password'] = $password;

        $verify_user = wp_signon($login_array,true);

        if(!is_wp_error($verify_user)){
            echo "<script>window.location=' ".site_url()."'</script>";
        }else{
            echo "<p>Invalid credentials</p>";
            die();
        }

    }

    get_header();
?>

    <form method="post">
        <p>
            <label for="username">Username/Email</label>
            <input type="text" id="username" name="username">
        </p>

        <p>
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
        </p>

        <p>
            <button type="submit" name="btn_submit">Log In</button>
        </p>
    </form>

<?php

    get_footer();
    }else{

    }

?>