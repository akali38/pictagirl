<?php
class MonstroThemeAdmin{
    private static $instance = false;
    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new MonstroThemeAdmin();
        }
        return self::$instance;
    }

    public function postBox($post){
        $meta = get_post_meta($post->ID, MONSTROTHEME_POST_META_NAME, true);
        if(!is_array($meta)){
            $meta = array('postSource' => '');
        }

        wp_nonce_field( 'MonstroMetaBox', 'monstroMetaBoxNonce');
        echo '<label>' . __('Post source:', 'monstrotheme') . '</label><br/>';
        echo '<textarea cols="80" rows="3" name="monstro[postSource]">' . $meta['postSource'] . '</textarea>';
    }

    public function videoBox($post){
        $meta = get_post_meta($post->ID, MONSTROTHEME_POST_META_NAME, true);
        if(!is_array($meta)){
            $meta = array(
                'postSource' => '',
                'videoUrl' => ''
            );
        }

        wp_nonce_field( 'MonstroMetaBox', 'monstroMetaBoxNonce');
        echo '<label>' . __('Post source:', 'monstrotheme') . '</label><br/>';
        echo '<textarea cols="80" rows="3" name="monstro[postSource]">' . $meta['postSource'] . '</textarea>';
        echo '<br/>';
        echo '<label>' . __('Video URL or embed code:', 'monstrotheme') . '</label><br/>';
        echo ' <textarea cols="80" rows="3" name="monstro[videoUrl]">' . $meta['videoUrl'] . '</textarea>';
    }

    public function addMetaBoxes(){
        add_meta_box('monstro-post-box', MONSTROTHEME_NAME, array($this, 'postBox'), 'post', 'normal');
        add_meta_box('monstro-post-box', MONSTROTHEME_NAME, array($this, 'videoBox'), 'video', 'normal');
    }

    public function adminNotices(){
        $monstrotheme = MonstroTheme::getInstance();
        if(!$monstrotheme->settings->dialogues->userLearnedHowToSaveSettings){
            ?>
            <div class="updated">
                <p>
                    <?php _e( 'Customizing the theme from backend is soooo 2013. Check out our', 'monstrotheme' ); ?>
                    <a href="<?php echo home_url();?>#customize"><?php _e('frontend customizer', 'monstrotheme');?></a>!
                </p>
            </div>
        <?php
        }
    }

    public function __construct(){
        add_action('add_meta_boxes', array($this, 'addMetaBoxes'));
        add_action('admin_notices', array($this, 'adminNotices'));
    }
}

MonstroThemeAdmin::getInstance();

