<?php
/**
 * Module Name: Gravity Forms Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/Gravity-Forms
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action("gform_after_submission", "wpachievements_gform_submission", 10, 2);
 //*************** Detect Form Submission ***************\\
 function wpachievements_gform_submission( $entry, $form ){
   if( is_user_logged_in() ){
     $type='gform_sub'; $uid=''; $postid=$entry["form_id"];
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_gform_points');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_gform_desc', 10, 6);
 function achievement_gform_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $form = __('forms', 'wpachievements');
  } else{
    $form = __('form', 'wpachievements');
  }
  if( !empty($data) ){
    switch($type){
     case 'gform_sub': { $text = sprintf( __('for submitting the form: %s', 'wpachievements'), $data); } break;
    }
  } else{
    switch($type){
     case 'gform_sub': { $text = sprintf( __('for submitting %s %s', 'wpachievements'), $times, $form); } break;
    }
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_gform_desc', 10, 3);
 function quest_gform_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $form = __('forms', 'wpachievements');
  } else{
    $form = __('form', 'wpachievements');
  }
  switch($type){
   case 'gform_sub': { $text = sprintf( __('Submit %s %s', 'wpachievements'), $times, $form); } break;
  }
  return $text;
 }

add_filter( 'wpachievements_get_sections_module', 'wpachievements_add_section_gform' );
function wpachievements_add_section_gform( $sections ) {
	$sections['gform'] = __( 'Gravity Forms', 'wpachievements' );
	return $sections;
}
  
//*************** Admin Settings ***************\\
add_filter('wpachievements_achievements_modules_admin_settings', 'wpachievements_gform_admin', 10, 3);
function wpachievements_gform_admin($defaultsettings, $shortname, $current_section){
  if ( $current_section == 'gform' ) {
    $settings[] = array( 'title' => __( 'Gravity Forms', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'GravityForms_options' );
          
    $settings[] = array(
            'title'   => __( 'User Submitting Forms', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user submits a form.', 'wpachievements' ),
            'id'      => $shortname.'_gform_points',
            'type'    => 'text',
            'default' => '0',
          );
   
    $settings[] = array( 'type' => 'sectionend', 'id' => 'GravityForms_options');

    return $settings;
  /**
  * If not, return the standard settings
  **/
	} else {
		return $defaultsettings;
	} 
} 

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_gform_admin_events', 10);
 function achievement_gform_admin_events(){
   echo'<optgroup label="Gravity Forms Events">
     <option value="gform_sub">'.__('The user submits a form', 'wpachievements').'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_gform_admin_triggers', 1, 10);
 function achievement_gform_admin_triggers($trigger){

   switch($trigger){
     case 'gform_sub': { $trigger = __('The user submits a form', 'wpachievements'); } break;
   }

   return $trigger;

 }

?>