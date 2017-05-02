<?php
/**
 * Module Name: BuddyPress Links Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/BuddyPress-Links
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action('bp_links_create_complete','my_bp_bplink_add_cppoints');
 add_action('bp_links_cast_vote_success','my_bp_bplink_vote_add_cppoints');
 add_action('bp_links_posted_update','my_bp_bplink_comment_add_cppoints');
 //*************** Detect New Link Creation ***************\\
 function my_bp_bplink_add_cppoints(){
   $type='cp_bp_link_added'; $uid=''; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_gift_given');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
 }
 //*************** Detect New Link Vote ***************\\
 function my_bp_bplink_vote_add_cppoints(){
   $type='cp_bp_link_voted'; $uid=''; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_link_voted');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
 }
 //*************** Detect New Link Comment ***************\\
 function my_bp_bplink_comment_add_cppoints(){
   $type='cp_bp_link_comment'; $uid=''; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_link_comment');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_bplink_desc', 10, 6);
 function achievement_bplink_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $link = __('links', 'wpachievements');
  } else{
    $link = __('link', 'wpachievements');
  }
  switch($type){
   case 'cp_bp_link_added': { $text = sprintf( __('for adding %s %s', 'wpachievements'), $times, $link); } break;
   case 'cp_bp_link_voted': { $text = sprintf( __('for voting on %s %s', 'wpachievements'), $times, $link); } break;
   case 'cp_bp_link_comment': { $text = sprintf( __('for commenting on %s %s', 'wpachievements'), $times, $link); } break;
   case 'cp_bp_link_delete': { $text = sprintf( __('for deleting %s %s', 'wpachievements'), $times, $link); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_bplink_desc', 10, 3);
 function quest_bplink_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $link = __('links', 'wpachievements');
  } else{
    $link = __('link', 'wpachievements');
  }
  switch($type){
   case 'cp_bp_link_added': { $text = sprintf( __('Add %s %s', 'wpachievements'), $times, $link); } break;
   case 'cp_bp_link_voted': { $text = sprintf( __('Vote on %s %s', 'wpachievements'), $times, $link); } break;
   case 'cp_bp_link_comment': { $text = sprintf( __('Comment on %s %s', 'wpachievements'), $times, $link); } break;
   case 'cp_bp_link_delete': { $text = sprintf( __('Delete %s %s', 'wpachievements'), $times, $link); } break;
  }
  return $text;
 }

add_filter( 'wpachievements_get_sections_module', 'wpachievements_add_section_bplink' );
function wpachievements_add_section_bplink( $sections ) {
	$sections['bplink'] = __( 'BuddyPress Links', 'wpachievements' );
	return $sections;
}
 
//*************** Admin Settings ***************\\
add_filter('wpachievements_achievements_modules_admin_settings', 'wpachievements_bplink_admin', 10, 3);
function wpachievements_bplink_admin($defaultsettings, $shortname, $current_section){
  if ( $current_section == 'bplink' ) {
    $settings[] = array( 'title' => __( 'BuddyPress Links', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'BBuddyPressLinks_options' );
          
    $settings[] = array(
            'title'   => __( 'User Adding Links', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user adds a link.', 'wpachievements' ),
            'id'      => $shortname.'_bp_link_added',
            'type'    => 'text',
            'default' => '0',
          );
          
    $settings[] = array(
            'title'   => __( 'User Link Voting', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user votes on a link.', 'wpachievements' ),
            'id'      => $shortname.'_bp_link_voted',
            'type'    => 'text',
            'default' => '0',
          ); 

    $settings[] = array(
            'title'   => __( 'User Link Comments', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user comments on a link.', 'wpachievements' ),
            'id'      => $shortname.'_bp_link_comment',
            'type'    => 'text',
            'default' => '0',
          ); 
          
    $settings[] = array( 'type' => 'sectionend', 'id' => 'BBuddyPressLinks_options');

    return $settings;
  /**
  * If not, return the standard settings
  **/
	} else {
		return $defaultsettings;
	} 
}

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_bplink_admin_events', 10);
 function achievement_bplink_admin_events(){
   echo'<optgroup label="BuddyPress Links Events">
     <option value="cp_bp_link_added">'.__('The user adds a link', 'wpachievements').'</option>
     <option value="cp_bp_link_voted">'.__('The user votes on a link', 'wpachievements').'</option>
     <option value="cp_bp_link_comment">'.__('The user comments on a link', 'wpachievements').'</option>
     <option value="cp_bp_link_delete">'.__('The user delets a link', 'wpachievements').'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_bplink_admin_triggers', 1, 10);
 function achievement_bplink_admin_triggers($trigger){

   switch($trigger){
     case 'cp_bp_link_added': { $trigger = __('The user adds a link', 'wpachievements'); } break;
     case 'cp_bp_link_voted': { $trigger = __('The user votes on a link', 'wpachievements'); } break;
     case 'cp_bp_link_comment': { $trigger = __('The user comments on a link', 'wpachievements'); } break;
     case 'cp_bp_link_delete': { $trigger = __('The user delets a link', 'wpachievements'); } break;
   }

   return $trigger;

 }

?>