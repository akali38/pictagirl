<?php
/**
 * Module Name: CubePoints Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/CubePoints
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;
 //*************** Actions ***************\\
 add_action('cp_logs_description','cp_admin_logs_desc_wpachievements', 10, 4);
 add_action('cp_formatPoints', 'add_extra_notice');
 add_action('cp_logs_description','cp_admin_logs_desc_achievements', 10, 4);
 //*************** Functions that handles the point Descriptions ***************\\
 function cp_admin_logs_desc_wpachievements($type,$uid,$points,$data){
   if($type=='wpachievements_removed'){ __('Achievement Removed', 'wpachievements'); }
   if($type=='wpachievements_added'){ __('Achievement Added', 'wpachievements'); }
   if($type=='wpachievements_quest_removed'){ __('Quest Removed', 'wpachievements'); }
   else{ return; }
 }
 function add_extra_notice(){
   if(function_exists('is_multisite') && is_multisite()){
     return get_blog_option(1,'cp_prefix') . $points . get_blog_option(1,'cp_suffix');
   } else{
     return get_option('cp_prefix') . $points . get_option('cp_suffix');
   }
 }
 function cp_admin_logs_desc_achievements($type,$uid,$points,$data){
   if(strpos($type,'wpachievements_achievement') !== false){ echo $data; }
   else{ return; }
 }

add_filter( 'wpachievements_get_sections_module', 'wpachievements_add_section_cubepoints' );
function wpachievements_add_section_cubepoints( $sections ) {
	$sections['cubepoints'] = __( 'Cubepoints', 'wpachievements' );
	return $sections;
}
 
//*************** Admin Settings ***************\\
add_filter('wpachievements_achievements_modules_admin_settings', 'wpachievements_cubepoints_admin', 10, 3);
function wpachievements_cubepoints_admin($defaultsettings, $shortname, $current_section){
  if ( $current_section == 'cubepoints' ) {
    $settings[] = array( 'title' => __( 'Cubepoints', 'wpachievements' ), 'type' => 'title', 'desc' => '<strong>CubePoints will overwrite some of these settings.</strong>  To get the best from WPAchievements you should consider deactivating CubePoints.', 'id' => 'Cubepoints_options' );

    $settings[] =  array( 'type' => 'sectionend', 'id' => 'Cubepoints_options');

    return $settings;
  /**
  * If not, return the standard settings
  **/
	} else {
		return $defaultsettings;
	} 
}
?>