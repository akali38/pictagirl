<?php
/**
 * Automatic Updates
 */
class WPAchievements_Update {

  private static $api = 'http://api.wpachievements.net/'; //http://localhost/WPAchievements_API/';

  /**
   * Init required hooks
   *
   * @version 1.0.0
   * @since   1.0.0
   * @static
   * @access  public
   * @return  void
   */
  public static function init() {
    //set_site_transient('update_plugins', null);
    add_action( 'admin_notices', array( __CLASS__, 'activation_notice' ) );
    add_action( 'wpachievements_settings_saved', array( __CLASS__, 'verify' ) );
    add_filter( 'pre_set_site_transient_update_plugins', array( __CLASS__, 'check' ) );
    add_filter( 'plugins_api', array( __CLASS__, 'plugins_api' ), 10, 3);
  }

  /**
   * Check if the plugin needs to be verified
   *
   * @version 8.0.0
   * @since   8.0.0
   * @access  public
   * @return  boolean TRUE if the plugin has already been verified
   */
  public static function is_verified() {
    $purchase_code = get_option( 'wpachievements_license_key' );
    $verified = get_option( 'wpachievements_verified' );

    if ( empty( $purchase_code ) ) {
      delete_option( 'wpachievements_verified' );
    }
    elseif ( $purchase_code == $verified && ! empty( $verified ) ) {
      return true;
    }
    else {
      delete_option( 'wpachievements_verified' );
    }

    return false;
  }

  /**
   * Show a notice if the plugin hasn't been activated, yet
   *
   * @version 8.0.0
   * @since   8.0.0
   * @static
   * @access  public
   * @return  void
   */
  public static function activation_notice() {

    $screen = get_current_screen();

    if ( ! self::is_verified() && 'wpachievements_page_wpachievements_settings' != $screen->id ) {
      self::message( __("Thank you for purchasing WPAchievements! The plugin needs to be activated to offer full fuctionality and to enable automatic updates. Navigate to WPAchievements <a href='edit.php?post_type=wpachievements&page=wpachievements_settings' >settings page</a> and enter your Codecanyon purchase code.", 'wpachievements' ) );
    }
  }

  /**
   * Make a request to the API for license verification
   *
   * @version 8.0.0
   * @since   8.0.0
   * @static
   * @access  public
   * @param   bolean $force
   * @return  void
   */
  public static function verify( $force = false ) {

    if ( ! $force && self::is_verified() ) {
      return;
    }

    $purchase_code = get_option( 'wpachievements_license_key' );

    if ( ! $purchase_code ) {
      self::message( sprintf( __("Missing Codecanyon purchase code. Navigate to %sPlugin Options Page%s and enter your purchase code.", 'wpachievements'), '<a href="edit.php?post_type=wpachievements&page=wpachievements_settings">', '</a>') );

      return;
    }

    $request_url = add_query_arg( array(
      "code" => $purchase_code,
      "site" => home_url()
    ), self::$api . 'verify.php' );

    $args = array(
      'timeput' => 30,
    );

    $response = wp_remote_get( $request_url, $args );

    if ( is_wp_error( $response ) ) {
      self::message( $response->get_error_message() );
    }
    elseif ( ! empty( $response['response']['code'] ) ) {
      if ( 200 == $response['response']['code'] ) {
        $data = json_decode( $response['body'] );

        if ( $data ) {
          if ( isset( $data->msg ) ) {
            if ( isset( $data->code ) && 200 == $data->code ) {
              update_option( 'wpachievements_verified', $purchase_code );
              $class = 'updated';
            }
            else {
              $class = 'error';
              delete_option( 'wpachievements_verified' );
            }

            self::message( $data->msg, $class );
          }
          else {
            self::message(__( "Your purchase can't be verified due to an unexpeted error!", 'wpachievements').' '.sprintf( __("Error: %s. Please contact WPAchievements Support!", 'wpachievements' ), 'SERVER MESSAGE EMPTY' ), 'error' );
          }
        }
        else {
          self::message( __( "Your purchase can't be verified due to an unexpeted error!", 'wpachievements').' '.sprintf( __("Error: %s. Please contact WPAchievements Support!", 'wpachievements' ), 'JSON DECODE' ) );
        }
      }
      else {
        self::message( __( "Your purchase can't be verified due to a server error!", 'wpachievements').' '.sprintf( __("Error: %s. Please contact WPAchievements Support!", 'wpachievements' ), $response['response']['code'] ) );
      }
    }
    else {
      self::message( __( "Your purchase can't be verified due to an unexpeted error!", 'wpachievements').' '.sprintf( __("Error: %s. Please contact WPAchievements Support!", 'wpachievements' ), 'EMPTY RESPONSE' ) );
    }
  }

  /**
   * Take over the update check
   *
   * @version 8.0.0
   * @since   8.0.0
   * @static
   * @access  public
   * @param   object $checked_data
   * @return  object
   */
  public static function check( $checked_data ) {

    if ( empty($checked_data->checked) ) {
      return $checked_data;
    }

    $request_args = array(
      'slug' => 'wpachievements',
      'version' => $checked_data->checked['wpachievements/wpachievements.php'],
    );

    $request_string = self::prepare_request( 'update_check', $request_args );

    // Start checking for an update
    $raw_response = wp_remote_post( self::$api . 'check.php' , $request_string );

    $response = null;

    if ( ! is_wp_error( $raw_response ) && isset( $raw_response['response']['code'] ) && ( $raw_response['response']['code'] == 200 ) ) {
      $response = unserialize( $raw_response['body'] );
    }

    if ( ! empty( $response ) && is_object( $response ) ) {
      // Feed the update data into WP updater
      $checked_data->response['wpachievements/wpachievements.php'] = $response;
    }

    return $checked_data;
  }

  /**
   * Take over the plugin info screen
   *
   * @version 8.0.0
   * @since   8.0.0
   * @access  public
   * @return  [type] [description]
   */
  public static function plugins_api( $def, $action, $args ) {

    if ( ! isset( $args->slug ) || $args->slug != 'wpachievements' ) {
      return false;
    }

    // Get the current version
    $plugin_info = get_site_transient('update_plugins');
    $current_version = $plugin_info->checked['wpachievements/wpachievements.php'];
    $args->version = $current_version;

    $request_string = self::prepare_request( $action, $args );

    $request = wp_remote_post( self::$api . 'check.php' , $request_string );

    if ( is_wp_error($request) ) {
      $res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
    }
    else {
      $res = unserialize($request['body']);

      if ( $res === false ) {
        $res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body'] );
      }
    }

    return $res;
  }

  /**
   * Generate the request query for the update check
   *
   * @version 8.0.0
   * @since   8.0.0
   * @static
   * @access  private
   * @param   string $action
   * @param   array $args
   * @return  array
   */
  private static function prepare_request( $action, $args ) {
    global $wp_version;

    return array (
      'body' => array (
        'action'  => $action,
        'request' => serialize( $args ),
        'site'    => home_url(),
        'code'    => get_option( 'wpachievements_license_key' ),
        'item_id' => '4265703',
      ),
      'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url()
    );
  }

  /**
   * Display a message
   *
   * @version 8.0.0
   * @since   8.0.0
   * @static
   * @access  public
   * @param   string $message Message test
   * @param   string $class   updated|error
   * @return  void
   */
  public static function message( $message, $class = 'error' ) {
    ?>
    <div class="<?php echo $class; ?>">
      <p><?php echo $message; ?></p>
    </div>
    <?php
  }
}

return WPAchievements_Update::init();