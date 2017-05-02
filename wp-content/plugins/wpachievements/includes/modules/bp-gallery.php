<?php
/**
 * Module Name: BP Gallery Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/BP-Gallery
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action('gallery_media_upload_complete','my_bp_gallery_upload_add_cppoints');
 //*************** Detect Gallery Upload ***************\\
 function my_bp_gallery_upload_add_cppoints(){
   $type='cp_bp_galery_upload'; $uid=''; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_galery_upload');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_bpg_desc', 10, 6);
 function achievement_bpg_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $time = __('times', 'wpachievements');
  } else{
    $time = __('time', 'wpachievements');
  }
  switch($type){
   case 'cp_bp_galery_upload': { $text = sprintf( __('for uploading to a gallery %s %s', 'wpachievements'), $times, $time); } break;
   case 'cp_bp_galery_delete': { sprintf( __('for deleting from a gallery %s %s', 'wpachievements'), $times, $time); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_bpg_desc', 10, 3);
 function quest_bpg_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $time = __('times', 'wpachievements');
  } else{
    $time = __('time', 'wpachievements');
  }
  switch($type){
   case 'cp_bp_galery_upload': { $text = sprintf( __('Upload to a gallery %s %s', 'wpachievements'), $times, $time); } break;
   case 'cp_bp_galery_delete': { sprintf( __('Delete from a gallery %s %s', 'wpachievements'), $times, $time); } break;
  }
  return $text;
 }

add_filter( 'wpachievements_get_sections_module', 'wpachievements_add_section_bpg' );
function wpachievements_add_section_bpg( $sections ) {
	$sections['bpg'] = __( 'BuddyPress Gallery', 'wpachievements' );
	return $sections;
}
 
//*************** Admin Settings ***************\\
add_filter('wpachievements_achievements_modules_admin_settings', 'wpachievements_bpg_admin', 10, 3);
function wpachievements_bpg_admin($defaultsettings, $shortname, $current_section){
  if ( $current_section == 'bpg' ) {
    $settings[] = array( 'title' => __( 'BuddyPress Gallery', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'BuddyPressGallery_options' );
          
    $settings[] = array(
            'title'   => __( 'User Uploads Image', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user uploads an image.', 'wpachievements' ),
            'id'      => $shortname.'_bp_galery_upload',
            'type'    => 'text',
            'default' => '0',
          );
   
    $settings[] =     array( 'type' => 'sectionend', 'id' => 'BuddyPressGallery_options');

    return $settings;
  /**
  * If not, return the standard settings
  **/
	} else {
		return $defaultsettings;
	}      
}

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_bpg_admin_triggers', 1, 10);
 function achievement_bpg_admin_triggers($trigger){

   switch($trigger){
     case 'cp_bp_galery_upload': { $trigger = __('The user uploads to a gallery', 'wpachievements'); } break;
     case 'cp_bp_galery_delete': { $trigger = __('The user deletes from a gallery', 'wpachievements'); } break;
   }

   return $trigger;

 }

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_bpg_admin_events', 10);
 function achievement_bpg_admin_events(){
   echo'<optgroup label="BuddyPress Gallery Events">
     <option value="cp_bp_galery_upload">'.__('The user uploads to a gallery', 'wpachievements').'</option>
     <option value="cp_bp_galery_delete">'.__('The user deletes from a gallery', 'wpachievements').'</option>
   </optgroup>';
 }
?>