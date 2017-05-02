<?php
/**
 * Module Name: WooCommerce Points and Rewards Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/WooCommerce-Points-and-Rewards
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;
 //*************** Actions ***************\\
 add_filter( 'wc_points_rewards_event_description', 'wpachievements_wcpar_description', 10, 3 );

 //*************** Functions that handles the point Descriptions ***************\\
 function wpachievements_wcpar_description( $event_description, $event_type, $event ) {
   switch ( $event_type ) {
     case 'wpachievements_achievement': $event_description = sprintf( __( '%s earned for getting the achievement: %s', 'wpachievements' ), $event->points, $event->data['achievement_id'] ); break;
     case 'wpachievements_achievement_added': $event_description = sprintf( __( '%s earned for admin adding the achievement: %s', 'wpachievements' ), $event->points, $event->data['achievement_id']  ); break;
     case 'wpachievements_achievement_removed': $event_description = sprintf( __( '%s removed by admin removing the achievement: %s', 'wpachievements' ), $event->points, $event->data['achievement_id']  ); break;
     case 'wpachievements_achievement_edited_add': $event_description = sprintf( __( '%s earned because an achievements points have been increased.', 'wpachievements' ), $event->points ); break;
     case 'wpachievements_achievement_edited_remove': $event_description = sprintf( __( '%s earned because an achievements points have been decreased.', 'wpachievements' ), $event->points ); break;


     case 'wpachievements_quest': $event_description = sprintf( __( '%s earned for getting the quest: %s', 'wpachievements' ), $event->points, $event->data['achievement_id'] ); break;
     case 'wpachievements_quest_added': $event_description = sprintf( __( '%s earned for admin adding the quest: %s', 'wpachievements' ), $event->points, $event->data['achievement_id']  ); break;
     case 'wpachievements_quest_removed': $event_description = sprintf( __( '%s removed by admin removing the quest: %s', 'wpachievements' ), $event->points, $event->data['achievement_id']  ); break;
     case 'wpachievements_quest_edited_add': $event_description = sprintf( __( '%s earned because an quest points have been increased.', 'wpachievements' ), $event->points ); break;
     case 'wpachievements_quest_edited_remove': $event_description = sprintf( __( '%s earned because an quest points have been decreased.', 'wpachievements' ), $event->points ); break;
   }
   return $event_description;
 }

add_filter( 'wpachievements_get_sections_module', 'wpachievements_add_section_wcpr' );
function wpachievements_add_section_wcpr( $sections ) {
	$sections['wcpr'] = __( 'WooCommerce Points and Rewards', 'wpachievements' );
	return $sections;
}
  
//*************** Admin Settings ***************\\
add_filter('wpachievements_achievements_modules_admin_settings', 'achievements_wcpr_admin', 10, 3);
function achievements_wcpr_admin($defaultsettings, $shortname, $current_section){
  if ( $current_section == 'wcpr' ) { 
    $settings[] = array( 'title' => __( 'WooCommerce Points and Rewards', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'WooCommerce_options' );
    
    $settings[] = array(
            'title'   => __( 'Sync All Points', 'wpachievements' ),
            'desc'    => __( 'This will make all points gained be added to WooCommerce Points and Rewards.<br/><strong>Note:</strong> This is not recommeded as user can gain points quickly.', 'wpachievements' ),
            'id'      => $shortname.'_wcpr_sync_enabled',
            'type'    => 'checkbox',
            'default' => '',
          );
          
    $settings[] =  array( 'type' => 'sectionend', 'id' => 'WooCommerce_options');

    return $settings;
/**
  * If not, return the standard settings
  **/
	} else {
		return $defaultsettings;
	}      
 } 
?>