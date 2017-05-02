<?php
/**
 * Module Name: Jigoshop Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/Jigoshop
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action('jigoshop_payment_complete', 'wpachievements_js_order_complete', 10, 1);
 //*************** Detect order completed ***************\\
 function wpachievements_js_order_complete($order_id){
   $type='js_order_complete'; $uid=''; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_js_order_complete');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_js_desc', 10, 6);
 function achievement_js_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $order = __('orders', 'wpachievements');
  } else{
    $order = __('order', 'wpachievements');
  }
  switch($type){
   case 'js_order_complete': { $text = sprintf( __('for making %s %s', 'wpachievements'), $times, $order); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_js_desc', 10, 3);
 function quest_js_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $order = __('orders', 'wpachievements');
  } else{
    $order = __('order', 'wpachievements');
  }
  switch($type){
   case 'js_order_complete': { $text = sprintf( __('Make %s %s', 'wpachievements'), $times, $order); } break;
  }
  return $text;
 }

add_filter( 'wpachievements_get_sections_module', 'wpachievements_add_section_js' );
function wpachievements_add_section_js( $sections ) {
	$sections['js'] = __( 'Jigoshop', 'wpachievements' );
	return $sections;
}
  
//*************** Admin Settings ***************\\
add_filter('wpachievements_achievements_modules_admin_settings', 'wpachievements_js_admin', 10, 3 );
function wpachievements_js_admin($defaultsettings, $shortname, $current_section){
  if ( $current_section == 'js' ) {
    $settings[] = array( 'title' => __( 'Jigoshop', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'Jigoshop_options' );
          
    $settings[] = array(
            'title'   => __( 'User Completes Orders', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the users completes an order.', 'wpachievements' ),
            'id'      => $shortname.'_js_order_complete',
            'type'    => 'text',
            'default' => '0',
          );
   
    $settings[] = array( 'type' => 'sectionend', 'id' => 'Jigoshop_options');

    return $settings;
  /**
  * If not, return the standard settings
  **/
	} else {
		return $defaultsettings;
	}  
} 

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_js_admin_events', 10);
 function achievement_js_admin_events(){
   echo'<optgroup label="Jigoshop Events">
     <option value="js_order_complete">'.__('The user completes an order', 'wpachievements').'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_ia_admin_triggers', 1, 10);
 function achievement_ia_admin_triggers($trigger){

   switch($trigger){
     case 'js_order_complete': { $trigger = __('The user completes an order', 'wpachievements'); } break;
   }

   return $trigger;

 }
?>