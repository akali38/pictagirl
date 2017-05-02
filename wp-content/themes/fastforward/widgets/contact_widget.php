<?php

class Contact_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'monstro_contact_widget',
            __('Quick Contact Form', 'monstrotheme'),
            array('description' => __('Display Quick Contact Form', 'monstrotheme'),)
        );
    }

    function form($instance) {
        if (isset($instance['title'])) {
            $title = esc_attr($instance['title']);
        } else {
            $title = __('Contact us', 'monstrotheme');
        }

        if (isset($instance['email'])) {
            $email = esc_attr($instance['email']);
        } else {
            $email = get_the_author_meta('user_email', get_current_user_id());
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'monstrotheme'); ?>:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Your email', 'monstrotheme'); ?>:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('email'); ?>"
                   name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo $email; ?>"/>
        </p>
    <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['email'] = strip_tags($new_instance['email']);
        return $instance;
    }

    function widget($args, $instance) {
        global $before_widget, $after_widget, $before_title, $after_title;
        extract($args);
        if (!empty($instance['title'])) {
            $title = apply_filters('widget_title', $instance['title']);
        } else {
            $title = '';
        }

        if (isset($instance['email'])) {
            $email = $instance['email'];
        }


        echo $before_widget;

        if (strlen($title) > 0) {
            echo $before_title . $title . $after_title;
        }

        if (strlen($email)) {
            // TODO send form functionality
            ?>
            <form id="comment_form" class="form comments b_contact" method="post" action="<?php echo home_url() ?>/">
                <fieldset>
                    <p class="input">
                        <input type="text"
                               onfocus="if (this.value == '<?php _e('Your name', 'monstrotheme'); ?> *') {this.value = '';}"
                               onblur="if (this.value == '') {this.value = '<?php _e('Your name', 'monstrotheme'); ?> *';}"
                               value="<?php _e('Your name', 'monstrotheme'); ?> *" name="name" id="name"/>
                    </p>

                    <p class="input">
                        <input type="text"
                               onfocus="if (this.value == '<?php _e('Your email', 'monstrotheme'); ?> *') {this.value = '';}"
                               onblur="if (this.value == '') {this.value = '<?php _e('Your email', 'monstrotheme'); ?> *';}"
                               value="<?php _e('Your email', 'monstrotheme'); ?> *" name="email" id="email"/>
                    </p>

                    <p class="textarea">
                        <textarea
                            onfocus="if (this.value == '<?php _e('Message', 'monstrotheme'); ?> *') {this.value = '';}"
                            onblur="if (this.value == '') {this.value = '<?php _e('Message', 'monstrotheme'); ?> *';}"
                            tabindex="4" cols="50" rows="10" id="comment_widget"
                            name="message"><?php _e('Message', 'monstrotheme'); ?> *</textarea>
                    </p>

                    <p>
                        <input type="button" value="<?php _e('Submit form', 'monstrotheme'); ?>" name="btn_send"
                               onclick="javascript:act.send_mail( 'contact' , '#comment_form' , 'p#send_mail_result' );"
                               class="inp_button"/>
                    </p>

                    <div class="container_msg"></div>
                    <p id="send_mail_result">
                    </p>
                    <input type="hidden" value="<?php echo $email; ?>" name="contact_email"/>
                </fieldset>
            </form>
        <?php
        }

        echo $after_widget;
    }
}

register_widget('Contact_Widget');
?>
