<?php
if(!class_exists('MonstroThemePartial')){
class MonstroThemePartial{
    private static $instance = false;
    public static function getInstance(){
        if(false === self::$instance ){
            self::$instance = new MonstroThemePartial();
        }
        return self::$instance;
    }

    public function getSidebar(){
        if(isset($_GET['id']) && strlen($id = $_GET['id'])){
            dynamic_sidebar($id);
        }
    }

    private function getChildrenIds( $id, $items ) {
        $ids = wp_filter_object_list( $items, array( 'menu_item_parent' => $id ), 'and', 'ID' );
        foreach ( $ids as $id ) {
            $ids = array_merge( $ids, $this->getChildrenIds( $id, $items ) );
        }
        return $ids;
    }

    public function monstroGetHalfMenu($items, $args){
        $topLevel = wp_filter_object_list( $items, array( 'menu_item_parent' => 0 ), 'and', 'ID');
        $totalItems = count($topLevel);
        $half = (int) $totalItems / 2;
        $preserve = 1 == $args -> half ? array_slice($topLevel, 0, $half) : array_slice($topLevel, $half);
        foreach($preserve as $id){
            $preserve = array_merge($preserve, $this->getChildrenIds($id, $items));
        }
        foreach($items as $key => $item){
            if(!in_array($item->ID, $preserve)){
                unset($items[$key]);
            }
        }
        return $items;
    }

    function defaultMenu( $args ){
        extract( $args );
        $listArgs = array(
            'title_li' => '',
            'echo' => false
        );
        if(false !== $this->menuHalf){
            $total = substr_count(wp_list_pages($listArgs), '<li');
            $half = (int) $total / 2;
            $listArgs['number'] = $half;
            if(2 ==  $this->menuHalf){
                $listArgs['offset'] = $half;
            }
        }
        $listArgs['echo'] = true;
        echo "<$container class='$container_class' id='$container_id'><ul>";
        wp_list_pages($listArgs);
        echo "</ul></$container>";
    }



    function monstroItemdescription( $item_output, $item, $depth, $args ) {
        $desc = __( $item->post_content );
        if(!empty($desc)){
            return preg_replace('/(<a.*?>[^<]*?)</', '$1' . "<span class=\"menu-description\">{$desc}</span><", $item_output);
        }else{
            return preg_replace('/(<a.*?>[^<]*?)</', '$1' . "<", $item_output);
        }

    }

    public function menu($location = 'header_menu', $half = false){
        $this->menuHalf = $half;
        add_filter( 'walker_nav_menu_start_el', array($this, 'monstroItemdescription'), 10, 4);
        $menuArgs = array(
            'container' => 'nav',
            'container_class' => 'hide-for-small',
            'echo' => true,
            'fallback_cb' => array($this, 'defaultMenu')
        );
        if('header_menu' == $location){
            $menuArgs['container_class'] .= ' main-menu';
        } else if('footer_menu' == $location){
            $menuArgs['container_class'] .= ' footer-menu';
        }
        if(has_nav_menu('header_menu')){
            $menuArgs['theme_location'] = $location;
        }
        if(false !== $half){
            add_filter( 'wp_nav_menu_objects', array($this, 'monstroGetHalfMenu'), 10, 2);
            $menuArgs['half'] = $half;
            if(1 ==  $half){
                $menuArgs['container_class'] .= ' fr';
            }
        }
        wp_nav_menu($menuArgs);
        if(false !== $half){
            remove_filter( 'wp_nav_menu_objects', array($this, 'monstroGetHalfMenu'));
        }
    }

    public function mlMenu(){
        get_template_part('templates/ml-menu');
    }

    public function loginForm(){
        get_template_part('templates/modal/login/login');
    }

    public function registerForm(){
        get_template_part('templates/modal/login/register');
    }

    public function lostPasswordForm(){
        get_template_part('templates/modal/login/lost-password');
    }

    public function comments(){
        global $withcomments;
        $withcomments = true;
        comments_template();
    }

    public function comment($comment, $args, $depth ){
        global $comment;
        switch ( $comment->comment_type ) {
            case '': ?>
                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                    <article id="comment-<?php comment_ID(); ?>" class="comment-body">
                        <?php if (get_avatar(get_the_author_meta('ID',$comment -> user_id)) != '') {?>
                            <div class="monstro-comment-thumb"><?php echo get_avatar( $comment, 70 ); ?></div>
                        <?php } ?>
                        <div class="monstro-comment-quote">
                            <header class="monstro-comment-textinfo st">
                                <span class="user"><?php _e( 'by' , 'monstrotheme'); ?> <?php echo get_comment_author_link($comment->comment_ID); ?></span>
                                <span class="time"><?php _e( 'on' , 'monstrotheme'); ?> <?php printf( __( '%1$s&nbsp;&nbsp;%2$s', 'monstrotheme' ), get_comment_date() , get_comment_time() );  ?></span>
                                <?php if((is_user_logged_in() && get_comment_author() == get_current_user_id()) || current_user_can('administrator')){?>
                                    <span class="edit">
                                    <?php edit_comment_link(__( 'Edit' , 'monstrotheme' ));?>
                                    </span>
                                <?php } ?>
                                <?php if ( $comment->comment_approved == '0' ) : ?>
                                    <br/><em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'monstrotheme' ); ?></em>
                                <?php endif; ?>
                                <span class="gray reply fr"><?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span>
                            </header>
                            <p>
                            <?php comment_text(); ?>
                            </p>
                        </div>
                    </article>
                </li>
            <?php break;
            case 'pingback':
            case 'trackback': ?>
                <li class="pingback">
                    <p>
                        <?php
                        _e( 'Pingback' , 'monstrotheme' ); ?> : <?php comment_author_link(); ?><?php edit_comment_link( '(' . __( 'Edit' , 'monstrotheme' ) . ')' , ' ' );
                        ?>
                    </p>
                </li>
            <?php break;
        }
    }

    public function related(){
        $monstrotheme = MonstroTheme::getInstance();
        $monstrotheme->doRelatedQuery($_GET['taxonomy']);
        get_template_part('noscript/views/single/related/related-posts');
    }

    public function pagination(){
        global $wp_query;
        echo paginate_links( array(
            'base' => str_replace( 999999999, '%#%', remove_query_arg(MONSTROTHEME_PARTIAL_ENDPOINT, get_pagenum_link(999999999, false))),
            'current' => max( 1, get_query_var('page'), get_query_var('paged') ),
            'total' => $wp_query->max_num_pages,
            'mid_size' => 5
        ) );
    }

    public function resolveAjax(){
        global $wp_query;
        if(isset($wp_query->query_vars[MONSTROTHEME_PARTIAL_ENDPOINT])){
            $monstrotheme = MonstroTheme::getInstance();
            switch($wp_query->query_vars[MONSTROTHEME_PARTIAL_ENDPOINT]){
                case 'sidebar':
                    $monstrotheme->onCustomQuery();
                    $this->getSidebar();
                    break;
                case 'menu':
                    $monstrotheme->onCustomQuery();
                    $half = isset($_GET['half']) ? $_GET['half'] : false;
                    $this->menu($_GET['location'], $half);
                    break;
                case 'mlMenu':
                    $this->mlMenu();
                    break;
                case 'loginForm':
                    $this->loginForm();
                    break;
                case 'lostPasswordForm':
                    $this->lostPasswordForm();
                    break;
                case 'registerForm':
                    $this->registerForm();
                    break;
                case 'comments':
                    $this->comments();
                    break;
                case 'related':
                    $this->related();
                    break;
                case 'pagination':
                    $monstrotheme->onCustomQuery();
                    $this->pagination();
            }
            exit;
        }
    }
}
};
MonstroThemePartial::getInstance();
