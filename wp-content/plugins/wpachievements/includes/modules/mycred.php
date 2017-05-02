<?php
/**
 * Module Name: myCRED Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/myCRED
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;
 //*************** Actions ***************\\
 add_action('mycred_update_user_balance','wpa_mycred_update_user_balance');
 //*************** Functions that handles the point Descriptions ***************\\
 function myCRED_Desc($ref,$creds,$entry,$log_entry){
   $mycred = mycred();
   return $mycred->parse_template_tags( $entry, $log_entry );
 }
 function wpa_mycred_update_user_balance( $user_id='', $current_balance='', $amount='', $cred_id='' ){
   if( !empty($user_id) && !empty($amount) && $amount == 0 ){
     if( $amount > 0 ){
       wpachievements_increase_points( $user_id, abs($amount) );
     } else{
       wpachievements_decrease_points( $user_id, abs($amount) );
     }
   }
 }

add_filter( 'wpachievements_get_sections_module', 'wpachievements_add_section_mycred' );
function wpachievements_add_section_mycred( $sections ) {
	$sections['mycred'] = __( 'MyCred', 'wpachievements' );
	return $sections;
}
 
//*************** Admin Settings ***************\\
add_filter('wpachievements_achievements_modules_admin_settings', 'wpachievements_mycred_admin', 10, 3);
function wpachievements_mycred_admin($defaultsettings, $shortname, $current_section){
  if ( $current_section == 'mycred' ) { 
    $settings[] = array( 'title' => __( 'MyCred', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'MyCred_options' );
    
    $settings[] = array(
            'title'   => __( 'myCRED Points Type', 'wpachievements' ),
            'desc'    => __( 'Enter the unique id of the point type you wish to use.<br/><strong>Note:</strong> Leave blank to use default.', 'wpachievements' ),
            'id'      => $shortname.'_mycred_point_type',
            'type'    => 'text',
            'default' => '',
          );  

    $settings[] =  array( 'type' => 'sectionend', 'id' => 'MyCred_options');

    return $settings;
  /**
  * If not, return the standard settings
  **/
	} else {
		return $defaultsettings;
	}
} 
?>