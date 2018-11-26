<?php
/*
Template Name: Registration page
*/
get_header()
?>

    <div class="wrapper">

        <?php
        $error= '';
        $success = '';

        global $wpdb, $PasswordHash, $current_user, $user_ID;

        if(isset($_POST['task']) && $_POST['task'] == 'register' ) {


            $password1 = $wpdb->escape(trim($_POST['password1']));
            $password2 = $wpdb->escape(trim($_POST['password2']));
            $first_name = $wpdb->escape(trim($_POST['first_name']));
            $last_name = $wpdb->escape(trim($_POST['last_name']));
            $email = $wpdb->escape(trim($_POST['email']));
            $username = $wpdb->escape(trim($_POST['username']));

            if( $email == "" || $password1 == "" || $password2 == "" || $username == "" || $first_name == "" || $last_name == "") {
                $error= 'Please don\'t leave the required fields.';
            } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error= 'Invalid email address.';
            } else if(email_exists($email) ) {
                $error= 'Email already exist.';
            } else if($password1 <> $password2 ){
                $error= 'Password do not match.';
            } else {

                $user_id = wp_insert_user( array ('first_name' => apply_filters('pre_user_first_name', $first_name), 'last_name' => apply_filters('pre_user_last_name', $last_name), 'user_pass' => apply_filters('pre_user_user_pass', $password1), 'user_login' => apply_filters('pre_user_user_login', $username), 'user_email' => apply_filters('pre_user_user_email', $email), 'role' => 'subscriber' ) );
                if( is_wp_error($user_id) ) {
                    $error= 'Error on user creation.';
                } else {
                    do_action('user_register', $user_id);

                    $success = 'You\'re successfully register';
                }

            }

        }
        ?>

        <!--display error/success message-->
        <div id="message">
            <?php
            if(! empty($err) ) :
                echo '<p class="error">'.$err.'';
            endif;
            ?>

            <?php
            if(! empty($success) ) :
                echo '<p class="error">'.$success.'';
            endif;
            ?>
        </div>

        <form method="post">
            <h3>Don't have an account?<br /> Create one now.</h3>
            <p><label>Last Name</label></p>
            <p><input type="text" value="" name="last_name" id="last_name" /></p>
            <p><label>First Name</label></p>
            <p><input type="text" value="" name="first_name" id="first_name" /></p>
            <p><label>Email</label></p>
            <p><input type="text" value="" name="email" id="email" /></p>
            <p><label>Username</label></p>
            <p><input type="text" value="" name="username" id="username" /></p>
            <p><label>Password</label></p>
            <p><input type="password" value="" name="password1" id="password1" /></p>
            <p><label>Password again</label></p>
            <p><input type="password" value="" name="password2" id="password2" /></p>
            <div class="alignleft"><p><?php if($sucess != "") { echo $sucess; } ?> <?php if($error!= "") { echo $error; } ?></p></div>
            <button type="submit" name="btnregister" class="button" >Submit</button>
            <input type="hidden" name="task" value="register" />
        </form>

    </div>
<?php get_footer() ?>