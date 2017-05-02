<?php
    class Comments_Widget extends WP_Widget {


        public function __construct() {
            parent::__construct(
                'monstro_comments_widget',
                __('Comments Widget', 'monstrotheme'),
                array( 'description' => __( 'Commenting widget', 'monstrotheme' ), )
            );
        }

        function widget( $args , $instance ) {

            /* prints the widget*/
            extract($args, EXTR_SKIP);

            if( isset( $instance['title'] ) ){
                $title = $instance['title'];
            }else{
                $title = '';
            }

            if( isset( $instance['nr_comments'] ) ){
                $nr_comments = $instance['nr_comments'];
            }else{
                $nr_comments = 0;
            }

            echo $before_widget;

            if( !empty( $title ) ){
                echo $before_title . $title . $after_title;
            }

            if( options::get_value( 'blog_post' , 'comments_type' ) != 'fb_comments' ) {
        ?>
            
            <div class="tab_menu_content tabs-container">
                <?php
                    $args = array(
                        'number' => $nr_comments,
                        'status' => 'approve'
                    );

                    $comments = get_comments( $args );

                    if( !empty( $comments ) && is_array( $comments ) ){
                        echo '<ul class="widget-list">';
                        /* list comments */
                        foreach($comments as $comment) {

                            /* get post info */
                            $post = get_post( $comment -> comment_post_ID );

                            /* get user info */
                            $user = get_users( array( 'include' => $comment -> user_id ) );
                            $user_url = '';

                            /* get user ulr */
                            if( !empty( $user ) ){
                                $user_url = $user[0] -> user_url;
                            }

                            /* author comment */
                            if( $comment -> comment_author_url != ''){
                                /* get author url */
                                $author_url = '<a href="' . $comment -> comment_author_url . '">' . mb_substr( $comment -> comment_author , 0 , 7 );
                                if( strlen( $comment -> comment_author ) > 7 ){
                                    $author_url .=  '...</a>';
                                }else{
                                    $author_url .= '</a>';
                                }
                            }else{
                                /* create user url */
                                if( $user_url != '' ){
                                    $author_url = '<a href="' . $user_url . '">' . mb_substr( $comment -> comment_author , 0 , 7 );
                                    if( strlen( $comment -> comment_author ) > 7 ){
                                        $author_url .=  '...</a>';
                                    }else{
                                        $author_url .= '</a>';
                                    }
                                }else{
                                    $author_url = mb_substr( $comment -> comment_author , 0 , 7 );
                                    if( strlen( $comment -> comment_author ) > 7 ){
                                        $author_url .=  '...';
                                    }
                                }
                            }
                ?>
                            <li>
                                <article class="row">
                                    <div class="large-4 small-1 columns">
                                        <a class="entry-img" href="<?php echo get_permalink( $comment -> comment_post_ID ) . '#comment-' . $comment -> comment_ID; ?>">
                                            <?php $size = image::asize( 'thumbnail' ); echo get_avatar( $comment, $size[0] , DEFAULT_AVATAR );  ?>
                                        </a>
                                    </div>
                                    <div class="large-8 small-3 columns">
                                        <h6>
                                            <a href="<?php echo get_permalink( $comment -> comment_post_ID ) . '#comment-' . $comment -> comment_ID; ?>">
                                                <?php
                                                    echo strip_tags( mb_substr( $comment -> comment_content , 0 , BLOCK_TITLE_LEN-5 ) );
                                                    if( strlen ( strip_tags ( $comment -> comment_content ) ) > BLOCK_TITLE_LEN-5 ){
                                                        echo ' ...';
                                                    }
                                                ?>
                                            </a>
                                        </h6>
                                        <div class="widget-meta st">
                                            <ul>
                                                <li class="author"><?php echo $author_url; ?></li>
                                                    <?php

                                                        if( options::logic( 'blog_post' , 'time' ) ){

                                                            $comment_time = human_time_diff( strtotime($comment -> comment_date) , current_time('timestamp') ) . ' ' . __( 'ago' , 'monstrotheme' );
                                                        }else{
                                                            $comment_time = date_i18n( get_option( 'date_format' ) , strtotime($comment -> comment_date) ); /*echo ' '.__('at','monstrotheme') . ' '. get_the_time( get_option( 'time_format' ) , $post -> ID  );*/

                                                        }
                                                    ?>
                                                <li class="time">
                                                    <time datetime="<?php echo date('Y-m-d',strtotime($comment -> comment_date)); ?>"><?php echo $comment_time; ?></time>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </article>
                            </li>
                <?php
                        }
                        echo '</ul>';
                    }else{
                        echo '<p>' . __( 'There are no comments' , 'monstrotheme' ) . '</p>';
                    }
                ?>
                </div>
            <?php
                }

            echo $after_widget;
        }

        function update( $new_instance, $old_instance) {

            /*save the widget*/
            $instance = $old_instance;
            $instance['title']              = strip_tags( $new_instance['title'] );
            $instance['nr_comments']        = strip_tags( $new_instance['nr_comments'] );

            return $instance;
        }

        function form($instance) {

            /* widget form in backend */
            $instance       = wp_parse_args( (array) $instance, array( 'title' => '' ,  'nr_comments' => 10 ) );
            $title          = strip_tags( $instance['title'] );
            $nr_comments    = strip_tags( $instance['nr_comments'] );
    ?>

            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','monstrotheme') ?>:
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('nr_comments'); ?>"><?php _e( 'Number of comments' , 'monstrotheme' ) ?>:
                    <input class="widefat digit" id="<?php echo $this->get_field_id('nr_comments'); ?>" name="<?php echo $this->get_field_name('nr_comments'); ?>" type="text" value="<?php echo esc_attr( $nr_comments ); ?>" />
                </label>
            </p>
    <?php
        }
    }

register_widget( 'Comments_Widget' );
?>