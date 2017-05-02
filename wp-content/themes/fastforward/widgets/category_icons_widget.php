<?php
class Category_Icons_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'monstro_category_icons_widget',
            __('Category icons', 'monstrotheme'),
            array( 'description' => __( 'Category with representative icons', 'monstrotheme' ), )
        );
    }

    function widget( $args , $instance ) {
        /* prints the widget*/

        if( isset( $instance[ 'title' ] ) ){
            $title = $instance[ 'title' ];
        }else{
            $title = '';
        }
        
        extract( $args , EXTR_SKIP );

        echo $before_widget;

        if( !empty( $title ) ){
            echo $before_title . $title . $after_title;
        }

        $categories = get_terms( 'category' );

        ?><ul class="widget-list"><?php
        foreach( $categories as $category ){
            if( isset( $instance[ 'icon_' . $category -> term_id ] ) ){
                $icon[ 'icon_' .  $category -> term_id ] = $instance[ 'icon_' .  $category -> term_id ];
            }else{
                $icon[ 'icon_' .  $category -> term_id ] = 'default.png';
            }
            ?>
                <li class="cat-item cat-item-3 cat-item-icon">
                    <a href="<?php echo get_category_link( $category ); ?>" title="<?php printf(__('View all posts filed under %s','monstrotheme'),$category -> name); ?>">
                        <?php
                            if( !empty( $icon[ 'icon_' .  $category -> term_id ]  ) ){
                        ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/category-icons/<?php echo $icon[ 'icon_' .  $category -> term_id ]; ?>" alt="" />
                        <?php
                            }
                        ?>
                        <span><?php echo $category -> name ?></span>
                        <span class="count right"><?php echo $category -> count ?></span>
                    </a>
                </li>
            <?php
        }
        ?></ul><?php

        echo $after_widget;
    }

    function update($new_instance, $old_instance) {

    /*save the widget*/
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );

        $categories = get_terms( 'category' );

        foreach( $categories as $category ){
            $instance[ 'icon_' . $category -> term_id ] = strip_tags( $new_instance[ 'icon_' . $category -> term_id ] );
        }

        return $instance;
    }

    function form($instance) {
    /*widgetform in backend*/

        if( isset( $instance[ 'title' ] ) ){
            $title = $instance[ 'title' ];
        }else{
            $title = '';
        }
        
        ?>
            <p>
                <label for="<?php echo $this -> get_field_id( 'title' ); ?>"><?php _e( 'Title' , 'monstrotheme' ) ?>:
                    <input class="widefat" type="text" id="<?php echo $this -> get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>">
                </label>
            </p>
        <?php

        $categories = get_terms( 'category' );

        $icon = array();

        foreach( $categories as $category ){
            if( isset( $instance[ 'icon_' . $category -> term_id ] ) ){
                $icon[ 'icon_' .  $category -> term_id ] = $instance[ 'icon_' .  $category -> term_id ];
            }else{
                $icon[ 'icon_' .  $category -> term_id ] = 'default.png';
            }

            ?>

            <p>
                <label for="<?php echo $this->get_field_id( 'icon_' .  $category -> term_id ); ?>"><?php echo $category -> name; ?>:
                    <select class="widefat" id="<?php echo $this->get_field_id( 'icon_' .  $category -> term_id ); ?>" name="<?php echo $this->get_field_name( 'icon_' .  $category -> term_id ); ?>" >
                        <?php
                            $icns = self::get_icons();
                            if( !empty( $icns ) ){
                                foreach( $icns  as $icn ){
                                    $file = pathinfo( $icn );
                                    if( $icon[ 'icon_' .  $category -> term_id ] == $icn ){
                                        ?><option value="<?php echo $icn; ?>" selected="selected"><?php echo $file['filename']; ?></option><?php
                                    }else{
                                        ?><option value="<?php echo $icn; ?>"><?php echo $file['filename']; ?></option><?php
                                    }
                                }
                            }
                        ?>
                    </select>
                </label>
            </p>
            <?php
        }
    }

    function get_icons(){
        $result = array();
        $icons = scandir(get_template_directory() . '/images/category-icons/' );
        foreach( $icons as $icon ){
            if( $icon != '..' && $icon != '.' ){
                $result[]  = $icon;
            }
        }

        return $result;
    }
}

register_widget( 'Category_Icons_Widget' );

?>