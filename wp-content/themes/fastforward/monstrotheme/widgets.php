<?php

/**
 * User: Mircea Trofimciuc
 * Date: 28.03.2014
 * Time: 11:07
 */
class NextPrevWidget extends WP_Widget
{

    function NextPrevWidget()
    {
        parent::__construct('nextPrevWidget', 'Next/Prev Post');
    }

    function widget($args, $instance)
    {

        get_template_part('templates/widgets/next-prev-front-view');
        // $next = empty($instance['next']) ? __('Next Previous Post','cosmotheme') : apply_filters('widget_title', $instance['next']);
        // $prev = empty($instance['prev']) ? 3 : apply_filters('widget_number', $instance['next']);

        //echo 'wtf';
    }

    function update($new_instance, $old_instance)
    {
        // Save widget options
        $instance = $old_instance;
        $instance['next'] = strip_tags($new_instance['next']);
        $instance['prev'] = strip_tags($new_instance['prev']);

        return $instance;
    }

    function form($instance)
    {
        $instance = wp_parse_args((array)$instance, array('next' => '', 'prev' => ''));
        $next = strip_tags($instance['next']);
        $prev = strip_tags($instance['prev']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('next'); ?>"><?php _e('Next String', 'monstrotheme') ?>:
                <input class="widefat" id="<?php echo $this->get_field_id('next'); ?>"
                       name="<?php echo $this->get_field_name('next'); ?>" type="text"
                       value="<?php echo esc_attr($next); ?>"/>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('prev'); ?>"><?php _e('Previous String', 'monstrotheme') ?>:
                <input class="widefat" id="<?php echo $this->get_field_id('prev'); ?>"
                       name="<?php echo $this->get_field_name('prev'); ?>" type="text"
                       value="<?php echo esc_attr($prev); ?>"/>
            </label>
        </p>
        <?php

        $next = strip_tags($instance['next']);
        $prev = strip_tags($instance['prev']);

    }
}


function monstro_register_widgets()
{
    register_widget('NextPrevWidget');
}