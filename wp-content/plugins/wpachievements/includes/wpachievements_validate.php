<?php
include ( '../../../wp-load.php' );

/**
 *****************************************************************
 *   W P A C H I E V E M E N T S   F A C E B O O K   S T U F F   *
 *****************************************************************
 */
 if ( isset( $_POST['fblo'] ) ) {

  // If have access to users email address
  if( !empty( $_POST['fblo_email'] ) ) {
    $userid = 0;

    // Get the users login details
    $user = get_user_by( 'email', $_POST['fblo_email'] );

    if ( $user ) {
      $userid = $user->ID;
    }

    // If user does not exist then register new user and log in
    if ( ! $user && ! empty( $_POST['fblo_user'] ) ) {
      $random_password = wp_generate_password();
      $userid = wp_create_user( $_POST['fblo_user'], $random_password, $_POST['fblo_email'] );
    }

    // Try to log in the user
    wp_set_auth_cookie( $userid, 0, 0 );

    update_user_meta( $userid,'FB_loggedin','true' );
  }
}

// If user has logged out in via Facebook
if ( isset($_POST['fblo_out'] ) ) {
  // Log out current user
  $current_user = wp_get_current_user();
  delete_user_meta( $current_user->ID, 'FB_loggedin' );
  wp_logout();
}
?>