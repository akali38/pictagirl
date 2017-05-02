<?php
/**
 * Module Name: GD Star Rating Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/GD-Star-Rating
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action("gdsr_vote", "wpachievements_gd_rating");
 //*************** Detect Post Rating ***************\\
 function wpachievements_gd_rating( $vote_value, $vote_id, $vote_tpl, $vote_size ){
   if( is_user_logged_in() ){
     $type='gd_rating'; $uid=$user; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_gd_star_points');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_gd_desc', 10, 6);
 function achievement_gd_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){$pt = WPACHIEVEMENTS_POST_TEXT."'s";}
  switch($type){
   case 'gd_rating': { $text = sprintf( __('for rating %s %s', 'wpachievements'), $times, $pt); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_gd_desc', 10, 3);
 function quest_gd_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){$pt = WPACHIEVEMENTS_POST_TEXT."'s";}
  switch($type){
   case 'gd_rating': { $text = sprintf( __('Rate %s %s', 'wpachievements'), $times, $pt); } break;
  }
  return $text;
 }

add_filter( 'wpachievements_get_sections_module', 'wpachievements_add_section_gd' );
function wpachievements_add_section_gd( $sections ) {
	$sections['gd'] = __( 'GD Star Rating', 'wpachievements' );
	return $sections;
}
   
//*************** Admin Settings ***************\\
add_filter('wpachievements_achievements_modules_admin_settings', 'wpachievements_gd_admin', 10, 3);
function wpachievements_gd_admin($defaultsettings, $shortname, $current_section){
  if ( $current_section == 'gd' ) {
    $settings[] = array( 'title' => __( 'GD Star Rating', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'GDStarRating_options' );
          
    $settings[] = array(
            'title'   => __( 'User Adding Ratings', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user adds a rating.', 'wpachievements' ),
            'id'      => $shortname.'_gd_star_points',
            'type'    => 'text',
            'default' => '0',
          );
   
    $settings[] =     array( 'type' => 'sectionend', 'id' => 'GDStarRating_options');

    return $settings;
  /**
  * If not, return the standard settings
  **/
	} else {
		return $defaultsettings;
	} 
}

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_gd_admin_events', 10);
 function achievement_gd_admin_events(){
   echo'<optgroup label="GD Star Rating Events">
     <option value="gd_rating">'.__('The user adds a rating', 'wpachievements').'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_gd_admin_triggers', 1, 10);
 function achievement_gd_admin_triggers($trigger){

   switch($trigger){
     case 'gd_rating': { $trigger = __('The user adds a rating', 'wpachievements'); } break;
   }

   return $trigger;

 }
?>