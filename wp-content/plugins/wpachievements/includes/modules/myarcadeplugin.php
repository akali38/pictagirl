<?php
/**
 * Module Name: MyArcadePlugin Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/MyArcadePlugin
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action('myarcade_update_play_points', 'wpachievements_play_game');
 add_action('myarcade_new_score', 'wpachievements_submit_score');
 add_action('myarcade_new_highscore', 'wpachievements_new_highscore');
 add_action('myarcade_new_medal', 'wpachievements_new_medal');
 //*************** Detect playing of a game ***************\\
 function wpachievements_play_game() {
   if( is_user_logged_in() ) {
     $type='playedgame'; $uid=''; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_play_points');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }
 //*************** Detect submitting a score ***************\\
 function wpachievements_submit_score() {
   if( is_user_logged_in() ) {
     $type='scoresubmit'; $uid=''; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_score_points');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }
 //*************** Detect new highscore ***************\\
 function wpachievements_new_highscore() {
   if( is_user_logged_in() ) {
     $type='newhighscore'; $uid=''; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_highscore_points');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }
 //*************** Dectect new medal ***************\\
 function wpachievements_new_medal() {
   if( is_user_logged_in() ) {
     $type='newmedal'; $uid=''; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_medal_points');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_map_desc', 10, 6);
 function achievement_map_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $highscore = __('highscores', 'wpachievements');
    $score = __('scores', 'wpachievements');
    $medal = __('medals', 'wpachievements');
  } else{
    $highscore = __('highscore', 'wpachievements');
    $score = __('score', 'wpachievements');
    $medal = __('medal', 'wpachievements');
  }
  switch($type){
   case 'playedgame': { $text = sprintf( __('for playing %s %s', 'wpachievements'), $times, $pt); } break;
   case 'newhighscore': { $text = sprintf( __('for getting %s %s', 'wpachievements'), $times, $highscore); } break;
   case 'scoresubmit': { $text = sprintf( __('for submitting %s %s', 'wpachievements'), $times, $score); } break;
   case 'newmedal': { $text = sprintf( __('for getting %s %s', 'wpachievements'), $times, $medal); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_map_desc', 10, 3);
 function quest_map_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $highscore = __('highscores', 'wpachievements');
    $score = __('scores', 'wpachievements');
    $medal = __('medals', 'wpachievements');
  } else{
    $highscore = __('highscore', 'wpachievements');
    $score = __('score', 'wpachievements');
    $medal = __('medal', 'wpachievements');
  }
  switch($type){
   case 'playedgame': { $text = sprintf( __('Play %s %s', 'wpachievements'), $times, $pt); } break;
   case 'newhighscore': { $text = sprintf( __('Get %s %s', 'wpachievements'), $times, $highscore); } break;
   case 'scoresubmit': { $text = sprintf( __('Submit %s %s', 'wpachievements'), $times, $score); } break;
   case 'newmedal': { $text = sprintf( __('Get %s %s', 'wpachievements'), $times, $medal); } break;
  }
  return $text;
 }

add_filter( 'wpachievements_get_sections_module', 'wpachievements_add_section_myarcadeplugin' );
function wpachievements_add_section_myarcadeplugin( $sections ) {
	$sections['myarcadeplugin'] = __( 'MyArcadePlugin', 'wpachievements' );
	return $sections;
}

//*************** Admin Settings ***************\\
add_filter('wpachievements_achievements_modules_admin_settings', 'wpachievements_map_admin', 10, 3);
function wpachievements_map_admin($defaultsettings, $shortname, $current_section){
  if ( $current_section == 'myarcadeplugin' ) {
    $settings[] = array( 'title' => __( 'MyArcadePlugin', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'MyArcadePlugin_options' );

    $settings[] = array(
            'title'   => __( 'User Playing Games', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user plays a game.', 'wpachievements' ),
            'id'      => $shortname.'_play_points',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Submitting Scores', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user submits a score.', 'wpachievements' ),
            'id'      => $shortname.'_score_points',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Getting Highscores', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user gets a highscore.', 'wpachievements' ),
            'id'      => $shortname.'_highscore_points',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Getting Medals', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user gets a medal.', 'wpachievements' ),
            'id'      => $shortname.'_medal_points',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] =     array( 'type' => 'sectionend', 'id' => 'MyArcadePlugin_options');

    return $settings;
    /**
	 * If not, return the standard settings
	 **/
	} else {
		return $defaultsettings;
	}
}

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_map_admin_events', 10);
 function achievement_map_admin_events(){
   echo '<optgroup label="'.__('MyArcadePlugin Events', 'wpachievements').'">
     <option value="playedgame">'.__('The user plays a game', 'wpachievements').'</option>
     <option value="scoresubmit">'.__('The user submits a new score', 'wpachievements').'</option>
     <option value="newhighscore">'.__('The user gets a new highscore', 'wpachievements').'</option>
     <option value="newmedal">'.__('The user gets a new medal', 'wpachievements').'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_map_admin_triggers', 1, 10);
 function achievement_map_admin_triggers($trigger){

   switch($trigger){
     case 'playedgame': { $trigger = __('The user plays a game', 'wpachievements'); } break;
     case 'scoresubmit': { $trigger = __('The user submits a new score', 'wpachievements'); } break;
     case 'newhighscore': { $trigger = __('The user gets a new highscore', 'wpachievements'); } break;
     case 'newmedal': { $trigger = __('The user gets a new medal', 'wpachievements'); } break;
   }

   return $trigger;

 }

/**
 *************************************************
 *    A D D I T I O N A L   F U N C T I O N S    *
 *************************************************
 */
 if ( is_active_widget('', '','mabp_user_login') ) {
  function modify_user_widget() {
    list($lvlstat,$wid) = wpa_ranks_widget();
    echo "<script>
    jQuery(document).ready(function(){
      jQuery('.userinfo').append('".$lvlstat."');
      jQuery('.pb_bar_user_login').animate({width:'".$wid."px'},1500);
    });
    </script>";
  }
  add_action( 'wp_footer', 'modify_user_widget' );
 }
?>
