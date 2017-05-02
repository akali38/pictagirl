<?php
/**
 * Module Name: WP-Courseware Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/WP-Courseware
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action('wpcw_quiz_graded', 'wpachievements_wpcw_quiz_complete', 10, 4);
 add_action('wpcw_user_completed_module', 'wpachievements_wpcw_module_complete', 10, 3);
 add_action('wpcw_user_completed_course', 'wpachievements_wpcw_course_complete', 10, 3);
 //*************** Detect Quiz Completed ***************\\
 function wpachievements_wpcw_quiz_complete($userID, $quizDetails, $correctPercentage, $extra){
   if(!empty($userID)){
     $type='wpcw_quiz_pass'; $uid=$userID;
     if($quizDetails->quiz_id){
       $postid = $quizDetails->quiz_id;
     } else{
       $postid = '';
     }
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_wpcw_quiz_pass');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
     if($correctPercentage == '100'){
       $type='wpcw_quiz_perfect';
       if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
         $points = (int) wpachievements_get_site_option('wpachievements_wpcw_quiz_perfect');
       }
       if(empty($points)){$points=0;}
       wpachievements_new_activity($type, $uid, $postid, $points);
     }
   }
 }
 //*************** Detect Module Completed ***************\\
 function wpachievements_wpcw_module_complete($userID, $unitID, $unitParentData){
   if(!empty($userID)){
     $type='wpcw_module_complete'; $uid=$userID; $postid=$unitID;
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_wpcw_module_complete');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }
 //*************** Detect Course Completed ***************\\
 function wpachievements_wpcw_course_complete($userID, $unitID, $unitParentData){
   if(!empty($userID)){
     $type='wpcw_course_complete'; $uid=$userID;
     if($unitParentData->course_id){
       $postid = $unitParentData->course_id;
     } else{
       $postid = '';
     }
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_wpcw_course_complete');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_wpcw_desc', 10, 6);
 function achievement_wpcw_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $quiz = __('quizzes', 'wpachievements');
    $score = __('scores', 'wpachievements');
    $module = __('modules', 'wpachievements');
    $course = __('courses', 'wpachievements');
  } else{
    $quiz = __('quiz', 'wpachievements');
    $score = __('score', 'wpachievements');
    $module = __('module', 'wpachievements');
    $course = __('course', 'wpachievements');
  }
  switch($type){
   case 'wpcw_quiz_pass': { $text = sprintf( __('for passing %s %s', 'wpachievements'), $times, $quiz); } break;
   case 'wpcw_quiz_perfect': { $text = sprintf( __('for getting %s perfect %s', 'wpachievements'), $times, $score); } break;
   case 'wpcw_module_complete': { $text = sprintf( __('for completing %s %s', 'wpachievements'), $times, $module); } break;
   case 'wpcw_course_complete': { $text = sprintf( __('for completing %s %s', 'wpachievements'), $times, $course); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_wpcw_desc', 10, 3);
 function quest_wpcw_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $quiz = __('quizzes', 'wpachievements');
    $score = __('scores', 'wpachievements');
    $module = __('modules', 'wpachievements');
    $course = __('courses', 'wpachievements');
  } else{
    $quiz = __('quiz', 'wpachievements');
    $score = __('score', 'wpachievements');
    $module = __('module', 'wpachievements');
    $course = __('course', 'wpachievements');
  }
  switch($type){
   case 'wpcw_quiz_pass': { $text = sprintf( __('Pass %s %s', 'wpachievements'), $times, $quiz); } break;
   case 'wpcw_quiz_perfect': { $text = sprintf( __('Get %s perfect %s', 'wpachievements'), $times, $score); } break;
   case 'wpcw_module_complete': { $text = sprintf( __('Complete %s %s', 'wpachievements'), $times, $module); } break;
   case 'wpcw_course_complete': { $text = sprintf( __('Complete %s %s', 'wpachievements'), $times, $course); } break;
  }
  return $text;
 }

add_filter( 'wpachievements_get_sections_module', 'wpachievements_add_section_wpcw' );
function wpachievements_add_section_wpcw( $sections ) {
	$sections['wpcw'] = __( 'WP-Courseware', 'wpachievements' );
	return $sections;
}

//*************** Admin Settings ***************\\
add_filter('wpachievements_achievements_modules_admin_settings', 'wpachievements_wpcw_admin', 10, 3);
function wpachievements_wpcw_admin($defaultsettings, $shortname, $current_section){
  if ( $current_section == 'wpcw' ) {
    $settings[] = array( 'title' => __( 'WP-Courseware', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'WPCourseware_options' );
          
    $settings[] = array(
            'title'   => __( 'User Passes Quiz', 'wpachievements' ),
            'desc'    => __( 'Points awarded when a user passes a quiz.', 'wpachievements' ),
            'id'      => $shortname.'_wpcw_quiz_pass',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Gets Perfect Quiz Score', 'wpachievements' ),
            'desc'    => __( 'Points awarded when a user gets 100% on a quiz.', 'wpachievements' ),
            'id'      => $shortname.'_wpcw_quiz_perfect',
            'type'    => 'text',
            'default' => '0',
          );
    
    $settings[] = array(
            'title'   => __( 'User Completes Module', 'wpachievements' ),
            'desc'    => __( 'Points awarded when a user completes a module.', 'wpachievements' ),
            'id'      => $shortname.'_wpcw_module_complete',
            'type'    => 'text',
            'default' => '0',
          );     

    $settings[] = array(
            'title'   => __( 'Users Completes Course.', 'wpachievements' ),
            'desc'    => __( 'Points awarded when a user completes a course.', 'wpachievements' ),
            'id'      => $shortname.'_wpcw_course_complete',
            'type'    => 'text',
            'default' => '0',
          );
          
    $settings[] = array( 'type' => 'sectionend', 'id' => 'WPCourseware_options');

    return $settings;
/**
  * If not, return the standard settings
  **/
	} else {
		return $defaultsettings;
	}       
}  

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_wpcw_admin_events', 10);
 function achievement_wpcw_admin_events(){
   echo'<optgroup label="WP-Courseware Events">
     <option value="wpcw_quiz_pass">'.__('The user passes a quiz', 'wpachievements').'</option>
     <option value="wpcw_quiz_perfect">'.__('The user gets 100% on a quiz', 'wpachievements').'</option>
     <option value="wpcw_module_complete">'.__('The user completes a module', 'wpachievements').'</option>
     <option value="wpcw_course_complete">'.__('The user completes a course', 'wpachievements').'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_wpcw_admin_triggers', 1, 10);
 function achievement_wpcw_admin_triggers($trigger){

   switch($trigger){
     case 'wpcw_quiz_pass': { $trigger = __('The user passes a quiz', 'wpachievements'); } break;
     case 'wpcw_quiz_perfect': { $trigger = __('The user gets 100% on a quiz', 'wpachievements'); } break;
     case 'wpcw_module_complete': { $trigger = __('The user completes a module', 'wpachievements'); } break;
     case 'wpcw_course_complete': { $trigger = __('The user completes a course', 'wpachievements'); } break;
   }

   return $trigger;

 }
?>