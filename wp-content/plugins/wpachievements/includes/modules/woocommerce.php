<?php
/**
 * Module Name: WooCommerce Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/WooCommerce
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action('woocommerce_payment_complete', 'wpachievements_wc_order_complete', 10, 1);
 //*************** Detect order completed ***************\\
 function wpachievements_wc_order_complete($order_id){
   $type='wc_order_complete'; $uid=''; $postid=$order_id;
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_wc_order_complete');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);

   $type='wc_user_spends'; $uid=''; $postid=$order_id; $points=0;
   wpachievements_new_activity($type, $uid, $postid, $points);
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_wc_desc', 10, 6);
 function achievement_wc_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  if($times>1){
    $order = __('orders', 'wpachievements');
  } else{
    $order = __('order', 'wpachievements');
  }
  switch($type){
   case 'wc_order_complete': { $text = sprintf( __('for making %s %s', 'wpachievements'), $times, $order); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_wc_desc', 10, 3);
 function quest_wc_desc($text='',$type='',$times=''){
  if($times>1){
    $order = __('orders', 'wpachievements');
  } else{
    $order = __('order', 'wpachievements');
  }
  switch($type){
   case 'wc_order_complete': { $text = sprintf( __('Make %s %s', 'wpachievements'), $times, $order); } break;
  }
  return $text;
 }

add_filter( 'wpachievements_get_sections_module', 'wpachievements_add_section_woocommerce' );
function wpachievements_add_section_woocommerce( $sections ) {
	$sections['woocommerce'] = __( 'WooCommerce', 'wpachievements' );
	return $sections;
}
 
//*************** Admin Settings ***************\\
add_filter('wpachievements_achievements_modules_admin_settings', 'wpachievements_wc_admin', 10, 3);
function wpachievements_wc_admin($defaultsettings, $shortname, $current_section){
  if ( $current_section == 'woocommerce' ) {
    $settings[] = array( 'title' => __( 'WooCommerce', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'WooCommerce_options' );
          
    $settings[] = array(
            'title'   => __( 'User Completes Orders', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the users completes an order.', 'wpachievements' ),
            'id'      => $shortname.'_wc_order_complete',
            'type'    => 'text',
            'default' => '0',
          );
   
    $settings[] = array( 'type' => 'sectionend', 'id' => 'WooCommerce_options');

    return $settings;
  /**
  * If not, return the standard settings
  **/
	} else {
		return $defaultsettings;
	}
}  
 
 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_wc_admin_events', 10);
 function achievement_wc_admin_events(){
   echo'<optgroup label="WooCommerce Events">
     <option value="wc_order_complete">'.__('The user completes an order', 'wpachievements').'</option>
     <option value="wc_user_spends">'.__('The user spends at least...', 'wpachievements').'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_wc_admin_triggers', 1, 10);
 function achievement_wc_admin_triggers($trigger){

   switch($trigger){
     case 'wc_order_complete': { $trigger = __('The user completes an order', 'wpachievements'); } break;
     case 'wc_user_spends': { $trigger = __('The user spends at least...', 'wpachievements'); } break;
   }

   return $trigger;

 }
?>