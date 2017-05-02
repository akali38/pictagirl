<?php
class MonstroWPData{
    private $data = array();
    private $isPrecache = false;
    public function __construct(){
        require_once(get_template_directory() . '/monstrotheme/monstro-likes-and-views.php');
        global $wp_query;
        $monstrotheme = MonstroTheme::getInstance();
        $monstrotheme->onCustomQuery();
        $this->data['specialPage'] = false;
        if(isset($wp_query->query_vars[$monstrotheme->settings->slugs->frontendPosting->addNew])) {
            $this->data['specialPage'] = 'frontend-submit';
            $this->data['currentContentLayout'] = 'page';
        } else if($wp_query->query_vars['pagename'] == $monstrotheme->settings->slugs->login->login){
            $this->data['currentContentLayout'] = 'frontPage';
        } else {
            if(is_front_page() || is_404()) {
                $this->data['currentContentLayout'] = 'frontPage';
            } else if(is_post_type_archive('gallery') || is_tax('gallery-category') || is_tax('gallery-tag')){
                $this->data['currentContentLayout'] = 'galleryArchive';
            } else if(is_post_type_archive('video') || is_tax('video-category') || is_tax('video-tag')){
                $this->data['currentContentLayout'] = 'videoArchive';
            } else if(is_archive() || is_search() || (is_home() && !is_front_page())){
                $this->data['currentContentLayout'] = 'postArchive';
            } else if(is_page()){
                $this->data['currentContentLayout'] = 'page';
            } else if(is_single()) {
                switch (get_post_type()) {
                    case 'video':
                        $this->data['currentContentLayout'] = 'video';
                        break;
                    case 'gallery':
                        $this->data['currentContentLayout'] = 'gallery';
                        break;
                    default:
                        $this->data['currentContentLayout'] = 'post';
                }
            } else {
                exit;
            }
        }

        $likesAndViews = MonstroLikesAndViews::getInstance();

        $this->data['isPage'] = is_page();
        $this->data['isSingle'] = is_single();
        $this->data['isHome'] = is_home();
        $this->data['isFrontPage'] = is_front_page();
        $this->data['isArchive'] = is_archive();
        $this->data['is404'] = is_404();
        if(is_day()){
            $this->data['archiveTitle'] = sprintf( __( 'Daily Archives: <span>%s</span>', 'monstrotheme' ), get_the_date() );
        }else if(is_month()){
            $this->data['archiveTitle'] = sprintf( __( 'Monthly Archives: <span>%s</span>', 'monstrotheme' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'monstrotheme' ) ) );
        }else if(is_year()){
            $this->data['archiveTitle'] = sprintf( __( 'Yearly Archives: <span>%s</span>', 'monstrotheme' ), get_the_date( _x( 'Y', 'yearly archives date format', 'monstrotheme' ) ) );
        }else if(is_author()){
            $this->data['archiveTitle'] = sprintf( __('Author: <span>%s</span>', 'monstrotheme'), get_the_author());
        }else if(is_tax() || is_category() || is_tag()) {
            $this->data['archiveTitle'] = single_term_title('', false);
        }else if(is_search()) {
            $this->data['archiveTitle'] = __('Search results:', 'monstrotheme') . ' <span>' . $_GET['s'] . '</span>';
        }else if(is_post_type_archive('video')){
            $this->data['archiveTitle'] = __( 'Videos', 'monstrotheme' );
        }else if(is_post_type_archive('gallery')){
            $this->data['archiveTitle'] = __( 'Galleries', 'monstrotheme' );
        }else if(is_archive()){
            $this->data['archiveTitle'] = __( 'Blog Archives', 'monstrotheme' );
        }

        $this->data['title'] = get_bloginfo('name') . ' &raquo; ' . get_bloginfo('description');
        if(is_single()){
            ob_start();
            wp_title();
            $this->data['title'] .= ob_get_clean();
        }

        global $post;
        global $wpdb;
        $monstroViewsTable = $wpdb->prefix . 'monstro_views';
        $this->data['bodyClasses'] = get_body_class();
        $this->data['posts'] = array();
        ;
        global $paged;
        if ( !is_single() ) {
            if ( !$paged ){
                $paged = 1;
            }
            $nextpage = intval($paged) + 1;
            if ( !$wp_query->max_num_pages || $wp_query->max_num_pages >= $nextpage ){
                $this->data['nextPostsLink'] = remove_query_arg(MONSTROTHEME_JSON_ENDPOINT, get_pagenum_link($nextpage, false));
            }
        }

        $this->data['havePagination'] = $wp_query->max_num_pages > 1;
        while(have_posts()){
            the_post();
            $postData = array();
            $postData['id'] = get_the_ID();
            $postData['post_title'] = get_the_title();
            if(has_post_format()){
                $postData['postFormat'] = array(
                    'link' => get_post_format_link(get_post_format()),
                    'slug' => get_post_format(),
                    'string' => get_post_format_string(get_post_format())
                );
            }
            @ob_start();
            preg_match( '/<!--more(.*?)?-->/', $post->post_content ) ? the_content() :the_excerpt();
            $postData['post_excerpt'] = @ob_get_clean();
            @ob_start();
            $queried_post_type = get_query_var('post_type');
            if ( is_single() && 'gallery' ==  $queried_post_type ) {
                $content = get_the_content();
                preg_match('/\[gallery (?P<content>.*)\]/', $content, $matches);
                if($matches){
                    $content = preg_replace('/\[gallery (?P<content>.*)\]/', '',  $content , 1);
                    $content = apply_filters('the_content', $content );
                }else{
                    preg_match('/\[gallery\]/', $content, $matches);
                    $content = preg_replace('/\[gallery+\]/', '',  $content , 1);
                    $content = apply_filters('the_content', $content );
                }
                echo $content;
                ob_start();
                ob_clean();
                echo do_shortcode($matches[0]);
                $postData['post_shortcode'] = ob_get_clean();
            }else{
                $postData['post_shortcode'] = null;
                the_content();
            }
            if ( ! post_password_required() ) {
                $postData['post_slider_height'] = $monstrotheme->settings->imageSizes->gallery->height;
            }else{
                $postData['post_slider_height'] = null;

            }
            $postData['post_content'] = @ob_get_clean();

            if ( get_post_format_string( get_post_format()) == 'Gallery' ){
                $postData['post_mime_type'] = get_post_format_string( get_post_format());
            }else{
                $postData['post_mime_type'] = null;
            }


            if(has_post_format()){
                if(get_post_format()== "aside"|get_post_format()== "link"|get_post_format()== "quote"|get_post_format()== "status"){
                    $postData['post_excerpt'] = $postData['post_content'];
                }
            }
            if(has_post_thumbnail()){
                require_once get_template_directory() . '/vendors/aq_resizer.php';
                $monstrotheme = MonstroTheme::getInstance();
                $imageSizeSlug = 'post';
                switch($this->data['currentContentLayout']){
                    case 'post':
                    case 'video':
                        $imageSizeSlug = 'post';
                        break;
                    case 'gallery':
                        $imageSizeSlug = 'post';
                        break;
                    case 'page':
                        $imageSizeSlug = 'page';
                        break;
                    default:
                        if('thumb' == $monstrotheme->settings->contentLayouts->{$this->data['currentContentLayout']}->viewType){
                            $imageSizeSlug = 'grid';
                        }else{
                            $imageSizeSlug = $monstrotheme->settings->contentLayouts->{$this->data['currentContentLayout']}->viewType;
                        }
                }

                $imageSize = $monstrotheme->settings->imageSizes->{$imageSizeSlug};
                $thumbID = get_post_thumbnail_id();
                $src = wp_get_attachment_url($thumbID);
                $postData['featimg'] = aq_resize( $src, $imageSize->width, $imageSize->crop ? $imageSize->height : null, true, true, true);
                $featimgFileType = wp_check_filetype($src);
                if('gif' == $featimgFileType['ext']){
                    $postData['animation'] = $src;
                }

                $attachment = get_post($thumbID);
                if ($attachment) {
                    $caption = $attachment->post_excerpt;
                    $postData['featimgCaption'] = $caption;
                }
            }
            $monstroMeta = get_post_meta($postData['id'], MONSTROTHEME_POST_META_NAME, true);
            if(!is_array($monstroMeta)){
                $monstroMeta = array();
            }
            $postData['meta'] = $monstroMeta;
            $postData['postType'] = get_post_type();
            if('video' == $postData['postType'] && isset($monstroMeta['videoUrl'])){
                global $wp_embed;
                $video = $monstroMeta['videoUrl'];
                if(preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $video)){
                    $postData['video'] = $wp_embed->run_shortcode( '[embed]' . $video . '[/embed]' );
                } else {
                    $postData['video'] = $video;
                }

            }
            $postData['permalink'] = get_permalink();
            $postData['terms'] = array();
            $terms = wp_get_post_terms($post->ID, array('category', 'post_tag', 'video-category', 'video-tag', 'gallery-category', 'gallery-tag'));
            foreach($terms as $term){
                /*Custom Category Color*/
                $term_meta = get_option( "taxonomy_".$term -> term_id );
                $color = $term_meta['tax_image'] ? $term_meta['tax_image'] : '';

                $taxonomy = $term -> taxonomy;
                $postData['terms'][$taxonomy][] = array(
                    'id' => $term -> term_id,
                    'title' => $term -> name,
                    'url' => get_term_link($term),
                    'slug' => $term -> slug,
                    'color' => $color
                );
            }
            $postData['commentsOpen'] = comments_open();
            if($postData['commentsOpen']){
                $postData['nrComments'] = get_comments_number();
                $postData['haveComments'] = 1;
            }else{
                $postData['haveComments'] = null;
            }

            $postData['commentsPassword'] = post_password_required();
            if(! $postData['commentsPassword']){
                $postData['commentsVisibility'] = 1;
            }else{
                $postData['commentsVisibility'] = 0;
            }

            $postData['pingsOpen'] = pings_open();
            $comments = get_comments('status=approve&type=pings&post_id=' . $post->ID );
            $comments = separate_comments( $comments );
            $postData['havePings'] = 0 < count( $comments[ 'pings' ] );
            $postData['author'] = array(
                'id' => $post->post_author,
                'name' => get_the_author(),
                'bio' => get_the_author_meta('description'),
                'website' => get_the_author_meta('url'),
                'url' => get_author_posts_url($post->post_author),
                'avatar' => get_avatar($post->post_author)
            );
            $postData['postClasses'] = get_post_class();
            $year = get_the_time('Y');
            $month = get_the_time('m');
            $day = get_the_time('d');
            $postData['date'] = array(
                'text' => get_the_date(),
                'm' => $year . $month . $day,
                'url' => get_day_link($year, $month, $day)
            );

            $postIds[] = $postData['id'];
            if(is_single()){
                ob_start();
                wp_link_pages(
                    array(
                        'before' => '<p>' . __("Pages:","monstrotheme"),
                        'after' => '</p>',
                        'next_or_number' => 'number'
                    )
                );
                $postData['linkPage'] = ob_get_clean();

                if(!$this->isPrecache){
                    $wpdb->replace(
                        $monstroViewsTable,
                        array(
                            'post_id' => get_the_ID(),
                            'user_ip' => $_SERVER['REMOTE_ADDR'],
                            'user_id' => get_current_user_id()
                        ),
                        array(
                            '%d',
                            '%s',
                            '%d'
                        )
                    );
                }
            }
            $postData['views'] = $likesAndViews->getViews();
            $postData['votes'] = $likesAndViews->getVotes();
            $postData['vote'] = $likesAndViews->getVote();
            $postData['next_post'] = "" ;
            $postData['prev_post'] = "" ;
            $this->data['posts'][] = $postData;
        }
    }

    public function getData(){
        return $this->data;
    }
}