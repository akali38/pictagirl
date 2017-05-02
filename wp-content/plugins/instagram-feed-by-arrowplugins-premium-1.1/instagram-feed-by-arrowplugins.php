<?php 
/*
Plugin Name: Instagram Feed by Arrow Plugins Premium
Plugin URI: 
Description: Add Responsive Instgram Feed
Author: Arrow Plugins
Author URI: https://www.arrowplugins.com
Version: 1.1
License: GplV2
Copyright: 2016 Arrow Plugins
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define( 'IFBA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
include_once('includes/ifba-post-type.php');
include_once('includes/ifba-custom-columns.php');
include_once('includes/ifba-post-meta-boxes.php');
include_once('includes/ifba-save-post.php');
include_once('includes/ifba-shortcode.php');
include_once('includes/ifba-enqueue-scripts.php');


add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'ifba_plugin_action_links' );

function ifba_plugin_action_links( $links ) {
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'edit.php?post_type=ifba_instagram_feed') ) .'">Dashboard</a>';
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'edit.php?post_type=ifba_instagram_feed&page=ifba_form_support') ) .'">Support</a>';
   return $links;
}