<?php
/**
 * Module Name: WordPress Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/WordPress
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;
 //*************** Actions ***************\\
 add_action("comment_post", "wpachievements_wordpress_comment", 10 ,2);
 add_action('comment_unapproved_to_approved', 'wpachievements_wordpress_comment_app', 10, 1);
 add_action('comment_trash_to_approved', 'wpachievements_wordpress_comment_app', 10, 1);
 add_action('comment_spam_to_approved', 'wpachievements_wordpress_comment_app', 10, 1);
 add_action('delete_comment', 'wpachievements_wordpress_comment_del', 10, 1);
 add_action("publish_post", "wpachievements_wordpress_post", 10 ,2);
 add_action('before_delete_post', 'wpachievements_wordpress_post_del', 10, 1);
 add_action("user_register", "wpachievements_wordpress_register");
 add_action("wp_login", "wpachievements_wordpress_login", 20, 2);
 add_action("deleted_user", "wpachievements_wordpress_deletion");
 if(!function_exists(WPACHIEVEMENTS_MYARCADE) && !function_exists(WPACHIEVEMENTS_MYARCADE_ALT)){
   add_action("wp_footer", "wpachievements_wordpress_post_view");
 }
 //*************** Detect Comment Added ***************\\
 function wpachievements_wordpress_comment($cid, $status){
  if( !empty($cid) ){
   $cdata = get_comment($cid);
   if( $cdata->user_id ){
     if($status == 1){
       $uid=$cdata->user_id; $postid='';
      $comm_count = (int) wpachievements_get_site_option('wpachievements_comm_min_count');
      $comm_deduct = (int)wpachievements_get_site_option('wpachievements_comm_min_deduct');
       if( empty($comm_count) || $comm_count < 1 ){
         $comm_count = 1;
       }
       if( !empty($comm_count) ){
         if( str_word_count($cdata->comment_content) >= $comm_count ){
           $type='comment_added';
           if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
             $points = (int) wpachievements_get_site_option('wpachievements_comment_points');
           }
           if(empty($points)){$points=0;}
           wpachievements_new_activity($type, $uid, $postid, $points);
         } else{
           $type='comment_added_bad';
           if( !empty($comm_deduct) && $comm_deduct > 0 ){
             $points=$comm_deduct;
           } else{
             $points=0;
           }
           if(empty($points)){$points=0;}
           wpachievements_new_activity($type, $uid, $postid, -$points);
         }
       } else{
         $type='comment_added';
         if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
           $points = (int) wpachievements_get_site_option('wpachievements_comment_points');
         }
         if(empty($points)){$points=0;}
         wpachievements_new_activity($type, $uid, $postid, $points);
       }
     }
   }
  }
 }
 //*************** Detect Comment Approved ***************\\
 function wpachievements_wordpress_comment_app($cid){
  if( !empty($cid) ){
   $cdata = get_comment($cid);
   if( $cdata->user_id ){
     $uid=$cdata->user_id; $postid='';
     $comm_count = (int) wpachievements_get_site_option('wpachievements_comm_min_count');
     $comm_deduct = (int)wpachievements_get_site_option('wpachievements_comm_min_deduct');
     if( !empty($comm_count) && $comm_count > 0 ){
       if( str_word_count($cdata->comment_content) >= $comm_count ){
         $type='comment_added';
         if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
           $points = (int) wpachievements_get_site_option('wpachievements_comment_points');
         }
         if(empty($points)){$points=0;}
         wpachievements_new_activity($type, $uid, $postid, $points);
       } else{
         $type='comment_added_bad';
         if( !empty($comm_deduct) && $comm_deduct > 0 ){
           $points=$comm_deduct;
         } else{
           $points=0;
         }
         if(empty($points)){$points=0;}
         wpachievements_new_activity($type, $uid, $postid, -$points);
       }
     } else{
       $type='comment_added';
       if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
         $points = (int) wpachievements_get_site_option('wpachievements_comment_points');
       }
       if(empty($points)){$points=0;}
       wpachievements_new_activity($type, $uid, $postid, $points);
     }
   }
  }
 }
 //*************** Detect Comment Deleted ***************\\
 function wpachievements_wordpress_comment_del($cid){
  if( !empty($cid) ){
   $cdata = get_comment($cid);
   $type='comment_remove'; $uid=$cdata->user_id; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_comment_points');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, -$points);
  }
 }
 //*************** Detect Post Added ***************\\
 function wpachievements_wordpress_post($pid, $status){
  if( !empty($pid) ){
   $pdata = get_post( $pid );
   if( $pdata->post_author && $pdata->post_type == 'post' ){
    $post_count = (int) wpachievements_get_site_option('wpachievements_post_min_count');
    $post_deduct = (int) wpachievements_get_site_option('wpachievements_post_min_deduct');
     if( empty($post_count) || $post_count < 1 ){
       $post_count = 1;
     }
     $uid=$pdata->post_author; $postid=$pid;
     if( !empty($post_count) && $post_count > 0 ){
       if( str_word_count($pdata->post_content) >= $post_count ){
         $type='post_added';
         if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
           $points = (int) wpachievements_get_site_option('wpachievements_post_points');
         }
         if(empty($points)){$points=0;}
         wpachievements_new_activity($type, $uid, $postid, $points);
       } else{
         $type='post_added_bad';
         if( !empty($post_deduct) && $post_deduct > 0 ){
           $points=$post_deduct;
         } else{
           $points=0;
         }
         if(empty($points)){$points=0;}
         wpachievements_new_activity($type, $uid, $postid, -$points);
       }
     } else{
       $type='post_added';
       if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
         $points = (int) wpachievements_get_site_option('wpachievements_post_points');
       }
       if(empty($points)){$points=0;}
       wpachievements_new_activity($type, $uid, $postid, $points);
     }
   }
  }
 }
 //*************** Detect Post Deleted ***************\\
 function wpachievements_wordpress_post_del($pid){
  if( !empty($pid) ){
   $pdata = get_post($pid);
   if( $pdata->post_author && $pdata->post_status == 'trash' && $pdata->post_type == 'post' ){
    $type='post_remove'; $uid=$pdata->post_author; $postid=$pid;
    if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
      $points = (int) wpachievements_get_site_option('wpachievements_post_points');
    }
    if(empty($points)){$points=0;}
    wpachievements_new_activity($type, $uid, $postid, -$points);
   }
  }
 }
 //*************** Detect User Registration ***************\\
 function wpachievements_wordpress_register($user_id){
   if( !empty($user_id) ){
     $type='user_register'; $uid=$user_id; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_reg_points');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }
 //*************** Detect User Registration ***************\\
 function wpachievements_wordpress_login($login, $user){
   if( !empty($login) ){
     $points = ''; $end_time = '';
     $user = get_user_by( 'login', $login );
     $last_login = get_user_meta($user->ID, 'last_login', true);

     update_user_meta($user->ID, 'last_login', time());
     $delay = (int) wpachievements_get_site_option('wpachievements_log_delay');
     if( !empty($last_login) && $last_login != '' ){
       if( $delay == 1 ){
         $end_time = strtotime('+ '.$delay.' hour', $last_login);
       } else{
         $end_time = strtotime('+ '.$delay.' hours', $last_login);
       }
     } else{
       $end_time = time();
     }
     if( time() >= $end_time ){
       if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
         $points = (int) wpachievements_get_site_option('wpachievements_log_points');
       }
       if(empty($points)){$points=0;}
       $type='user_login'; $uid=$user->ID; $postid='';
       wpachievements_new_activity($type, $uid, $postid, $points);
     }
   }
 }
 //*************** Detect User Deletion ***************\\
 function wpachievements_wordpress_deletion($user_id){
   if( !empty($user_id) ){
     global $wpdb;
     $wpdb->delete( $wpdb->prefix.'achievements', array( 'uid' => $user_id ), array( '%d' ) );
   }
 }
 //*************** Detect User Viewing Post ***************\\
 if(!function_exists(WPACHIEVEMENTS_MYARCADE) && !function_exists(WPACHIEVEMENTS_MYARCADE_ALT)){
   function wpachievements_wordpress_post_view(){
     if( is_user_logged_in() && is_single() ){
       global $post, $wpdb;
       $current_user = wp_get_current_user();
       $points = (int) wpachievements_get_site_option('wpachievements_post_view_points');
       if(function_exists('is_multisite') && is_multisite()){
         $table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
       } else{
         $table = $wpdb->prefix.'achievements';
       }
       if( empty($points) ){
         $points = 0;
       }
       if( get_bloginfo('version') >= '3.5' ){
         $activities = $wpdb->get_var( "SELECT COUNT(type) FROM $table WHERE type='user_post_view' AND uid=$current_user->ID AND postid=$post->ID AND points > 0" );
       } else{
         $activities = $wpdb->get_var( "SELECT COUNT(type) FROM $table WHERE type='user_post_view' AND uid=$current_user->ID AND postid=$post->ID AND points > 0" );
       }
       if( empty($activities) || $activities == '' || $activities == 0 ){
         $delay = (int) wpachievements_get_site_option('wpachievements_post_view_delay');
         if(empty($delay)){$delay='0';}
         $last_visit = get_user_meta($current_user->ID, 'wpa_last_post_visit', true);
         if( empty($last_visit) || $last_visit <= strtotime('-'.$delay.' minutes') ){
           update_user_meta($current_user->ID, 'wpa_last_post_visit', time());
           $type='user_post_view'; $uid=''; $postid=$post->ID;
           if(empty($points)){$points=0;}
           wpachievements_new_activity($type, $uid, $postid, $points);

           if($current_user->ID != $post->post_author){
             $type='user_post_viewed'; $uid=$post->post_author; $postid=$post->ID;
             $points = (int) wpachievements_get_site_option('wpachievements_post_viewed_points');
             if(empty($points)){$points=0;}
             wpachievements_new_activity($type, $uid, $postid, $points);
           }
         }
       }
     }
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_wordpress_desc', 10, 6);
 function achievement_wordpress_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $comment = __('comments', 'wpachievements');
  } else{
    $comment = __('comment', 'wpachievements');
  }
  switch($type){
   case 'user_register': { $text = __('for registering with us', 'wpachievements'); } break;
   case 'user_login': { $text = __('for logging in', 'wpachievements'); } break;
   case 'user_post_view': { $text = sprintf( __('for visiting %s %s','wpachievements'), $times, WPACHIEVEMENTS_POST_TEXT); } break;
   case 'user_post_viewed': { $text = sprintf( __('for getting %s visits on your %s','wpachievements'), $times, WPACHIEVEMENTS_POST_TEXT); } break;
   case 'comment_added': { $text = sprintf( __('for adding %s %s', 'wpachievements'), $times, $comment); } break;
   case 'comment_added_bad': { $text = sprintf( __('for adding %s bad %s', 'wpachievements'), $times, $comment); } break;
   case 'comment_remove': { $text = __('for a comment being removed', 'wpachievements'); } break;
   case 'post_remove': { $text = sprintf( __('for removing a %s', 'wpachievements'), WPACHIEVEMENTS_POST_TEXT); } break;
   case 'post_added': { $text = sprintf( __('for adding %s %s', 'wpachievements'), $times, $pt); } break;
   case 'post_added_bad': { $text = sprintf( __('for adding %s bad %s', 'wpachievements'), $times, $pt); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_wordpress_desc', 10, 3);
 function quest_wordpress_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $comment = __('comments', 'wpachievements');
  } else{
    $comment = __('comment', 'wpachievements');
  }
  switch($type){
   case 'user_register': { $text = __('Register with us', 'wpachievements'); } break;
   case 'user_login': { $text = __('Log in', 'wpachievements'); } break;
   case 'user_post_view': { $text = sprintf( __('Visit %s %s','wpachievements'), $times, WPACHIEVEMENTS_POST_TEXT); } break;
   case 'user_post_viewed': { $text = sprintf( __('Get %s visits on your %s','wpachievements'), $times, WPACHIEVEMENTS_POST_TEXT); } break;
   case 'comment_added': { $text = sprintf( __('Add %s %s', 'wpachievements'), $times, $comment); } break;
   case 'post_added': { $text = sprintf( __('Add %s %s', 'wpachievements'), $times, $pt); } break;
  }
  return $text;
 }

//*************** Admin Settings ***************\\
add_filter('wpachievements_achievements_modules_admin_settings', 'wpachievements_wordpress_admin', 10, 3);
function wpachievements_wordpress_admin($defaultsettings, $shortname, $current_section){

  if ( $current_section == '' ) {

    $settings[] = array( 'title' => __( 'Default WordPress', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'DefaultWordPress_options' );

    $settings[] = array(
            'title'   => __( 'User Logging in', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user logs in.', 'wpachievements' ),
            'id'      => $shortname.'_log_points',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Adding Posts', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user adds a post.', 'wpachievements' ),
            'id'      => $shortname.'_post_points',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Adding Comments', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user adds a comment.', 'wpachievements' ),
            'id'      => $shortname.'_comment_points',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Registering', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user first registers.', 'wpachievements' ),
            'id'      => $shortname.'_reg_points',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Visits Post', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user views a post.', 'wpachievements' ),
            'id'      => $shortname.'_post_view_points',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'Users Post Visited', 'wpachievements' ),
            'desc'    => __( 'Points awarded to user when a post they have added is visited.', 'wpachievements' ),
            'id'      => $shortname.'_post_viewed_points',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array( 'type' => 'sectionend', 'id' => 'DefaultWordPress_options');
    $settings[] = array( 'title' => __( 'Login Validation', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'LoginValidation_options' );

    $settings[] = array(
            'title'   => __( 'User Login Delay', 'wpachievements' ),
            'desc'    => __( 'Enter the number of hours to delay logins being counted, this helps stop users getting points by logging in and out quickly.', 'wpachievements' ),
            'id'      => $shortname.'_log_delay',
            'type'    => 'text',
            'default' => '1',
          );

    $settings[] = array( 'type' => 'sectionend', 'id' => 'LoginValidation_options');

    $settings[] = array( 'title' => __( 'Comment Validation', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'CommentValidation_options' );

    $settings[] = array(
            'title'   => __( 'Minimum Word Limit', 'wpachievements' ),
            'desc'    => __( 'The minimum number of words required to gain comment based achievements.', 'wpachievements' ),
            'id'      => $shortname.'_comm_min_count',
            'type'    => 'text',
            'default' => '1',
          );

    $settings[] = array(
            'title'   => __( 'Number of Points to Deduct', 'wpachievements' ),
            'desc'    => __( 'The amount of points to deduct if the user has not met the minimum word limit.', 'wpachievements' ),
            'id'      => $shortname.'_comm_min_deduct',
            'type'    => 'text',
            'default' => '0',
          );
    $settings[] = array( 'type' => 'sectionend', 'id' => 'CommentValidation_options');

    $settings[] = array( 'title' => __( 'Post Validation', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'PostValidation_options' );

    $settings[] = array(
            'title'   => __( 'Minimum Word Limit', 'wpachievements' ),
            'desc'    => __( 'The minimum number of words required to gain post based achievements.', 'wpachievements' ),
            'id'      => $shortname.'_post_min_count',
            'type'    => 'text',
            'default' => '1',
          );

    $settings[] = array(
            'title'   => __( 'Number of Points to Deduct', 'wpachievements' ),
            'desc'    => __( 'The amount of points to deduct if the user has not met the minimum word limit.', 'wpachievements' ),
            'id'      => $shortname.'_post_min_deduct',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array( 'type' => 'sectionend', 'id' => 'PostValidation_options');

    $settings[] = array( 'title' => __( 'Post Visit Validation', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'PostVisitValidation_options' );

    $settings[] = array(
            'title'   => __( 'Users Post Visit Delay', 'wpachievements' ),
            'desc'    => __( 'Enter the number of minutes to delay visits being counted, this helps stop users getting points by refreshing the page quickly.', 'wpachievements' ),
            'id'      => $shortname.'_post_view_delay',
            'type'    => 'text',
            'default' => '1',
          );

    $settings[] = array( 'type' => 'sectionend', 'id' => 'PostVisitValidation_options');

    return $settings;
  	/**
	 * If not, return the default settings
	 **/
	} else {
		return $defaultsettings;
	}
}

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_wordpress_admin_events', 10);
 function achievement_wordpress_admin_events(){
   echo '<optgroup label="'.__('Default WordPress Events', 'wpachievements').'">
     <option value="user_register">'. __('The user first registers', 'wpachievements') .'</option>
     <option value="user_login">'. __('The user logins in', 'wpachievements') .'</option>
     <option value="post_added">'.sprintf( __('The user adds a %s', 'wpachievements'), WPACHIEVEMENTS_POST_TEXT) .'</option>
     <option value="comment_added">'.__('The user adds a comment', 'wpachievements').'</option>';
     if(!function_exists(WPACHIEVEMENTS_MYARCADE) && !function_exists(WPACHIEVEMENTS_MYARCADE_ALT)){
       echo'<option value="user_post_view">'.__('The user visits a post','wpachievements').'</option>
       <option value="user_post_viewed">'.__('The users posts get visited','wpachievements').'</option>';
     }
     echo '</optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_wordpress_admin_triggers', 1, 10);
 function achievement_wordpress_admin_triggers($trigger){

   switch($trigger){
     case 'user_register': { $trigger = __('The user first registers', 'wpachievements'); } break;
     case 'user_login': { $trigger = __('The user logins in', 'wpachievements'); } break;
     case 'post_added': { $trigger = sprintf( __('The user adds a %s', 'wpachievements'), WPACHIEVEMENTS_POST_TEXT); } break;
     case 'comment_added': { $trigger = __('The user adds a comment', 'wpachievements'); } break;
     case 'user_post_view': { $trigger = __('The user visits a post','wpachievements'); } break;
     case 'user_post_viewed': { $trigger = __('The users posts get visited','wpachievements'); } break;
   }

   return $trigger;

 }
?>
