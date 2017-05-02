<?php
define('MONSTROTHEME_NAME', 'FastForward');
define('MONSTROTHEME_SLUG', 'fastforward');
define('MONSTROTHEME_SETTINGS', MONSTROTHEME_NAME . '_settings');
define('SIDEBARS_OPTION_NAME', 'monstrotheme_sidebars');
define('MONSTROTHEME_JSON_ENDPOINT', 'monstro-json');
define('MONSTROTHEME_PARTIAL_ENDPOINT', 'monstro-partial');
define('MONSTROTHEME_PROFILE_ENDPOINT', 'profile');
define('MONSTROTHEME_POST_META_NAME', 'monstrotheme');
define('MONSTROTHEME_VERSION', 3);

class MonstroTheme{
    private static $instance = false;
    public $isAjax = false;
    public $isAdmin = false;
    public $isFrontend = false;
    public $isCustomizableFrontend = false;
    public $uri;
    public $WPData;
    private $scripts = array();
    private $localize = array();

    public function onActivation(){
        get_template_part('monstrotheme/activation');
    }

    public function onDeactivation(){
        get_template_part('monstrotheme/deactivation');
    }

    private function onAjax(){
        $this->isAjax = true;
        get_template_part('monstrotheme/ajax');
    }

    private function onAdmin(){
        $this->isAdmin = true;
        if (!class_exists('MonstroThemeAdmin')) {
            get_template_part('monstrotheme/admin');
        }
    }

    public static function copy($source, $destination){
        foreach($source as $key=>$value){
            if(is_object($value)){
                self::copy($value, $destination->{$key});
            } else {
                if(!$destination){
                    $destination = json_decode(json_encode(array()));
                }
                $destination->{$key} = $value;
            }

        }
    }

    private function onFrontend(){
        $this->isFrontend = true;
        $this->localize['settings'] = $this->settings;
        //TODO: test this on non-multisite installation
        $site = 0;
        if (function_exists('get_current_site')) {
            $site = get_current_site();
        }
        $this->scripts['monstrotheme'] = array('angular-animate', 'angular-sanitize');
        $this->scripts['gagarin'] = array('monstrotheme');
        $this->scripts['modernizr.custom'] = array();
        if ('centered' == $this->settings->header->type && $this->settings->header->menu->enableHamburgerMenu) {
            $this->scripts['classie'] = array();
            $this->scripts['mlpushmenu'] = array('jquery', 'modernizr.custom', 'classie');
        }
        $this->localize['directives'] = array(
            'template_url' => $this->uri . '/templates/directives',
            'image_select_url' => $this->uri . '/images/image_select',

        );
        global $wp_rewrite;
        $this->localize['frontend'] = array(

            'wpRewrite' => $wp_rewrite,
            'site_id' => $site && $site->blog_id ? $site->blog_id : 0
        );
    }

    private function onCustomizableFrontend(){
        $this->isCustomizableFrontend = true;
        $this->ngApp = "MonstroThemeCustomizableFrontend";
        $this->scripts['gagarin'] = array('monstrotheme');
    }

    public function enqueueScripts(){
        if($this->isFrontend){
            if( is_singular() ){
                wp_enqueue_script( "comment-reply" );
            }
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script(
                'iris',
                admin_url( 'js/iris.min.js' ),
                array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
                false,
                1
            );
            wp_enqueue_script(
                'wp-color-picker',
                admin_url( 'js/color-picker.min.js' ),
                array( 'iris' ),
                false,
                1
            );
            $colorpicker_l10n = array(
                'clear' => __( 'Clear','monstrotheme'),
                'defaultString' => __( 'Default' , 'monstrotheme' ),
                'pick' => __( 'Select Color' , 'monstrotheme' )
            );
            wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n );
            wp_enqueue_style('roboto-font', 'https://fonts.googleapis.com/css?family=Roboto:400,700&amp;subset=latin,latin-ext,cyrillic');
            wp_enqueue_style('monstrotheme-frontend-style', get_template_directory_uri() . '/css/frontend.css');
			/*wp_enqueue_style('zoom', get_template_directory_uri() . '/css/zoom.css');
			wp_enqueue_script('transition', get_template_directory_uri() . '/transition.js');
			wp_enqueue_script('zoom-script', get_template_directory_uri() . '/zoom.js');
			*/
            wp_enqueue_script('monstrotheme-frontend-script', get_template_directory_uri() . '/frontend.js', array('jquery', 'wp-color-picker'), null, true);

            get_template_part('monstrotheme/monstro-wp-data');
            $prerenderedWPData = new MonstroWPData();
            $this->WPData = $prerenderedWPData->getData();
            global $wp_rewrite;
            $data = array(
                'templateDirectoryUri' => get_template_directory_uri(),
                'homeUrl' => get_home_url(),
                'wpRewriteFront' => $wp_rewrite->front,
                'jsonEndpoint' => MONSTROTHEME_JSON_ENDPOINT,
                'partialEndpoint' => MONSTROTHEME_PARTIAL_ENDPOINT,
                'baseSettings' => $this->settings,
                'userSettings' => $this->settings,
                'settings' => $this->settings,
                'WPData' => $this->WPData,
                'blogName' => get_bloginfo('name'),
                'rssLink' => get_bloginfo('rss2_url'),
                'loginUrl' => wp_login_url(),
                'logoutUrl' => wp_logout_url(),
                'lostPasswordUrl' => wp_lostpassword_url(),
                'registerUrl' => wp_registration_url(),
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'ajaxPost' => admin_url('admin-post.php'),
                'user' => is_user_logged_in() ? $this->transformWPUser(new WP_User(get_current_user_id())) : false,
                'specialPages' => array(
                    'addNew' => array()
                )
            );
            if($wp_rewrite->using_permalinks()){
                $data['specialPages']['frontendPosting']['addNew'] = home_url() . '/' . $this->settings->slugs->frontendPosting->addNew;
            } else {
                $data['specialPages']['frontendPosting']['addNew'] = add_query_arg($this->settings->slugs->frontendPosting->addNew, '', home_url() . '/');
            }
            wp_localize_script('monstrotheme-frontend-script', 'MonstroThemeData', $data);
        }
    }

    public static function getInstance(){
        if (!self::$instance) {
            self::$instance = new MonstroTheme();
        }
        return self::$instance;
    }

    public function onTemplateRedirect(){
        global $wp_query;
        if (isset($_GET[MONSTROTHEME_JSON_ENDPOINT])) {
            get_template_part('monstrotheme/json/json');
        } else if (isset($wp_query->query_vars[MONSTROTHEME_PARTIAL_ENDPOINT])) {
            get_template_part('monstrotheme/partials');
            $partial = MonstroThemePartial::getInstance();
            $partial->resolveAjax();
        }else if(isset($wp_query->query_vars[$this->settings->slugs->frontendPosting->addNew])) {
            get_template_part('index');
            exit;
        }else if(strlen($this->settings->slugs->login->login) && $wp_query->query_vars['pagename'] == $this->settings->slugs->login->login){
            $location = str_replace(home_url(), '', wp_login_url());
            if (isset($_GET['redirect_to'])) {
                $location = add_query_arg('redirect_to', $_GET['redirect_to'], $location);
            }
            wp_redirect(home_url() . '#!' . $location);
            exit;
        }
    }

    public function onDeletePost($postID){
        global $wpdb;
        $wpdb->delete($wpdb->prefix . 'monstro_views', array('post_id' => $postID), array('%d'));
        $wpdb->delete($wpdb->prefix . 'monstro_votes', array('post_id' => $postID), array('%d'));
    }

    public function getLoginUrl($oldUrl){
        if ($this->settings->slugs->login->login) {
            return str_replace('wp-login.php', $this->settings->slugs->login->login . '/', $oldUrl);
        }
        return $oldUrl;
    }

    public function getLogoutUrl($oldUrl){
        if ($this->settings->slugs->login->logout) {
            return home_url() . '/' . $this->settings->slugs->login->logout . '/';
        }
        return $oldUrl;
    }

    public function getRegistrationUrl($oldUrl){
        if ($this->settings->slugs->login->register) {
            return home_url() . '/' . $this->settings->slugs->login->register . '/';
        }
        return $oldUrl;
    }

    public function getLostPasswordUrl($oldUrl){
        if ($this->settings->slugs->login->lostPassword) {
            return home_url() . '/' . $this->settings->slugs->login->lostPassword . '/';
        }
        return $oldUrl;
    }

    public function registerRedirect(){
        //registration url
        $regpath = explode("/", wp_registration_url());
        $regurl = trim(end($regpath));
        $reglast = empty($regurl) ? $regpath[count($regpath) - 2] : $regurl;

        //requested url
        $redpath = explode("/", $_SERVER["REQUEST_URI"]);
        $redurl = trim(end($redpath));
        $redlast = empty($redurl) ? $redpath[count($redpath) - 2] : $redurl;

        if ($reglast == $redlast) {
            $location = str_replace(home_url(), '', wp_registration_url());
            if (isset($_GET['redirect_to'])) {
                $location = add_query_arg('redirect_to', $_GET['redirect_to'], $location);
            }
            wp_redirect(home_url() . '#!' . $location);
            exit;
        } else if ($this->settings->slugs->login->lostPassword) {
        }
    }

    public function doRelatedQuery($taxonomy)
{
        global $wp_query, $post;
        $query = array(
            'post_type' => get_post_type(),
            'posts_per_page' => 4,
            'ignore_sticky_posts' => true,
            'post__not_in' => array($post->ID)
        );
        switch ($taxonomy) {
            case 'post_tag':
                $queryTaxonomy = 'tag__in';
                break;
            default:
                $queryTaxonomy = $taxonomy . '__in';
        }
        $query[$queryTaxonomy] = array();
        $terms = wp_get_post_terms($post->ID, array($taxonomy));
        foreach ($terms as $term) {
            if(isset($term->term_id)){
                $query[$queryTaxonomy][] = $term->term_id;
            }
        }
        $wp_query = new WP_Query($query);
        return have_posts();
    }

    public static function saveMonstroMeta($postId){
        if ( ! isset( $_POST['monstroMetaBoxNonce'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['monstroMetaBoxNonce'], 'MonstroMetaBox' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $postId ) ) {
            return;
        }

        if ( ! isset( $_POST['monstro'] ) ) {
            return;
        }

        update_post_meta( $postId, MONSTROTHEME_POST_META_NAME, $_POST['monstro'] );
    }

    function onPreGetPosts( $query ) {
        if( $query->is_main_query() &&  ( is_home() || is_archive() ) && !is_post_type_archive('video') && !is_post_type_archive('gallery') && empty( $query->query_vars['suppress_filters'] ) ) {
            $query->set( 'post_type', array(
                'post', 'video','gallery'
            ));
        }
    }

    public function transformWPUser($WPUser){
        $postsUrl = get_author_posts_url($WPUser->ID);
        $data = array(
            'ID' => $WPUser->ID,
            'canCustomize' => user_can($WPUser, 'administrator'),
            'userPages' => array(
                'added' => $postsUrl
            )
        );
        if(isset($this->settings->slugs->userPages)){
            global $wp_rewrite;
            if($wp_rewrite->using_permalinks()){
                $data['userPages']['liked'] = $postsUrl . $this->settings->slugs->userPages->liked;
                $data['userPages']['disliked'] = $postsUrl . $this->settings->slugs->userPages->disliked;
                $data['userPages']['viewed'] = $postsUrl . $this->settings->slugs->userPages->viewed;
            } else {
                $data['userPages']['liked'] = add_query_arg($this->settings->slugs->userPages->liked, '', $postsUrl);
                $data['userPages']['disliked'] = add_query_arg($this->settings->slugs->userPages->disliked, '', $postsUrl);
                $data['userPages']['viewed'] = add_query_arg($this->settings->slugs->userPages->viewed, '', $postsUrl);
            }
        }
        return json_decode(json_encode($data));
    }

    public function add_tax_image_field(){
        ?>
        <tr class="term_meta[tax_image]">
            <th scope="row" valign="top">
                <label for="term_meta[tax_image]"><?php _e('Category Color', 'monstrotheme'); ?></label></th>
            <td>
                <div id="colorpicker">
                    <input type="text" name="term_meta[tax_image]" id="term_meta[tax_image]" class="colorpicker" size="3" style="width:20%;" value="<?php echo (isset($cat_meta['catBG'])) ? $cat_meta['catBG'] : '#fff'; ?>" />
                </div>
                <br />
                <span class="description"></span>
                <br />
            </td>
        </tr>
        <script>
            jQuery(document).ready(function($){
                $('.colorpicker').wpColorPicker();
            });
        </script>
    <?php
    }

    public function edit_tax_image_field( $term ){
        $term_id = $term->term_id;
        $term_meta = get_option( "taxonomy_$term_id" );
        $image = $term_meta['tax_image'] ? $term_meta['tax_image'] : '';
        ?>
        <tr class="term_meta[tax_image]">
            <th scope="row" valign="top">
                <label for="term_meta[tax_image]"><?php _e('Category Color', 'monstrotheme'); ?></label></th>
            <td>
                <div id="colorpicker">
                    <input type="text" name="term_meta[tax_image]" id="term_meta[tax_image]" class="colorpicker" size="3" style="width:20%;" value="<?php echo esc_url( $image ); ?>" />
                </div>
                <br />
                <span class="description"></span>
                <br />
            </td>
        </tr>
        <script>
            jQuery(document).ready(function($){
                $('.colorpicker').wpColorPicker();
            });
        </script>
    <?php
    }

    public function save_tax_meta( $term_id )
    {
        if (isset($_POST['term_meta'])) {
            $term_meta = array();
            $term_meta['tax_image'] = isset ($_POST['term_meta']['tax_image']) ? esc_url($_POST['term_meta']['tax_image']) : '';
            // Save the option array.
            update_option("taxonomy_$term_id", $term_meta);
        }
    }

    public function colorpicker_enqueue() {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'colorpicker-js', get_stylesheet_directory_uri() . '/scripts/colorpicker.js', array( 'wp-color-picker' ) );
    }

    public function colstyle() {
        echo "<style type='text/css' id='thumb'>th#thumb{width:60px;}</style>";
    }

    public function columns_head($columns) {
          $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['thumb'] = __('Color', 'monstrotheme');
        unset( $columns['cb'] );
        return array_merge( $new_columns, $columns );
    }

    public function columns_content_taxonomy($c, $column_name, $term_id) {
        $term_meta = get_option( "taxonomy_$term_id" );
        $image = $term_meta['tax_image'] ? $term_meta['tax_image'] : '';
        if ($column_name == 'thumb') {
            return '<span style="background-color: '.$image.'; padding:20px ; border:2px solid #ccc; display:inline-block; "></span>';

        }
        print_r($column_name);
    }

    public function onInit(){
        register_post_type('gallery', array(
            'labels' => array(
                'name' => __('Galleries', 'monstrotheme'),
                'singular_name' => __('Gallery', 'monstrotheme'),
                'add_new' => __('Add new', 'monstrotheme'),
                'add_new_item' => __('Add new gallery', 'monstrotheme'),
                'edit_item' => __('Edit gallery', 'monstrotheme'),
                'new_item' => __('New gallery', 'monstrotheme'),
                'all_items' => __('All galleries', 'monstrotheme'),
                'view_item' => __('View gallery', 'monstrotheme'),
                'search_items' => __('Search gallery', 'monstrotheme'),
                'not_found' => __('No gallery found', 'monstrotheme'),
                'not_found_in_trash' => __('No gallery found in trash', 'monstrotheme'),
                'parent_item_colon' => '',
                'menu_name' => __('Galleries', 'monstrotheme')
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'gallery'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments','revisions')
        ));

        register_post_type('video', array(
            'labels' => array(
                'name' => __('Videos', 'monstrotheme'),
                'singular_name' => __('Video', 'monstrotheme'),
                'add_new' => __('Add new', 'monstrotheme'),
                'add_new_item' => __('Add new video', 'monstrotheme'),
                'edit_item' => __('Edit video', 'monstrotheme'),
                'new_item' => __('New video', 'monstrotheme'),
                'all_items' => __('All videos', 'monstrotheme'),
                'view_item' => __('View video', 'monstrotheme'),
                'search_items' => __('Search videos', 'monstrotheme'),
                'not_found' => __('No videos found', 'monstrotheme'),
                'not_found_in_trash' => __('No videos found in trash', 'monstrotheme'),
                'parent_item_colon' => '',
                'menu_name' => __('Videos', 'monstrotheme')
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'video'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments','revisions')
        ));

        register_taxonomy('gallery-category', 'gallery', array(
            'hierarchical' => true,
            'labels' => array(
                'name' => __('Categories', 'monstrotheme'),
                'singular_name' => __('Category', 'monstrotheme'),
                'search_items' => __('Search categories', 'monstrotheme'),
                'all_items' => __('All categories', 'monstrotheme'),
                'parent_item' => __('Parent category', 'monstrotheme'),
                'parent_item_colon' => __('Parent category:', 'monstrotheme'),
                'edit_item' => __('Edit category', 'monstrotheme'),
                'update_item' => __('Update category', 'monstrotheme'),
                'add_new_item' => __('Add new category', 'monstrotheme'),
                'new_item_name' => __('New category name', 'monstrotheme'),
                'menu_name' => __('Categories', 'monstrotheme'),
            ),
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'gallery-category'),
        ));
        register_taxonomy('video-category', 'video', array(
            'hierarchical' => true,
            'labels' => array(
                'name' => __('Categories', 'monstrotheme'),
                'singular_name' => __('Category', 'monstrotheme'),
                'search_items' => __('Search categories', 'monstrotheme'),
                'all_items' => __('All categories', 'monstrotheme'),
                'parent_item' => __('Parent category', 'monstrotheme'),
                'parent_item_colon' => __('Parent category:', 'monstrotheme'),
                'edit_item' => __('Edit category', 'monstrotheme'),
                'update_item' => __('Update category', 'monstrotheme'),
                'add_new_item' => __('Add new category', 'monstrotheme'),
                'new_item_name' => __('New category name', 'monstrotheme'),
                'menu_name' => __('Categories', 'monstrotheme'),
            ),
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'video-category'),
        ));
        register_taxonomy('video-tag', 'video', array(
            'hierarchical' => false,
            'labels' => array(
                'name' => __('Tags',  'monstrotheme' ),
                'singular_name' => __('Tag',  'monstrotheme' ),
                'search_items' => __('Search tags', 'monstrotheme'),
                'all_items' => __('All tags', 'monstrotheme'),
                'edit_item' => __('Edit tag', 'monstrotheme'),
                'update_item' => __('Update tag', 'monstrotheme'),
                'add_new_item' => __('Add new tag', 'monstrotheme'),
                'new_item_name' => __('New tag name', 'monstrotheme'),
                'menu_name' => __('Tags', 'monstrotheme'),
            ),
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'video-tag'),
        ));
        register_taxonomy('gallery-tag', 'gallery', array(
            'hierarchical' => false,
            'labels' => array(
                'name' => __('Tags',  'monstrotheme' ),
                'singular_name' => __('Tag',  'monstrotheme' ),
                'search_items' => __('Search tags', 'monstrotheme'),
                'all_items' => __('All tags', 'monstrotheme'),
                'edit_item' => __('Edit tag', 'monstrotheme'),
                'update_item' => __('Update tag', 'monstrotheme'),
                'add_new_item' => __('Add new tag', 'monstrotheme'),
                'new_item_name' => __('New tag name', 'monstrotheme'),
                'menu_name' => __('Tags', 'monstrotheme'),
            ),
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'gallery-tag'),
        ));
    }

    public function includeTranslations(){
        if(($locale = get_locale()) != 'en_US') {
            if (file_exists(get_template_directory() . "/languages/$locale.po")) {
                echo '<link rel="gettext" href="' . get_template_directory_uri() . "/languages/$locale.po" . '" />';
            }
        }
    }

    public function onCustomQuery(){
        global $wp_query, $wpdb;
        if(!isset($this->settings->slugs->userPages)){
            return;
        }
        if(isset($wp_query->query_vars[$this->settings->slugs->userPages->liked])) {
            $monstroVotesTable = $wpdb->prefix . 'monstro_votes';
            $postIds = $wpdb->get_col(
                $wpdb->prepare("
                    SELECT post_id
                    FROM $monstroVotesTable
                    WHERE vote = 1
                    AND user_id = %d
                ;", get_the_author_meta('ID')
                )
            );
            if(empty($postIds)){
                $postIds = array(0);
            }
            $matched = preg_match('/\d+/', $wp_query->query_vars[$this->settings->slugs->userPages->liked], $matches);
            $wp_query = new WP_Query(array(
                'post__in' => $postIds,
                'ignore_sticky_posts' => true,
                'paged' => $matched ? $matches[0] : 1,
                'post_type' => array('post', 'video', 'gallery')
            ));
            $wp_query->is_archive = true;
        } else if(isset($wp_query->query_vars[$this->settings->slugs->userPages->disliked])) {
            $monstroVotesTable = $wpdb->prefix . 'monstro_votes';
            $postIds = $wpdb->get_col(
                $wpdb->prepare("
                    SELECT post_id
                    FROM $monstroVotesTable
                    WHERE vote = -1
                    AND user_id = %d
                ;", get_the_author_meta('ID')
                )
            );
            if(empty($postIds)){
                $postIds = array(0);
            }
            $matched = preg_match('/\d+/', $wp_query->query_vars[$this->settings->slugs->userPages->disliked], $matches);
            $wp_query = new WP_Query(array(
                'post__in' => $postIds,
                'ignore_sticky_posts' => true,
                'paged' => $matched ? $matches[0] : 1,
                'post_type' => array('post', 'video', 'gallery')
            ));
            $wp_query->is_archive = true;
        } else if(isset($wp_query->query_vars[$this->settings->slugs->userPages->viewed])) {
            $monstroViewsTable = $wpdb->prefix . 'monstro_views';
            $postIds = $wpdb->get_col(
                $wpdb->prepare("
                SELECT post_id
                FROM $monstroViewsTable
                WHERE user_id = %d
            ;", get_the_author_meta('ID')
                )
            );
            if(empty($postIds)){
                $postIds = array(0);
            }
            $matched = preg_match('/\d+/', $wp_query->query_vars[$this->settings->slugs->userPages->viewed], $matches);
            $wp_query = new WP_Query(array(
                'post__in' => $postIds,
                'ignore_sticky_posts' => true,
                'paged' => $matched ? $matches[0] : 1,
                'post_type' => array('post', 'video', 'gallery')
            ));
            $wp_query->is_archive = true;
        }
    }

    public function onWidgetsInit(){
        get_template_part('widgets/all-widgets');
    }

    public function __construct(){
        /*Script & Style*/
        add_action( 'admin_head', array( $this, 'colstyle'));
        add_action( 'admin_enqueue_scripts', array( $this, 'colorpicker_enqueue' ));
        /*Edit*/
        add_action( 'category_add_form_fields', array( $this, 'add_tax_image_field' ) );
        add_action( 'category_edit_form_fields', array( $this, 'edit_tax_image_field' ) );

        add_action( 'video-category_add_form_fields', array( $this, 'add_tax_image_field' ) );
        add_action( 'video-category_edit_form_fields', array( $this, 'edit_tax_image_field' ) );

        add_action( 'gallery-category_add_form_fields', array( $this, 'add_tax_image_field' ) );
        add_action( 'gallery-category_edit_form_fields', array( $this, 'edit_tax_image_field' ) );
        /*Save*/
        add_action( 'edited_category', array( $this, 'save_tax_meta' ), 10, 2 );
        add_action( 'create_category', array( $this, 'save_tax_meta' ), 10, 2 );

        add_action( 'edited_video-category', array( $this, 'save_tax_meta' ), 10, 2 );
        add_action( 'create_video-category', array( $this, 'save_tax_meta' ), 10, 2 );

        add_action( 'edited_gallery-category', array( $this, 'save_tax_meta' ), 10, 2 );
        add_action( 'create_gallery-category', array( $this, 'save_tax_meta' ), 10, 2 );
        /*Add To List*/
        add_filter('manage_edit-category_columns',array( $this, 'columns_head'));
        add_filter('manage_category_custom_column', array( $this, 'columns_content_taxonomy'), 10, 3);

        add_filter('manage_edit-video-category_columns',array( $this, 'columns_head'));
        add_filter('manage_video-category_custom_column', array( $this, 'columns_content_taxonomy'), 10, 3);

        add_filter('manage_edit-gallery-category_columns',array( $this, 'columns_head'));
        add_filter('manage_gallery-category_custom_column', array( $this, 'columns_content_taxonomy'), 10, 3);

        /**
         * To reset settings,
         * Uncomment line below,
         * Refresh once your web site,
         * Comment line back.
         */
        // delete_option(MONSTROTHEME_SETTINGS);

        $this->settings = get_option(MONSTROTHEME_SETTINGS, false);
        if (!$this->settings || !is_object($this->settings)) {
            get_template_part('monstrotheme/defaults');
            $this->settings = get_option(MONSTROTHEME_SETTINGS);
        }
        $this->uri = get_template_directory_uri();
        if(isset($this->settings->slugs->userPages)){
            add_rewrite_endpoint($this->settings->slugs->userPages->liked, EP_AUTHORS);
            add_rewrite_endpoint($this->settings->slugs->userPages->disliked, EP_AUTHORS);
            add_rewrite_endpoint($this->settings->slugs->userPages->viewed, EP_AUTHORS);
        }
        add_rewrite_endpoint(MONSTROTHEME_PROFILE_ENDPOINT, EP_AUTHORS);
        add_rewrite_endpoint(MONSTROTHEME_PARTIAL_ENDPOINT, EP_ALL);
        add_rewrite_endpoint($this->settings->slugs->frontendPosting->addNew, EP_ROOT);
        add_action('after_switch_theme', array($this, 'onActivation'));
        add_action('switch_theme', array($this, 'onDeactivation'));
        add_action('template_redirect', array($this, 'onTemplateRedirect'));
        add_action('delete_post', array($this, 'onDeletePost'));
        add_action('login_url', array($this, 'getLoginUrl'));
        add_action('logout_url', array($this, 'getLogoutUrl'));
        add_action('register_url', array($this, 'getRegistrationUrl'));
        add_action('lostpassword_url', array($this, 'getLostPasswordUrl'));
        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));
        add_action('save_post', array($this, 'saveMonstroMeta'));
        add_action('init', array($this, 'onInit'));
        add_action('wp_head', array($this, 'includeTranslations'));
        add_action('widgets_init', array($this, 'onWidgetsInit'));
        add_filter('pre_get_posts', array($this, 'onPreGetPosts'));
        $this->registerRedirect();
        $version = get_option(MONSTROTHEME_SLUG . '_version');
        if(!$version || ($version < MONSTROTHEME_VERSION)){
            $this->onActivation();
            update_option(MONSTROTHEME_SLUG . '_version', MONSTROTHEME_VERSION);
        }
        add_theme_support('post-thumbnails');
        add_theme_support( 'automatic-feed-links' );
        load_theme_textdomain( 'monstrotheme' );
        load_theme_textdomain( 'monstrotheme' , get_template_directory() . '/languages' );
        if ( function_exists( 'load_child_theme_textdomain' ) ){
            load_child_theme_textdomain( 'monstrotheme' );
        }

        add_theme_support( 'custom-header' );
        add_theme_support( 'custom-background' );
        add_theme_support('post-formats', array('image', 'gallery', 'aside', 'quote', 'link', 'chat', 'status', 'video', 'audio'));
        register_nav_menus(array(
            'header_menu' => __('Main menu', 'monstrotheme'),
            'footer_menu' => __('Footer menu', 'monstrotheme')
        ));
        $this->sidebars = get_option(SIDEBARS_OPTION_NAME);
        if (!is_array($this->sidebars)) {
            $this->sidebars = array();
        }
        foreach ($this->sidebars as $sidebar) {
            register_sidebar(array(
                'name' => $sidebar['name'],
                'id' => $sidebar['id'],
                'before_widget' => '<aside id="%1$s" class="widget"><div class="%2$s">',
                'after_widget' => '</div></aside>',
                'before_title' => '<p class="widget-delimiter">&nbsp;</p><h5 class="widget-title">',
                'after_title' => '</h5>',
            ));
        }
        register_sidebar(array(
            'name' => __(MONSTROTHEME_NAME . ' ' . __("default sidebar", 'monstrotheme')),
            'id' => MONSTROTHEME_SLUG . '-default-sidebar',
            'before_widget' => '<aside id="%1$s" class="widget"><div class="%2$s">',
            'after_widget' => '</div></aside>',
            'before_title' => '<p class="widget-delimiter">&nbsp;</p><h5 class="widget-title">',
            'after_title' => '</h5>',
        ));
        if (isset($_POST['action'])) {
            $this->onAjax();
        } else if (is_admin()) {
            $this->onAdmin();
        } else {
            $this->onFrontend();
            if (is_user_logged_in() && current_user_can('edit_theme_options')) {
                $this->onCustomizableFrontend();
            }
        }
        ob_start();
        get_template_part('shortcodes/all-shortcodes');
        ob_clean();
    }
}

MonstroTheme::getInstance();

