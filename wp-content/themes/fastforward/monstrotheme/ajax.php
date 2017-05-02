<?php

class MonstroAjax
{
    private static $instance = false;

    public static function getInstance()
    {
        if (false === self::$instance) {
            self::$instance = new MonstroAjax();
        }
        return self::$instance;
    }

    public function save_monstro_settings_in_user_meta()
    {
        if (isset($_POST['settings']) && is_array($settings = $_POST['settings'])) {
            update_user_meta(get_current_user_id(), MONSTROTHEME_SETTINGS, json_decode(json_encode($settings)));
        }
        exit;
    }

    public function save_monstrotheme_settings()
    {
        if(!current_user_can('administrator')) return;
        if (isset($_POST['settings']) && is_array($settings = $_POST['settings'])) {
            $settings['dialogues']['userLearnedHowToSaveSettings'] = 1;

            $google_analytics_temp = stripslashes($settings['general']['googleAnalytics']);
            unset($settings['general']['googleAnalytics']);

            $settings = json_decode(stripslashes(json_encode($settings)));
            $settings->general->googleAnalytics = $google_analytics_temp;

            update_option(MONSTROTHEME_SETTINGS, $settings);
        }
        exit;
    }

    private function returnVotes($postID)
    {
        global $wpdb;
        $monstro_votes_table = $wpdb->prefix . 'monstro_votes';
        $votes = $wpdb->get_var(
            $wpdb->prepare("
                SELECT SUM(vote)
                FROM $monstro_votes_table
                WHERE post_id = %d
                GROUP BY post_id
              ;", $postID)
        );
        $vote = $wpdb->get_var(
            $wpdb->prepare("
                    SELECT vote
                    FROM $monstro_votes_table
                    WHERE post_id = %d
                    AND user_ip LIKE %s
                ;", $postID, is_user_logged_in() ? get_current_user_id() : $_SERVER['REMOTE_ADDR'])
        );
        echo json_encode(array(
            'votes' => $votes ? $votes : 0,
            'vote' => $vote ? $vote : 0
        ));
    }

    private function vote($vote)
    {
        if (isset($_POST['postID']) && is_numeric($postID = $_POST['postID'])) {
            global $wpdb;
            $monstro_views_table = $wpdb->prefix . 'monstro_votes';
            $wpdb->replace(
                $monstro_views_table,
                array(
                    'post_id' => $postID,
                    'user_ip' => is_user_logged_in() ? get_current_user_id() : $_SERVER['REMOTE_ADDR'],
                    'user_id' => get_current_user_id(),
                    'vote' => $vote
                ),
                array(
                    '%d',
                    '%s',
                    '%d',
                    '%d'
                )
            );
            $this->returnVotes($postID);
        }
        exit;
    }

    public function unvote_post()
    {
        if (isset($_POST['postID']) && is_numeric($postID = $_POST['postID'])) {
            global $wpdb;
            $monstro_views_table = $wpdb->prefix . 'monstro_votes';
            $wpdb->delete($monstro_views_table, array('user_ip' => is_user_logged_in() ? get_current_user_id() : $_SERVER['REMOTE_ADDR']), array('%d'));
            $this->returnVotes($postID);
        }
        exit;
    }

    public function like_post()
    {
        $this->vote(1);
    }

    public function dislike_post()
    {
        $this->vote(-1);
    }

    public function save_sidebars()
    {
        if (isset($_POST['sidebars']) && is_array($sidebars = $_POST['sidebars'])) {
            update_option(SIDEBARS_OPTION_NAME, $sidebars);
        } else {
            update_option(SIDEBARS_OPTION_NAME, array());
        }
        exit;
    }

    public function login()
    {
        check_ajax_referer('ajax-login-nonce', 'security');
        $response = array();
        $user_signon = wp_signon();
        if (is_wp_error($user_signon)) {
            $response['success'] = true;
            $response['error'] = $user_signon->get_error_message();
        } else {
            $response['success'] = true;
            $monstrotheme = MonstroTheme::getInstance();
            $response['user'] = $monstrotheme->transformWPUser($user_signon);
        }
        echo json_encode($response);
        exit;
    }

    public function logout()
    {
        wp_logout();
        exit;
    }

    public function recover()
    {
        ob_start();
        require_once( ABSPATH . 'wp-login.php' );
        ob_get_clean();
        $response = array();
        $user_pass = retrieve_password();
        if (is_wp_error($user_pass)) {
            $response['success'] = false;
            $response['error'] = $user_pass->get_error_message();
        } else {
            $response['success'] = true;
        }
        echo json_encode($response);
        exit;
    }
    public function register()
    {
        $uid=$_POST['log'];
        $u_email = $_POST['user_email'];
        if (!is_null($uid) && !is_null($u_email))
        $response = array();
        $user_id = register_new_user( $uid, $u_email );
        if (is_wp_error($user_id)) {
            $response['success'] = false;
            $response['error'] = $user_id->get_error_message();
        } else {
            $response['success'] = true;
        }
        echo json_encode($response);
        exit;
    }

    public function updateMeta(){
        $postId = $_POST['postId'];
        $meta = $_POST['meta'];
        $post = get_post($postId);
        if(($post->post_author == get_current_user_id()) || current_user_can('administrator')){
            $monstroMeta = get_post_meta($postId, MONSTROTHEME_POST_META_NAME, true);
            if(!is_array($monstroMeta)){
                $monstroMeta = array();
            }
            $monstroMeta['meta'] = sanitize_meta($meta);
            update_post_meta($postId, MONSTROTHEME_POST_META_NAME, $monstroMeta);
        }
        exit;
    }

    public function resizeImages(){
        $response = array(
            'posts' => array(),
            'usedSettings' => $_POST['imageSizes']
        );
        $imageSizeSlug = 'post';
        $monstrotheme = MonstroTheme::getInstance();
        switch($_POST['contentLayout']){
            case 'post':
            case 'video':
                $imageSizeSlug = 'post';
                break;
            case 'page':
                $imageSizeSlug = 'page';
                break;
            default:
                if('thumb' == $monstrotheme->settings->contentLayouts->{$_POST['contentLayout']}->viewType){
                    $imageSizeSlug = 'grid';
                }else{
                    $imageSizeSlug = $monstrotheme->settings->contentLayouts->{$_POST['contentLayout']}->viewType;
                }
        }

        $imageSize = $_POST['imageSizes'][$imageSizeSlug];
        foreach($_POST['posts'] as $postId){
            if(has_post_thumbnail($postId)){
                require_once get_template_directory() . '/vendors/aq_resizer.php';
                $thumbID = get_post_thumbnail_id($postId);
                $src = wp_get_attachment_url($thumbID);
                $response['posts'][$postId] = aq_resize( $src, $imageSize['width'], $imageSize['crop'] ? $imageSize['height'] : null, true, true, true);
            }
        }
        echo json_encode($response);
        exit;
    }

    public function uploadFile(){
        $id = media_handle_upload('file', 0);
        $response = array();
        if(!is_wp_error($id)){
            $response['success'] = true;
            $response['id'] = $id;
            $response['src'] = wp_get_attachment_thumb_url($id);
            $response['full_src'] = wp_get_attachment_url($id);
        }
        echo json_encode($response);
        exit;
    }

    /**
     *
     */
    public function savePost(){
        $response = array();
        if(!isset($_POST['post'])) return;
        $post = $_POST['post'];
        if(!isset($post['title'])) return;
        $postArray = array(
            'post_title' => sanitize_text_field($post['title']),
            'post_content' => '',
            'post_status' => 'pending', //set to "publish" to be automatically published
            'post_type' => 'post',
            'post_author' => get_current_user_id(),
        );

        if(isset($post['content'])){
            $postArray['post_content'] =  sanitize_text_field($post['content']);
        }
        if(isset($post['category'])){
            $postArray['post_category'] = array($post['category']);
        }
        if(isset($post['tags'])){
       //     echo $post['tags'] . '|'. sanitize_text_field($post['tags']);
            $postArray['tags_input'] = sanitize_text_field($post['tags']);
        }

        $postId = wp_insert_post($postArray);
        set_post_format( $postId, 'image' ); //sets the given post to the 'gallery' format
        if ( isset($post['img_url_upload']) ) {
            $img_url =  $post['img_url_upload'];
            $upload =  media_sideload_image( $img_url , $postId);

        } else if(isset($post['featimg']) && isset($post['featimg']['id'])){
            set_post_thumbnail($postId, $post['featimg']['id']);
        }
        $meta = array(
            'postSource' => ''
        );
        if(isset($post['source'])){
            $meta['postSource'] = $post['source'];
        }
        update_post_meta($postId, MONSTROTHEME_POST_META_NAME, $meta);
        if(!is_wp_error($postId) && $postId){
            $response['postId'] = $postId;
            $response['link'] = get_permalink($postId);
        }
        echo json_encode($response);
        exit;
    }

    static function get_youtube_video_id( $url ){
        /**
         * @param  string $url #URL to be parsed, eg:
         * http://youtu.be/zc0s358b3Ys,
         * http://www.youtube.com/embed/zc0s358b3Ys
         * http://www.youtube.com/watch?v=zc0s358b3Ys
         * returns video id
         */
        $id=0;

        /*if there is a slash at the en we will remove it*/
        $url = rtrim($url, " /");
        if(strpos($url, 'youtu')){
            $urls = parse_url($url);

            /*expect url is http://youtu.be/abcd, where abcd is video iD*/
            if(isset($urls['host']) && $urls['host'] == 'youtu.be'){
                $id = ltrim($urls['path'],'/');
            }
            /*expect  url is http://www.youtube.com/embed/abcd*/
            else if(strpos($urls['path'],'embed') == 1){
                $id = end(explode('/',$urls['path']));
            }

            /*expect url is http://www.youtube.com/watch?v=abcd */
            else if( isset($urls['query']) ){
                parse_str($urls['query']);
                $id = $v;
            }else{
                $id=0;
            }
        }

        return $id;
    }

    static function  get_vimeo_video_id( $url ){
        /*if there is a slash at the en we will remove it*/
        $url = rtrim($url, " /");
        $id = 0;
        if(strpos($url, 'vimeo')){
            $urls = parse_url($url);
            if(isset($urls['host']) && $urls['host'] == 'vimeo.com'){
                $id = ltrim($urls['path'],'/');
                if(!is_numeric($id) || $id < 0){
                    $id = 0;
                }
            }else{
                $id = 0;
            }
        }
        return $id;
    }

    static function get_video_thumbnail( $url ){

        $vimeo_id = self::get_vimeo_video_id( $url );
        $youtube_id = self::get_youtube_video_id( $url );

        if( $vimeo_id != '0' ){
            $video_type = 'vimeo';
            $video_id = $vimeo_id;
        }

        if( $youtube_id != '0' ){
            $video_type = 'youtube';
            $video_id = $youtube_id;
        }

        $thumbnail_url = '';
        if($video_type == 'youtube'){
            $thumbnail_url = 'http://i1.ytimg.com/vi/'.$video_id.'/hqdefault.jpg';
        }elseif($video_type == 'vimeo'){

            $hash = wp_remote_get("http://vimeo.com/api/v2/video/$video_id.php");
            $hash = unserialize($hash['body']);

            $thumbnail_url = $hash[0]['thumbnail_large'];
        }

        return $thumbnail_url;
    }

    static function new_attachment($att_id){
        // the post this was sideloaded into is the attachments parent!
        $p = get_post($att_id);
        update_post_meta($p->post_parent,'_thumbnail_id',$att_id);
    }

    public function saveVideoPost(){
        $response = array();
        if(!isset($_POST['post'])) return;
        $post = $_POST['post'];
        if(!isset($post['title'])) return;
        if(!isset($post['video'])) return;
        $postArray = array(
            'post_title' => sanitize_text_field($post['title']),
            'post_content' => '',
            'post_status' => 'pending',
            'post_type' => 'video',
            'post_author' => get_current_user_id(),
            'tax_input' => array()
        );
        if(isset($post['content'])){
            $postArray['post_content'] = sanitize_text_field($post['content']);
        }
        if(isset($post['category'])){
            $postArray['post_category'] = $post['category'];
        }
        $postId = wp_insert_post($postArray);
        if(isset($post['videoTags'])){
            wp_set_post_terms($postId, $post['videoTags'], 'video-tag');
        }
        if(isset($post['videoCategory'])){
            wp_set_post_terms($postId, $post['videoCategory'], 'video-category');
        }
        if(isset($post['featimg']) && isset($post['featimg']['id'])){
            set_post_thumbnail($postId, $post['featimg']['id']);
        } else {
            $url = self::get_video_thumbnail( $post['video'] ) ;

            // add the function above to catch the attachments creation
            add_action('add_attachment', array( $this, 'new_attachment') );

            // load the attachment from the URL
            media_sideload_image( $url, $postId, $postArray['post_title'] . " | featured image");

            // we have the Image now, and the function above will have fired too setting the thumbnail ID in the process, so lets remove the hook so we don't cause any more trouble
            remove_action('add_attachment', array( $this, 'new_attachment') );
        }
        $meta = array(
            'postSource' => '',
            'videoUrl' => sanitize_text_field($post['video'])
        );
        if(isset($post['source'])){
            $meta['postSource'] = sanitize_text_field($post['source']);
        }
        update_post_meta($postId, MONSTROTHEME_POST_META_NAME, $meta);
        if(!is_wp_error($postId) && $postId){
            $response['postId'] = $postId;
            $response['link'] = get_permalink($postId);
        }
        echo json_encode($response);
        exit;
    }

    public function __construct()
    {
        add_action('wp_ajax_save_monstro_settings_in_user_meta', array($this, 'save_monstro_settings_in_user_meta'));
        add_action('wp_ajax_save_monstrotheme_settings', array($this, 'save_monstrotheme_settings'));
        add_action('wp_ajax_like_post', array($this, 'like_post'));
        add_action('wp_ajax_nopriv_like_post', array($this, 'like_post'));
        add_action('wp_ajax_dislike_post', array($this, 'dislike_post'));
        add_action('wp_ajax_nopriv_dislike_post', array($this, 'dislike_post'));
        add_action('wp_ajax_unvote_post', array($this, 'unvote_post'));
        add_action('wp_ajax_nopriv_unvote_post', array($this, 'unvote_post'));
        add_action('wp_ajax_monstrotheme_save_sidebars', array($this, 'save_sidebars'));
        add_action('wp_ajax_nopriv_login', array($this, 'login'));
        add_action('wp_ajax_nopriv_monstro_login', array($this, 'login'));
        add_action('wp_ajax_monstro_login', array($this, 'login'));
        add_action('wp_ajax_monstro_logout', array($this, 'logout'));
        add_action('wp_ajax_nopriv_monstro_register', array($this, 'register'));
        add_action('wp_ajax_nopriv_monstro_recover', array($this, 'recover'));
        add_action('wp_ajax_update_monstrometa', array($this, 'updateMeta'));
        add_action('wp_ajax_resize_images', array($this, 'resizeImages'));
        add_action('admin_post_monstro_upload_file', array($this, 'uploadFile'));
        add_action('wp_ajax_monstro_save_post', array($this, 'savePost'));
        add_action('wp_ajax_monstro_save_video_post', array($this, 'saveVideoPost'));
    }
}

MonstroAjax::getInstance();