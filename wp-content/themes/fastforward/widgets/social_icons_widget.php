<?php

class Social_Icons_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'monstro_social_icons_widget',
            __('Social Icons', 'monstrotheme'),
            array('description' => __('Display Social Icons', 'monstrotheme'),)
        );
    }

    function widget($args, $instance)
    {
        global $before_widget, $after_widget, $before_title, $after_title;

        /* prints the widget*/
        extract($args, EXTR_SKIP);

        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = '';
        }

        echo $before_widget;
        ?>

        <aside id="widget_monstro_socialicons" class="widget">

            <div class="widget_socialicons">
                <p class="widget-delimiter">&nbsp;</p>
                <?php if (!empty($title)) { ?>
                    <h5 class="widget-title"><?php echo $before_title . $title . $after_title; ?></h5>
                <?php } ?>
                <div class="socialicons" ng-include="tdu + '/templates/header/social-icons.html'"></div>
            </div>
        </aside>
        <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance)
    {

        /*save the widget*/
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }

    function form($instance)
    {

        /* widget form in backend */
        $instance = wp_parse_args((array)$instance, array('title' => ''));
        $title = strip_tags($instance['title']);

        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'monstrotheme') ?>:
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                       name="<?php echo $this->get_field_name('title'); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>"/>
            </label>
        </p>
    <?php
    }
}
    register_widget('Social_Icons_Widget');
?>