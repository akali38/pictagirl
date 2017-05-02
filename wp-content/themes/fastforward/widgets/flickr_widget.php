<?php
class Flickr_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'monstro_flickr_widget',
            __('Flickr Photos', 'monstrotheme'),
            array( 'description' => __( 'Display Flickr Photos', 'monstrotheme' ), )
        );
    }

    function widget($args, $instance) {

        /* prints the widget */
        extract($args, EXTR_SKIP);

        $id = empty($instance['id']) ? '&nbsp;' : apply_filters('widget_id', $instance['id']);
        $title = empty($instance['title']) ? __('Photo Gallery','monstrotheme') : apply_filters('widget_title', $instance['title']);
        $number = empty($instance['number']) ? 9 : apply_filters('widget_number', $instance['number']);
        $showing = empty($instance['showing']) ? '&nbsp;' : apply_filters('widget_showing', $instance['showing']);

        echo $before_widget;
        if( strlen( $title ) > 0 ){
            echo $before_title . $title . $after_title;
        }
?>
        <div class="flickr clearfix" style="position:relative;">
            <?php
                for($counter=0; $counter<$number; $counter++){
                    ?>
                        <div class="flickr_badge_image" id="flickr_badge_image2">
                            <img src="<?php echo get_template_directory_uri();?>/images/empty.png"/>
                        </div>
                    <?php
                }
            ?>
            <iframe class="flickr" seamless="seamless" scrolling="no" style="border:none; position: absolute; width:100%; height:100%; top:0; left:0;" src="<?php echo get_template_directory_uri()?>/widgets/flickr_iframe.php?number=<?php echo $number;?>&amp;showing=<?php echo $showing;?>&amp;id=<?php echo $id;?>"></iframe>
            <div class="clear"></div>
        </div>
<?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {

        /* save the widget */
        $instance = $old_instance;
        $instance['id'] = strip_tags($new_instance['id']);
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = strip_tags($new_instance['number']);
        $instance['showing'] = strip_tags($new_instance['showing']);

        return $instance;
    }

    function form($instance) {

        /* widgetform in backend */
        $instance = wp_parse_args( (array) $instance, array('title' => '',  'id' => '', 'number' => '' , 'showing' => '') );
        $id = strip_tags($instance['id']);
        $title = strip_tags($instance['title']);
        $number = strip_tags($instance['number']);
        $showing = strip_tags($instance['showing']);
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','monstrotheme') ?>:
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('id'); ?>">Flickr ID (<a target='_blank' href="http://www.idgettr.com">idGettr</a>):
                <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo esc_attr($id); ?>" />
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of photos','monstrotheme') ?>:
                <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" />
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('showing'); ?>"><?php _e('Showing Method','monstrotheme') ?>:
                <select size="1" name="<?php echo $this->get_field_name('showing'); ?>">
                    <option value="random"<?php if(esc_attr($showing) =='random'){echo 'selected';}?>><?php _e('Random Photo','monstrotheme')?></option>
                    <option value="latest"<?php if(esc_attr($showing) =='latest'){echo 'selected';}?>><?php _e('Latest Photo','monstrotheme') ?></option>
                </select>
            </label>
        </p>
<?php
    }
}
register_widget( 'Flickr_Widget' );
?>