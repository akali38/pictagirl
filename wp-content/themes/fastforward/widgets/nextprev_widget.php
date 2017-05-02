<?php

class NextPrev_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'monstro_nextprev_widget',
            __('Next Prev Widget', 'monstrotheme'),
            array('description' => __('Next Prev page travelling', 'monstrotheme'),)
        );
    }

    function widget($args, $instance)
    {
        global $before_widget, $after_widget, $before_title, $after_title;
        echo $before_widget;

        $title = empty($instance['title']) ? '' : $instance['title'];
        $next = empty($instance['next']) ? '' : $instance['next'];
        $prev = empty($instance['prev']) ? '' : $instance['prev'];

        if (is_single()) {
            if (strlen($title) > 0) {
                // echo $before_title . $title . $after_title;
            }
            ?>

            <aside class="widget">

                <div class="widget_next_previous_posts">
                    <ul class="widget-list">
                        <?php
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();
                        ?>

                        <li class="prev">
                            <?php if (!empty($prev_post)): ?>
                                <a href="<?php echo get_permalink($prev_post->ID); ?>">&#8592; <?php _e($prev) ?></a>
                            <?php endif; ?>
                        </li>


                        <li class="next">
                            <?php if (!empty($next_post)): ?>
                                <a href="<?php echo get_permalink($next_post->ID); ?>"><?php _e($next) ?> &#8594;</a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </aside>
        <?php
        }
        echo $after_widget;
    }

    function update($new_instance, $old_instance)
    {

        /*save the widget*/
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['next'] = strip_tags($new_instance['next']);
        $instance['prev'] = strip_tags($new_instance['prev']);

        return $instance;
    }

    function form($instance)
    {
        /*widgetform in backend*/

        $instance = wp_parse_args((array)$instance, array('title' => '', 'prev' => '', 'next' => ''));
        $title = strip_tags($instance['title']);
        $next = strip_tags($instance['next']);
        $prev = strip_tags($instance['prev']);

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'monstrotheme') ?>:
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                       name="<?php echo $this->get_field_name('title'); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>"/>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('next'); ?>"><?php _e('Next string', 'monstrotheme') ?>:
                <input class="widefat" id="<?php echo $this->get_field_id('next'); ?>"
                       name="<?php echo $this->get_field_name('next'); ?>" type="text"
                       value="<?php echo esc_attr($next); ?>"/>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('prev'); ?>"><?php _e('Prev string', 'monstrotheme') ?>:
                <input class="widefat" id="<?php echo $this->get_field_id('next'); ?>"
                       name="<?php echo $this->get_field_name('prev'); ?>" type="text"
                       value="<?php echo esc_attr($prev); ?>"/>
            </label>
        </p>

        <?php

        $title = strip_tags($instance['title']);
        $next = strip_tags($instance['next']);
        $prev = strip_tags($instance['prev']);

    }
}

register_widget('NextPrev_Widget');
?>