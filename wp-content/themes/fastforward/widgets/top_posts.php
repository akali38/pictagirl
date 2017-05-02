<?php
class Top_posts extends WP_Widget {
        public function __construct() {
            parent::__construct(
                'monstro_top_widget',
                __('Top Posts Widget', 'monstrotheme'),
                array( 'description' => __( 'Display Posts Widget', 'monstrotheme' ), )
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

			if( isset( $instance['nr_posts'] ) ){
                $nr_posts = $instance['nr_posts'];
            }else{
                $nr_posts = 0;
            }
            if( isset( $instance['post_type_name'] ) ){
                $post_type_name = $instance['post_type_name'];
            }else{
                $post_type_name = 0;
            }
            if( isset( $instance['most_voted'] ) ){
                $most_voted = $instance['most_voted'];
            }else{
                $most_voted = null;
            }
            if( isset( $instance['most_viewed'] ) ){
                $most_viewed = $instance['most_viewed'];
            }else{
                $most_viewed = null;
            }
            echo $before_widget;
?>
                <aside id="widget_monstro_latestposts-2" class="widget">
                    <div class="widget_latest_posts ">
                        <p class="widget-delimiter">&nbsp;</p>
                        <h5 class="widget-title"><?php echo $title ?></h5>
                            <?php if (!empty($most_voted) && !empty($most_viewed)){ ?>
                                <div class="monstro-comments">
                                    <ul class="comments-tabs">
                                        <li id="li_tab1" class="active" onclick="tab('tab1')" ><a class="tab">Voted</a></li>
                                        <li id="li_tab2"   onclick="tab('tab2')"><a class="tab">Viewed</a></li>
                                    </ul>
                                </div>
                            <?php } ?>
                            <div id="widget_tabs">
                                <?php if(!empty($most_voted)): ?>
                                <div id="tab1">
                                    <ul class="widget-list" monstro-json="latestPosts" src="'latest-posts'| jsonEndpoint">
                                    <?php

                                    wp_reset_query();
                                    global $wpdb;

                                    get_template_part('monstrotheme/monstro-likes-and-views');

                                    wp_reset_query();
                                    global $wpdb;

                                    $philosopher_table = $wpdb->prefix . 'monstro_votes';

                                    $sql = 'SELECT * FROM ' . $philosopher_table . ' ORDER BY vote DESC';

                                    $randomFact = $wpdb -> get_results( $sql );

                                    $post_voted_id = null ;
                                    foreach ($randomFact as $value) {
                                        $post_voted_id[] = $value->post_id;
                                    }
                                     //  print '<pre>';
                                     // print_r($post_voted_id);
                                     //  print '</pre>';
                                    $args = array(
                                        'post_type'           => array($post_type_name),
                                        'post__in'            => $post_voted_id,
                                        'orderby'             => 'post__in',
                                        'showposts'           => $nr_posts,
                                        'ignore_sticky_posts' => true
                                    );
                                    global $wp_query;
                                     wp_reset_query();
                                    $wp_query = new WP_Query($args);

                                    $likesAndViews = new MonstroLikesAndViews();

                                    //  print '<pre>';
                                     // print_r($wp_query);
                                     // print '</pre>';

                                    if (is_array($wp_query -> posts) && !empty($wp_query -> posts) && !empty($post_voted_id)) {

                                        while(have_posts()){
                                            the_post();

                                            $postClasses = array();
                                            if(!has_post_thumbnail() ){
                                                $postClasses[] = 'no-feat-img';
                                            }
                                            $permalink = get_permalink( );
                                            ?>
                                            <li>
                                                <article <?php post_class('row', get_the_ID() );?>>
                                                    <div class="large-12 small-12 columns <?php echo implode(' ', $postClasses);?>">
                                                        <div class="featimg" data-hover-effeckt-type="{{settings.contentLayouts.frontPage.hoverAnimation}}">
                                                            <a class="entry-img" href="<?php echo $permalink ?>">
                                                                <?php if($post_type_name == "post"):?>
                                                                    <span class="post-category">
                                                                            <?php
                                                                            $category = get_the_category( );
                                                                            $term_meta = get_option( "taxonomy_".$category[0]->term_id );
                                                                            $color = $term_meta['tax_image'] ? $term_meta['tax_image'] : '';
                                                                            ?>
                                                                        <span  style="background:<?php echo $color ?>">
                                                                            <?php echo $category[0]->name;?>
                                                                            </span>
                                                                        </span>
                                                                <?php
                                                                endif;
                                                                if(has_post_thumbnail( )){
                                                                    require_once get_template_directory() . '/vendors/aq_resizer.php';
                                                                    $monstrotheme = MonstroTheme::getInstance();
                                                                    $imageSize = $monstrotheme->settings->imageSizes->widgets;
                                                                    $thumbID = get_post_thumbnail_id();
                                                                    $src = wp_get_attachment_url($thumbID);
                                                                    ?>
                                                                    <img src="<?php echo aq_resize( $src, $imageSize->width, $imageSize->crop ? $imageSize->height : null, true, true, true);?>"/>
                                                                <?php } ?>
                                                            </a>
                                                            <div class="hover-toggle" ng-if="1 == settings.votes.enable && latestPosts" ng-init="post = latestPosts.posts[<?php echo get_the_ID()  ?>]">
                                                                <div monstro-votes icon="settings.votes.icon" votes="<?php echo $likesAndViews->getVotes();?>"
                                                                     vote="<?php echo $likesAndViews->getVote();?>" post-id="<?php echo get_the_ID();?>"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="large-12 small-12 columns">
                                                        <h6>
                                                            <a href="<?php  echo $permalink?>"><?php echo the_title() ;?></a>
                                                        </h6>
                                                    </div>
                                                </article>
                                            </li>
                                        <?php

                                        } // end while have posts - most voted posts
                                        wp_reset_query();

                                    }else{
                                        _e("There are no voted posts yet.","monstrotheme");
                                    }

                                    ?>
                                    </ul>
                                </div>
                                <?php endif;?>
                                <?php if(!empty($most_viewed)): ?>
                                <div id="tab2" <?php if(!empty($most_voted)) echo 'style="display: none;"' ?> >
                                    <ul class="widget-list" monstro-json="latestPosts" src="'latest-posts'| jsonEndpoint">
                                        <?php
                                        wp_reset_query();
                                        global $wp_query;
                                       /*
                                        $postIds = array();
                                        foreach ($wp_query->posts as $postv) {
                                            $postIds[] = $postv->ID;
                                            $this->data[$postv->ID] = array(
                                                'views' => 0,
                                                'votes' => 0,
                                                'vote' => 0
                                            );
                                        }

                                        $postIds = implode(',', $postIds);
                                        */
                                        global $wpdb;
                                        $monstro_views = $wpdb->prefix . 'monstro_views';
                                        $views = $wpdb->get_results( "SELECT post_id, COUNT(user_ip) as views
                                                FROM $monstro_views
                                                GROUP BY post_id ");

                                        foreach ($views as $view) {
                                            $post_view_id[$view->post_id] = $view->views;
                                        }
                                        asort($post_view_id);
                                        $reversed = array_reverse($post_view_id, true);

                                        //print '<pre>';
                                        // print_r($wp_query);
                                        //print '</pre>';
                                        foreach($reversed as $postId => $voteNumber){
                                            $post_ID[] = $postId;
                                        }

                                        $args = array(
                                            'ignore_sticky_posts' => true,
                                            'post_type'           => array($post_type_name),
                                            'post__in'            => $post_ID,
                                            'orderby'             => 'post__in',
                                            'showposts'           => $nr_posts
                                        );

                                        $wp_query = new WP_Query($args);

                                        if(empty($most_voted)) {
                                            get_template_part('monstrotheme/monstro-likes-and-views');
                                            $likesAndViews = new MonstroLikesAndViews();
                                        }


                                   //     $recent = $wp_query; -> get_posts();

                                        if ( is_array($wp_query -> posts) && !empty($wp_query -> posts)  ) {
                                            while(have_posts()){
                                                the_post();

                                                $postClasses = array();
                                                if(!has_post_thumbnail()){
                                                    $postClasses[] = 'no-feat-img';
                                                }
                                                $permalink = get_permalink();
                                                ?>
                                                <li>
                                                    <article <?php post_class('row', get_the_ID() );?>>
                                                        <div class="large-12 small-12 columns <?php echo implode(' ', $postClasses);?>">
                                                            <div class="featimg" data-hover-effeckt-type="{{settings.contentLayouts.frontPage.hoverAnimation}}">
                                                                <a class="entry-img" href="<?php echo $permalink ?>">
                                                                    <?php if($post_type_name == "post"):?>
                                                                        <span class="post-category">
                                                                            <?php
                                                                            $category = get_the_category();
                                                                            $term_meta = get_option( "taxonomy_".$category[0]->term_id );
                                                                            $color = $term_meta['tax_image'] ? $term_meta['tax_image'] : '';?>
                                                                                    <span  style="background:<?php echo $color ?>">
                                                                            <?php echo $category[0]->name;?>
                                                                            </span>
                                                                        </span>
                                                                    <?php
                                                                    endif;
                                                                    if( has_post_thumbnail() ){
                                                                        require_once get_template_directory() . '/vendors/aq_resizer.php';
                                                                        $monstrotheme = MonstroTheme::getInstance();
                                                                        $imageSize = $monstrotheme->settings->imageSizes->widgets;
                                                                        $thumbID = get_post_thumbnail_id();
                                                                        $src = wp_get_attachment_url($thumbID);
                                                                        ?>
                                                                        <img src="<?php echo aq_resize( $src, $imageSize->width, $imageSize->crop ? $imageSize->height : null, true, true, true);?>"/>
                                                                    <?php } ?>
                                                                </a>
                                                                <?php
                                                                $votes = gettype($likesAndViews->getVotes() ) == 'string' ? $likesAndViews->getVotes() : '0';


                                                                ?>
                                                                <div class="hover-toggle" ng-if="1 == settings.votes.enable && latestPosts" ng-init="post = latestPosts.posts[<?php echo get_the_ID()  ?>]">
                                                                    <div monstro-votes icon="settings.votes.icon" votes="<?php echo $votes;?>"
                                                                         vote="<?php echo $likesAndViews->getVote();?>" post-id="<?php echo get_the_ID();?>"></div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="large-12 small-12 columns">
                                                            <h6>
                                                                <a href="<?php echo $permalink?>"><?php echo the_title() ;?></a>
                                                            </h6>
                                                        </div>
                                                    </article>
                                                </li>
                                                <?php
                                            }
                                            wp_reset_query();
                                        }else{
                                            _e("There are no posts to list.","monstrotheme");
                                        } ?>
                                        </ul>
                            </div>
                                <?php endif;?>
                    </div>
                </aside>
                    <script>
                        function tab(tab) {
                            document.getElementById('tab1').style.display = 'none';
                            document.getElementById('tab2').style.display = 'none';
                            document.getElementById('li_tab1').setAttribute("class", "");
                            document.getElementById('li_tab2').setAttribute("class", "");
                            document.getElementById(tab).style.display = 'block';
                            document.getElementById('li_'+tab).setAttribute("class", "active");
                        }
                    </script>
                <?php
            echo $after_widget;
        }

        function update( $new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title']              = strip_tags( $new_instance['title'] );
			$instance['nr_posts']        	= strip_tags( $new_instance['nr_posts'] );
            $instance['most_viewed']        = !empty($new_instance['most_viewed']) ? 1 : 0;
            $instance['most_voted']         = !empty($new_instance['most_voted']) ? 1 : 0;
            $instance['post_type_name']     = strip_tags( $new_instance['post_type_name']);

            return $instance;
        }

        function form($instance) {
            $instance       = wp_parse_args( (array) $instance, array( 'title' => '' , 'nr_posts' => '5','most_viewed' => '' , 'most_voted' => '', 'post_type_name' => '') );
            $title          = strip_tags( $instance['title'] );
			$nr_posts    	= strip_tags( $instance['nr_posts'] );
			$most_viewed    = strip_tags( $instance['most_viewed'] );
			$most_voted     = strip_tags( $instance['most_voted'] );
            $post_type_name = strip_tags( $instance['post_type_name']);
    ?>

            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','monstrotheme') ?>:
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'nr_posts' ); ?>"><?php _e( 'Number of Posts' , 'monstrotheme' ); ?>:</label>
                <input id="<?php echo $this->get_field_id( 'nr_posts' ); ?>"  size="3" name="<?php echo $this->get_field_name('nr_posts'); ?>" type="text" value="<?php echo $nr_posts; ?>" />
            </p>
            <p>
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('most_voted'); ?>" name="<?php echo $this->get_field_name('most_voted'); ?>"<?php checked( $most_voted ); ?> />
                <label for="<?php echo $this->get_field_id('most_voted'); ?>"><?php _e( 'Enable most voted posts tab' , 'monstrotheme' ); ?></label></p>
            </p>
            <p>
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('most_viewed'); ?>" name="<?php echo $this->get_field_name('most_viewed'); ?>"<?php checked( $most_viewed ); ?> />
                <label for="<?php echo $this->get_field_id('most_viewed'); ?>"><?php _e( 'Enable most viewed posts tab' , 'monstrotheme' ); ?></label></p>
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
        }
    }

    register_widget( 'Top_posts' );
?>