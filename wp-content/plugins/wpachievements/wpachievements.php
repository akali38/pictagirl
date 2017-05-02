<?php
/**
 * Plugin Name: WPAchievements
 * Plugin URI:  http://wpachievements.net
 * Description: Achievements, Quest and Ranks Plugin for WordPress
 * Version:     8.0.2
 * Author:      Powerfusion
 * Author URI:  http://wpachievements.net
 * License:     Codecanyon Split License
 * License URL: http://codecanyon.net/licenses/standard
 * Text Domain: wpachievements
 * Domain Path: /lang
 */

/**
 * Init when WordPress initializes
 *
 * @version 8.0.0
 * @since 8.0.0
 * @access public
 * @return void
 */
function wpachievements_init() {

  // Set required constants
  wpachievements_initial_constants();

  // Load localization
  load_plugin_textdomain( 'wpachievements', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/');

  // Register Post Types
  wpachievements_post_types();

  // Include required files
  wpachievements_includes();

  wpachievements_hooks();
}
add_action( 'init', 'wpachievements_init' );

/**
 * Defines initial constants
 *
 * @version 8.0.0
 * @since   8.0.0
 * @access  public
 * @return  void
 */
function wpachievements_initial_constants() {

  define( 'WPACHIEVEMENTS_PATH', plugin_dir_path(__FILE__) );
  define( 'WPACHIEVEMENTS_URL', plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) ) );

  // Defines for plugin support
  define( 'WPACHIEVEMENTS_MYARCADE', 'F241D3F7A06D35E769A9D67F9524C4CD1' );
  define( 'WPACHIEVEMENTS_MYARCADE_ALT', 'myarcade_init' );
  define( 'WPACHIEVEMENTS_CUBEPOINTS', 'cp_getPoints' );
  define( 'WPACHIEVEMENTS_MYCRED', 'mycred_add' );
  define( 'WPACHIEVEMENTS_BUDDYPRESS', 'BP_VERSION' );
  define( 'WPACHIEVEMENTS_BUDDYPRESS_INT', 'cubepoints_bp_profile' );
  define( 'WPACHIEVEMENTS_FAVORITES', 'wpfp_link' );
  define( 'WPACHIEVEMENTS_SCORES', 'myscore_get_todays_scores' );
  define( 'WPACHIEVEMENTS_CONTESTS', 'myarcadecontest_debuglog' );
  define( 'WPACHIEVEMENTS_GFORMS', 'gform_get_meta' );
  define( 'WPACHIEVEMENTS_GDRATING', 'GDStarRating' );
  define( 'WPACHIEVEMENTS_SIMPLEPRESS', 'sp_setup_globals' );
  define( 'WPACHIEVEMENTS_BBPRESS', 'bbPress' );
  define( 'WPACHIEVEMENTS_BUDDYSTREAM', 'buddystream_init' );
  define( 'WPACHIEVEMENTS_WPECOMMERCE', 'WP_eCommerce' );
  define( 'WPACHIEVEMENTS_INVITE_ANYONE', 'invite_anyone_init' );
  define( 'WPACHIEVEMENTS_WOOCOMMERCE', 'Woocommerce' );
  define( 'WPACHIEVEMENTS_LEARNDASH', 'sfwd_lms_has_access' );
  define( 'WPACHIEVEMENTS_JIGOSHOP', 'jigoshop_init' );
  define( 'WPACHIEVEMENTS_WOOCOMMERCE_PAR', 'WC_Points_Rewards' );
  define( 'WPACHIEVEMENTS_WPCOURSEWARE', 'WPCW_plugin_init' );
  define( 'WPACHIEVEMENTS_USERPRO', 'userpro_init' );

  if( function_exists(WPACHIEVEMENTS_MYARCADE) || function_exists(WPACHIEVEMENTS_MYARCADE_ALT) ){
    define( 'WPACHIEVEMENTS_POST_TEXT', __('Game', 'wpachievements') );
  } else{
    define( 'WPACHIEVEMENTS_POST_TEXT', __('Post', 'wpachievements') );
  }
}

/**
 * Include required files used in admin and on the frontend.
 *
 * @version 8.0.0
 * @since   8.0.0
 * @access  public
 * @return  void
 */
function wpachievements_includes() {

  require_once( 'includes/wpachievements_setup.php' );
  require_once( 'includes/wpachievements_init.php' );
  require_once( 'includes/wpachievements_achievments.php');
  require_once( 'includes/wpachievements_quests.php');
  require_once( 'includes/wpachievements_ranks.php');
  require_once( 'includes/wpachievements_content_lock.php');
  require_once( 'includes/wpachievements_achievements_page_setup.php');
  require_once( 'includes/wpachievements_shortcodes.php');
  require_once( 'includes/wpachievements_widget.php' );

  // Include only on admin page
  if ( is_admin() ) {
    require_once( 'includes/wpachievements_admin.php' );
    require_once( 'includes/wpachievements_update.php' );
  }
}

/**
 * Register and include widgets
 *
 * @version 8.0.0
 * @since 8.0.0
 * @access public
 * @return void
 */
function wpachievements_register_widgets() {
  require_once( 'includes/wpachievements_widget.php' );
  register_widget('WP_Widget_WPAchievements_Widget');
  register_widget('WP_Widget_WPAchievements_Achievements_Widget');
  register_widget('WP_Widget_WPAchievements_Quests_Widget');
  register_widget('WP_Widget_WPAchievements_Ranks_Widget');

  do_action( 'WPAchievements_widgets_registered' );
} 
add_action( 'widgets_init', 'wpachievements_register_widgets' );

/**
 * Actions and Filters
 *
 * @version 8.0.0
 * @since   8.0.0
 * @access  public
 * @return  void
 */
function wpachievements_hooks() {
  global $cp_module;

  if ($cp_module ) {
    if ( in_array_r('BuddyPress Integration', $cp_module) ){
      add_action( 'wp_before_admin_bar_render', 'wpachievements_remove_dup' );
    }
  }
}

/**
 * Register Post Types
 *
 * @version 8.0.0
 * @since   8.0.0
 * @access  public
 * @return  void
 */
function wpachievements_post_types() {

  // Achievements
  $labels = array(
    'name' => __( 'Achievements', 'wpachievements' ),
    'singular_name' => __( 'Achievement', 'wpachievements' ),
    'add_new' => __( 'Add New Achievement' , 'wpachievements' ),
    'add_new_item' => __( 'Add New Achievement' , 'wpachievements' ),
    'edit_item' =>  __( 'Edit Achievement' , 'wpachievements' ),
    'new_item' => __( 'New Achievement' , 'wpachievements' ),
    'view_item' => __('View Achievement', 'wpachievements'),
    'search_items' => __('Search Achievements', 'wpachievements'),
    'not_found' =>  __('No Achievements Found', 'wpachievements'),
    'not_found_in_trash' => __('No Achievements Found in Trash', 'wpachievements'),
  );

  register_post_type('wpachievements', array(
    'labels' => $labels,
    'public' => false,
    'show_ui' => true,
    'hierarchical' => true,
    'rewrite' => false,
    'query_var' => "wpachievements",
    'supports' => array(
      'title'
    ),
    'show_in_menu'  => false,
  ));

  // Quests
  $labels = array(
    'name' => __( 'Quests', 'wpachievements' ),
    'singular_name' => __( 'Quest', 'wpachievements' ),
    'add_new' => __( 'Add New Quest' , 'wpachievements' ),
    'add_new_item' => __( 'Add New Quest' , 'wpachievements' ),
    'edit_item' =>  __( 'Edit Quest' , 'wpachievements' ),
    'new_item' => __( 'New Quest' , 'wpachievements' ),
    'view_item' => __('View Quest', 'wpachievements'),
    'search_items' => __('Search Quests', 'wpachievements'),
    'not_found' =>  __('No Quests Found', 'wpachievements'),
    'not_found_in_trash' => __('No Quests Found in Trash', 'wpachievements'),
  );

  register_post_type('wpquests', array(
    'labels' => $labels,
    'public' => false,
    'show_ui' => true,
    'hierarchical' => true,
    'rewrite' => false,
    'query_var' => "wpquests",
    'supports' => array(
      'title'
    ),
    'show_in_menu'  => false,
  ));
}

/**
 * Remove CubePoints menu to avoid duplicate point menus
 *
 * @version 8.0.0
 * @since   8.0.0
 * @access  public
 * @return  void
 */
function wpachievements_remove_dup() {
  global $wp_admin_bar;

  $wp_admin_bar->remove_menu('my-points');
}


 //*************** Setup Install Data ***************\\
function wpachievements_data_install() {
  global $wpdb;

  if(function_exists('is_multisite') && is_multisite()){
    $table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
    add_blog_option(1,'wpachievements_ranks_data', array(0=>__('Newbie','wpachievements')));
  } else {
    $table = $wpdb->prefix.'achievements';
    add_option('wpachievements_ranks_data', array(0=>__('Newbie','wpachievements')));
  }

  if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
   $sql =
   "CREATE TABLE " . $table . " (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    uid bigint(20) NOT NULL,
    type VARCHAR(256) NOT NULL,
    rank TEXT NOT NULL,
    data TEXT NOT NULL,
    points bigint(20) NOT NULL,
    postid bigint(20) NOT NULL,
    UNIQUE KEY id (id)
    );";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
  }
  
  // Include settings so that we can run through defaults
  include_once( 'includes/admin/class-wpachievements-admin-settings.php' );

  $settings = WPAchievements_Admin_Settings::get_settings_pages();
 
  foreach ( $settings as $section ) {
    if ( ! method_exists( $section, 'get_settings' ) ) {
      continue;
    }
    $subsections = array_unique( array_merge( array( '' ), array_keys( $section->get_sections() ) ) );

    foreach ( $subsections as $subsection ) {
      foreach ( $section->get_settings( $subsection ) as $value ) {
        if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
          $autoload = isset( $value['autoload'] ) ? (bool) $value['autoload'] : true;
          add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
        }
      }
    }
  }

    // Add plugin installation date and variable for rating div
  add_option( 'wpachievements_install_date', date('Y-m-d h:i:s') );
  add_option( 'wpachievements_rating_div', 'no' );

  /*if(function_exists('is_multisite') && is_multisite()){
    update_blog_option(1, 'wpachievements_important_notice_status', '');
  } else{
    update_option('wpachievements_important_notice_status', '');
  }*/
}
register_activation_hook( __FILE__, 'wpachievements_data_install' );

/**
 * Uninstall routine
 *
 * @version 8.0.0
 * @since   8.0.0
 * @access  public
 * @return  void
 */
function wpachievements_data_uninstall() {
  global $wpdb;

  if ( function_exists('is_multisite') && is_multisite() ) {
    $table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
    delete_blog_option(1,'wpachievements_achievements_data');
    delete_blog_option(1,'wpachievements_ranks_data');
    delete_blog_option(1,'wpach_of_template');
    delete_blog_option(1,'wpach_of_shortname');
  }
  else{
    $table = $wpdb->prefix.'achievements';
    delete_option('wpachievements_achievements_data');
    delete_option('wpachievements_ranks_data');
    delete_option('wpach_of_template');
    delete_option('wpach_of_shortname');
  }

  $wpdb->query( "DROP TABLE $table" );
  $wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE `achievements_count`" );
  $wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE `achievements_gained`" );
}
register_uninstall_hook( __FILE__, 'wpachievements_data_uninstall' );

/**
 * Retrieve an option depending on WP site (multisite, single blog)
 *
 * @version 1.0.0
 * @since   1.0.0
 * @access  public
 * @param   string $option_name Option name
 * @return  mixed Option value
 */
function wpachievements_get_site_option( $option_name ) {

  if ( is_multisite() ) {
    $value = get_blog_option( 1, $option_name );
  }
  else {
    $value = get_option( $option_name );
  }

  return $value;
}

/*if( get_bloginfo('version') >= 3.6 ){
require 'update/update.php';
$WPAchievementsUpdates = PucFactory::buildUpdateChecker('http://api.wpachievements.net/update/?action=get_metadata&slug=wpachievements', __FILE__, 'wpachievements');
$WPAchievementsUpdates->addQueryArgFilter('wpachievements_license');
function wpachievements_license($queryArgs) {
 $settings = get_option('wpachievements_license_key');
 if ( !empty($settings) ) {
   $queryArgs['license_key'] = $settings;
 }
 $queryArgs['site_url'] = home_url();
 return $queryArgs;
}
}*/
?>