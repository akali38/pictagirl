<?php
/**
 * Module Name: LearnDash Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/LearnDash
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action('learndash_completed', 'wpachievements_ld_quiz_complete', 10, 1);
 add_action('learndash_quiz_completed', 'wpachievements_ld_quiz_complete', 10, 1);
 add_action('learndash_lesson_completed', 'wpachievements_ld_lesson_complete', 10, 1);
 add_action('learndash_course_completed', 'wpachievements_ld_course_complete', 10, 1);
 //*************** Detect Quiz Completed ***************\\
 function wpachievements_ld_quiz_complete($quiz){
   if(!empty($quiz)){
     $current_user = wp_get_current_user();
     if( isset($quiz['pro_quizid']) ){
       $postid=$quiz['pro_quizid'];
     } else{
       $postid=$quiz['quiz']->ID;
     }
     if($quiz['pass'] == '1'){
       if($quiz['score'] == $quiz['count']){
         $type='ld_quiz_perfect'; $uid=$current_user->ID;
         if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
           $points = (int) wpachievements_get_site_option('wpachievements_ld_quiz_perfect');
         }
         if(empty($points)){$points=0;}
         wpachievements_new_activity($type, $uid, $postid, $points);
       }
       $type='ld_quiz_pass'; $uid=$current_user->ID;
       if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
         $points = (int) wpachievements_get_site_option('wpachievements_ld_quiz_pass');
       }
       if(empty($points)){$points=0;}
       wpachievements_new_activity($type, $uid, $postid, $points);
     } else{
       $type='ld_quiz_fail'; $uid=$current_user->ID;
       if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
         $points = (int) wpachievements_get_site_option('wpachievements_ld_quiz_fail');
       }
       if(empty($points)){$points=0;}
       wpachievements_new_activity($type, $uid, $postid, -$points);
     }
   }
 }
 //*************** Detect Lesson Completed ***************\\
 function wpachievements_ld_lesson_complete($lesson){
   if(!empty($lesson)){
     $type='ld_lesson_complete'; $uid=$lesson['user']->data->ID; $postid=$lesson['lesson']->ID;
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_ld_lesson_complete');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }
 //*************** Detect Course Completed ***************\\
 function wpachievements_ld_course_complete($course){
   if(!empty($course)){
     $type='ld_course_complete'; $uid=$course['user']->data->ID; $postid=$course['course']->ID;
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_ld_course_complete');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_ld_desc', 10, 6);
 function achievement_ld_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $quiz = __('quizzes', 'wpachievements');
    $score = __('scores', 'wpachievements');
    $lesson = __('lessons', 'wpachievements');
    $course = __('courses', 'wpachievements');
  } else{
    $quiz = __('quiz', 'wpachievements');
    $score = __('score', 'wpachievements');
    $lesson = __('lesson', 'wpachievements');
    $course = __('course', 'wpachievements');
  }
  switch($type){
   case 'ld_quiz_pass': { $text = sprintf( __('for passing %s %s', 'wpachievements'), $times, $quiz); } break;
   case 'ld_quiz_fail': { $text = sprintf( __('for failing %s %s', 'wpachievements'), $times, $quiz); } break;
   case 'ld_quiz_perfect': { $text = sprintf( __('for getting %s perfect %s', 'wpachievements'), $times, $score); } break;
   case 'ld_lesson_complete': { $text = sprintf( __('for completing %s %s', 'wpachievements'), $times, $lesson); } break;
   case 'ld_course_complete': { $text = sprintf( __('for completing %s %s', 'wpachievements'), $times, $course); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_ld_desc', 10, 3);
 function quest_ld_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $quiz = __('quizzes', 'wpachievements');
    $score = __('scores', 'wpachievements');
    $lesson = __('lessons', 'wpachievements');
    $course = __('courses', 'wpachievements');
  } else{
    $quiz = __('quiz', 'wpachievements');
    $score = __('score', 'wpachievements');
    $lesson = __('lesson', 'wpachievements');
    $course = __('course', 'wpachievements');
  }
  switch($type){
   case 'ld_quiz_pass': { $text = sprintf( __('Pass %s %s', 'wpachievements'), $times, $quiz); } break;
   case 'ld_quiz_fail': { $text = sprintf( __('Fail %s %s', 'wpachievements'), $times, $quiz); } break;
   case 'ld_quiz_perfect': { $text = sprintf( __('Get %s perfect %s', 'wpachievements'), $times, $score); } break;
   case 'ld_lesson_complete': { $text = sprintf( __('Complete %s %s', 'wpachievements'), $times, $lesson); } break;
   case 'ld_course_complete': { $text = sprintf( __('Complete %s %s', 'wpachievements'), $times, $course); } break;
  }
  return $text;
 }
 
add_filter( 'wpachievements_get_sections_module', 'wpachievements_add_section_ld' );
function wpachievements_add_section_ld( $sections ) {
	$sections['ld'] = __( 'LearnDash', 'wpachievements' );
	return $sections;
}
 
//*************** Admin Settings ***************\\
add_filter('wpachievements_achievements_modules_admin_settings', 'wpachievements_ld_admin', 10, 3);
function wpachievements_ld_admin($defaultsettings, $shortname, $current_section){
  if ( $current_section == 'ld' ) {
    $settings[] = array( 'title' => __( 'LearnDash', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'LearnDash_options' );
          
    $settings[] = array(
            'title'   => __( 'User Passes Quiz', 'wpachievements' ),
            'desc'    => __( 'Points awarded when a user passes a quiz.', 'wpachievements' ),
            'id'      => $shortname.'_ld_quiz_pass',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Fails Quiz', 'wpachievements' ),
            'desc'    => __( 'Points awarded when a user fails a quiz.', 'wpachievements' ),
            'id'      => $shortname.'_ld_quiz_fail',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Gets Perfect Quiz Score', 'wpachievements' ),
            'desc'    => __( 'Points awarded when a user gets 100% on a quiz.', 'wpachievements' ),
            'id'      => $shortname.'_ld_quiz_perfect',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Completes Lesson', 'wpachievements' ),
            'desc'    => __( 'Points awarded when a user completes a lesson.', 'wpachievements' ),
            'id'      => $shortname.'_ld_lesson_complete',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'Users Completes Course.', 'wpachievements' ),
            'desc'    => __( 'Points awarded when a user completes a course.', 'wpachievements' ),
            'id'      => $shortname.'_ld_course_complete',
            'type'    => 'text',
            'default' => '0',
          );
          
    $settings[] = array( 'type' => 'sectionend', 'id' => 'LearnDash_options');

    return $settings;
  /**
  * If not, return the standard settings
  **/
	} else {
		return $defaultsettings;
	}
}  

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_ld_admin_events', 10);
 function achievement_ld_admin_events(){
   echo'<optgroup label="LearnDash Events">
     <option value="ld_quiz_pass">'.__('The user passes a quiz', 'wpachievements').'</option>
     <option value="ld_quiz_fail">'.__('The user fails a quiz', 'wpachievements').'</option>
     <option value="ld_quiz_perfect">'.__('The user gets 100% on a quiz', 'wpachievements').'</option>
     <option value="ld_lesson_complete">'.__('The user completes a lesson', 'wpachievements').'</option>
     <option value="ld_course_complete">'.__('The user completes a course', 'wpachievements').'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_ld_admin_triggers', 1, 10);
 function achievement_ld_admin_triggers($trigger){

   switch($trigger){
     case 'ld_quiz_pass': { $trigger = __('The user passes a quiz', 'wpachievements'); } break;
     case 'ld_quiz_fail': { $trigger = __('The user fails a quiz', 'wpachievements'); } break;
     case 'ld_quiz_perfect': { $trigger = __('The user gets 100% on a quiz', 'wpachievements'); } break;
     case 'ld_lesson_complete': { $trigger = __('The user completes a lesson', 'wpachievements'); } break;
     case 'ld_course_complete': { $trigger = __('The user completes a course', 'wpachievements'); } break;
   }

   return $trigger;

 }
?>