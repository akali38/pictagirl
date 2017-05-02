<?php

class Latest_Posts_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'monstro_latest_posts_widget',
            __('Latest Posts', 'monstrotheme'),
            array('description' => __('Display Latest Posts', 'monstrotheme'),)
        );
    }

    function widget($args, $instance)
    {
        global $before_widget, $after_widget, $before_title, $after_title;
        require_once( get_template_directory() . '/vendors/aq_resizer.php');
        $monstrotheme = MonstroTheme::getInstance();
        extract($args, EXTR_SKIP);

        echo $before_widget;

        $title = empty($instance['title']) ? __('Latest Posts', 'monstrotheme') : apply_filters('widget_title', $instance['title']);
        $number = empty($instance['number']) ? 3 : apply_filters('widget_number', $instance['number']);
        $post_type_name = empty($instance['post_type_name']) ? 3 : apply_filters('widget_number', $instance['post_type_name']);
        ?>

        <?php
        $args = array(
            'orderby' => 'date',
            'posts_per_page' => $number,
            'ignore_sticky_posts' => true,
            'post_type' => $post_type_name
        );
        global $wp_query;
        $wp_query = new WP_Query($args);
        get_template_part('monstrotheme/monstro-likes-and-views');
        $likesAndViews = new MonstroLikesAndViews();
        if (have_posts()) {
            ?>

            <aside id="widget_monstro_latestposts-2" class="widget">

                <div class="widget_latest_posts ">
                    <p class="widget-delimiter">&nbsp;</p>

                    <h5 class="widget-title"><?php echo $title ?></h5>

                        <ul class="widget-list">
                        <?php
                        while(have_posts()){
                            the_post();
                            $headerClasses = array();
                            if(!has_post_thumbnail()){
                                $headerClasses[] = 'no-feat-img';
                            }
                            ?>
                            <li>
                                <article <?php post_class('row');?>>
                                    <div class="large-12 small-12 columns <?php echo implode(' ', $headerClasses);?>">
                                        <div class="featimg" data-hover-effeckt-type="{{settings.contentLayouts.frontPage.hoverAnimation}}">
                                            <a class="entry-img" href="<?php echo get_permalink() ?>">
                                               <?php if($post_type_name == "post"):?>
                                                <span class="post-category">
                                                    <?php
                                                    $category = get_the_category();
                                                    $term_meta = get_option( "taxonomy_".$category[0]->term_id );
                                                    $color = $term_meta['tax_image'] ? $term_meta['tax_image'] : '';
                                                    ?>
                                                    <span  style="background:<?php echo $color ?>">
                                                        <?php echo $category[0]->name;?>
                                                    </span>
                                                </span>
                                                <?php
                                                endif;
                                                if(has_post_thumbnail()){
                                                    require_once get_template_directory() . '/vendors/aq_resizer.php';
                                                    $monstrotheme = MonstroTheme::getInstance();
                                                    $imageSize = $monstrotheme->settings->imageSizes->widgets;
                                                    $thumbID = get_post_thumbnail_id($post->ID);
                                                    $src = wp_get_attachment_url($thumbID);
                                                    ?>
                                                    <img src="<?php echo aq_resize( $src, $imageSize->width, $imageSize->crop ? $imageSize->height : null, true, true, true);?>"/>
                                                <?php } ?>
                                            </a>
                                            <div class="hover-toggle" ng-if="1 == settings.votes.enable">
                                                <div monstro-votes icon="settings.votes.icon" votes="<?php echo $likesAndViews->getVotes();?>"
                                                     vote="<?php echo $likesAndViews->getVote();?>" post-id="<?php the_ID();?>"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="large-12 small-12 columns">
                                        <h6>
                                            <a href="<?php echo get_permalink() ?>"><?php the_title();?></a>
                                        </h6>
                                    </div>
                                </article>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </aside>
            <?php
            echo $after_widget;
        }
        wp_reset_query();
    }


    function update($new_instance, $old_instance)
    {

        /*save the widget*/
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = strip_tags($new_instance['number']);
        $instance['post_type_name']     = strip_tags( $new_instance['post_type_name']);

        return $instance;
    }

    function form($instance)
    {
        /*widgetform in backend*/

        $instance = wp_parse_args((array)$instance, array('title' => '', 'number' => '', 'post_type_name' => ''));
        $title              = strip_tags($instance['title']);
        $number             = strip_tags($instance['number']);
        $post_type_name     = strip_tags( $instance['post_type_name']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'monstrotheme') ?>:
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                       name="<?php echo $this->get_field_name('title'); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>"/>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of Posts' , 'monstrotheme' ); ?>:</label>
            <input id="<?php echo $this->get_field_id( 'number' ); ?>"  size="3" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('post_type_name'); ?>"><?php  _e( 'Select Post Type' , 'monstrotheme' ); ?>:
                <select class="widefat" id="<?php echo $this->get_field_id('post_type_name'); ?>" name="<?php echo $this->get_field_name('post_type_name'); ?>" <?php checked( $post_type_name ); ?>>
                    <option value="post" <?php echo ($post_type_name=='post')?'selected':'';  ?>><?php _e( 'Posts' , 'monstrotheme' ); ?></option>
                    <option value="video" <?php echo ($post_type_name=='video')?'selected':'';  ?>><?php _e( 'Video' , 'monstrotheme' ); ?></option>
                    <option value="gallery" <?php echo ($post_type_name=='gallery')?'selected':'';  ?>><?php _e( 'Gallery' , 'monstrotheme' ); ?></option>
                </select>
            </label>
        </p>
        <?php

        $title = strip_tags($instance['title']);
        $number = strip_tags($instance['number']);
    }
}

    register_widget('Latest_Posts_Widget');

?>