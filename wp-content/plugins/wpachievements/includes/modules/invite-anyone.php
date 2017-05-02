<?php
/**
 * Module Name: Invite Anyone Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/Invite-Anyone
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action('sent_email_invite', 'wpachievements_invite_sent', 10, 3);
 add_action('accepted_email_invite', 'wpachievements_invite_accepted', 10, 2);
 //*************** Detect adding of favorite ***************\\
 function wpachievements_invite_sent($user_id, $email, $group){
   $type='sentinvite'; $uid=$user_id; $postid=''; $points=0;
   wpachievements_new_activity($type, $uid, $postid, $points);
 }
 //*************** Detect removal of favorite ***************\\
 function wpachievements_invite_accepted($invited_user_id, $inviters){
   if( is_array($inviters) ){
     foreach($inviters as $inviter_id){
       $type='inviteacceptance'; $uid=$inviter_id; $postid='';
       if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
         $points = (int) wpachievements_get_site_option('wpachievements_iv_invite_acceptance_points');
       }
       if(empty($points)){$points=0;}
       wpachievements_new_activity($type, $uid, $postid, $points);
     }
   } else{
     $type='inviteacceptance'; $uid=$inviters; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_iv_invite_acceptance_points');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_ia_desc', 10, 6);
 function achievement_ia_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $invite = __('invites', 'wpachievements');
  } else{
    $invite = __('invite', 'wpachievements');
  }
  switch($type){
   case 'sentinvite': { $text = sprintf( __('for sending %s %s', 'wpachievements'), $times, $invite); } break;
   case 'inviteacceptance': { $text = sprintf( __('for %s %s being accepted', 'wpachievements'), $times, $invite); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_ia_desc', 10, 3);
 function quest_ia_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $invite = __('invites', 'wpachievements');
  } else{
    $invite = __('invite', 'wpachievements');
  }
  switch($type){
   case 'sentinvite': { $text = sprintf( __('Send %s %s', 'wpachievements'), $times, $invite); } break;
   case 'inviteacceptance': { $text = sprintf( __('Have %s %s accepted', 'wpachievements'), $times, $invite); } break;
  }
  return $text;
 }

add_filter( 'wpachievements_get_sections_module', 'wpachievements_add_section_ia' );
function wpachievements_add_section_ia( $sections ) {
	$sections['ia'] = __( 'Invite Anyone', 'wpachievements' );
	return $sections;
}
  
//*************** Admin Settings ***************\\
add_filter('wpachievements_achievements_modules_admin_settings', 'wpachievements_ia_admin', 10, 3);
function wpachievements_ia_admin($defaultsettings, $shortname, $current_section){
  if ( $current_section == 'ia' ) {
    $settings[] = array( 'title' => __( 'Invite Anyone', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'InviteAnyone_options' );
          
    $settings[] = array(
            'title'   => __( 'User Invite Accepted', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the users invite is accepted.', 'wpachievements' ),
            'id'      => $shortname.'_iv_invite_acceptance_points',
            'type'    => 'text',
            'default' => '0',
          );
   
    $settings[] = array( 'type' => 'sectionend', 'id' => 'InviteAnyone_options');

    return $settings;
  /**
  * If not, return the standard settings
  **/
	} else {
		return $defaultsettings;
	} 
} 

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_ia_admin_events', 10);
 function achievement_ia_admin_events(){
   echo'<optgroup label="Invite Anyone Events">
     <option value="sentinvite">'.__('The user invites someone', 'wpachievements').'</option>
     <option value="inviteacceptance">'.__('The users invitation is accepted', 'wpachievements').'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_ia_admin_triggers', 1, 10);
 function achievement_ia_admin_triggers($trigger){

   switch($trigger){
     case 'sentinvite': { $trigger = __('The user invites someone', 'wpachievements'); } break;
     case 'inviteacceptance': { $trigger = __('The users invitation is accepted', 'wpachievements'); } break;
   }

   return $trigger;

 }
?>