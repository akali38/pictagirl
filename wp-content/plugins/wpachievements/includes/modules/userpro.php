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

 //*************** Filters ***************\\
 add_action("wpachievements_after_new_achievement", "wpachievements_up_add_badges", 1, 3);
 add_action("wpachievements_admin_add_achievement", "wpachievements_up_add_badges", 1, 3);
 add_action("wpachievements_after_new_quest", "wpachievements_up_add_badges", 1, 3);
 add_action("wpachievements_remove_achievement", "wpachievements_up_remove_badges", 1, 2);
 add_action("userpro_sc_after_follow", "wpachievements_up_follow", 1, 1);
 add_action("userpro_sc_after_unfollow", "wpachievements_up_unfollow", 1, 1);
 add_action('userpro_after_user_verify', 'wpachievements_up_verified_user');

 //*************** Add Badge to User ***************\\
 function wpachievements_up_add_badges($user_id,$ach_ID,$achievement){

   $badges = get_user_meta($user_id, '_userpro_badges', true);

   // find if that badge exists
   if (is_array($badges)){
     foreach($badges as $k => $badge){
       if( isset($badge['badge_id']) ){
         if ( $badge['badge_id'] == $ach_ID ) {
           unset($badges[$k]);
         }
       }
     }
     update_user_meta($user_id, '_userpro_badges', $badges);
   }

   // add new badge to user
   $badges[] = array(
     'badge_url' => $achievement[6],
     'badge_title' => $achievement[0],
     'badge_id' => $ach_ID
   );
   update_user_meta($user_id, '_userpro_badges', $badges);

 }

 //*************** Remove Badge From User ***************\\
 function wpachievements_up_remove_badges($user_id,$ach_ID){

   $badges = get_user_meta($user_id, '_userpro_badges', true);

   // find if that badge exists
   if (is_array($badges)){
     foreach($badges as $k => $badge){
       if( isset($badge['badge_id']) ){
         if ( $badge['badge_id'] == $ach_ID ) {
           unset($badges[$k]);
         }
       }
     }
     update_user_meta($user_id, '_userpro_badges', $badges);
   }

 }

 //*************** Detect user following ***************\\
 function wpachievements_up_follow($args){
   if( !empty($args) ){
     $type='following_user'; $uid=$args['from']; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_following_user_points');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);

     $type='followed_user'; $uid=$args['to']; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_followed_user_points');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }

 //*************** Detect user unfollowing ***************\\
 function wpachievements_up_unfollow($args){
   if( !empty($args) ){
     $type='unfollowing_user'; $uid=$args['from']; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_following_user_points');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, -$points);

     $type='unfollowed_user'; $uid=$args['to']; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_followed_user_points');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, -$points);
   }
 }

 //*************** Detect user verified ***************\\
 function wpachievements_up_verified_user($user_id){
   if( !empty($user_id) ){
     $type='verified_user'; $uid=$user_id; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_verified_user_points');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_up_desc', 10, 6);
 function achievement_up_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  switch($type){
   case 'unfollowed_user': { $text = __('for being unfollowed by a user', 'wpachievements'); } break;
   case 'unfollowing_user': { $text = __('for unfollowing a user', 'wpachievements'); } break;
   case 'followed_user': { $text = __('for followed a user','wpachievements'); } break;
   case 'following_user': { $text = __('for being following by a user','wpachievements'); } break;
   case 'verified_user': { $text = __('for becoming verified','wpachievements'); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_up_desc', 10, 3);
 function quest_up_desc($text='',$type='',$times=''){
  switch($type){
   case 'unfollowed_user': { $text = __('Be unfollowed by a user', 'wpachievements'); } break;
   case 'unfollowing_user': { $text = __('Unfollow a user', 'wpachievements'); } break;
   case 'followed_user': { $text = __('Be followed by a user','wpachievements'); } break;
   case 'following_user': { $text = __('Follow a user','wpachievements'); } break;
   case 'verified_user': { $text = __('Become verified','wpachievements'); } break;
  }
  return $text;
 }

add_filter( 'wpachievements_get_sections_module', 'wpachievements_add_section_up' );
function wpachievements_add_section_up( $sections ) {
	$sections['up'] = __( 'UserPro', 'wpachievements' );
	return $sections;
}
 
//*************** Admin Settings ***************\\
add_filter('wpachievements_achievements_modules_admin_settings', 'wpachievements_up_admin', 10, 3);
function wpachievements_up_admin($defaultsettings, $shortname, $current_section){
  if ( $current_section == 'up' ) { 
    $settings[] = array( 'title' => __( 'UserPro', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'UserPro_options' );
          
    $settings[] = array(
            'title'   => __( 'User Gets Followed', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user gets followed.', 'wpachievements' ),
            'id'      => $shortname.'_followed_user_points',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Follows', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user follows another user.', 'wpachievements' ),
            'id'      => $shortname.'_following_user_points',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Verified', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user becomes verified.', 'wpachievements' ),
            'id'      => $shortname.'_verified_user_points',
            'type'    => 'text',
            'default' => '0',
          );
          
    $settings[] = array( 'type' => 'sectionend', 'id' => 'UserPro_options');

    return $settings;
/**
  * If not, return the standard settings
  **/
	} else {
		return $defaultsettings;
	}    
}  

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_up_admin_events', 10);
 function achievement_up_admin_events(){
   echo '<optgroup label="'.__('UserPro Events', 'wpachievements').'">
     <option value="followed_user">'. __('The user is followed by another', 'wpachievements') .'</option>
     <option value="following_user">'. __('The user follows another', 'wpachievements') .'</option>
     <option value="unfollowed_user">'. __('The user is unfollowed by another', 'wpachievements') .'</option>
     <option value="unfollowing_user">'.__('The user unfollows another', 'wpachievements').'</option>
     <option value="verified_user">'.__('The user becomes verified', 'wpachievements').'</option>';
   echo '</optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_up_admin_triggers', 1, 10);
 function achievement_up_admin_triggers($trigger){
   switch($trigger){
     case 'unfollowed_user': { $trigger = __('The user is unfollowed by another', 'wpachievements'); } break;
     case 'unfollowing_user': { $trigger = __('The user unfollows another', 'wpachievements'); } break;
     case 'followed_user': { $trigger = __('The user is followed by another','wpachievements'); } break;
     case 'following_user': { $trigger = __('The user follows another','wpachievements'); } break;
     case 'verified_user': { $text = __('The user becomes verified','wpachievements'); } break;
   }
   return $trigger;
 }

?>