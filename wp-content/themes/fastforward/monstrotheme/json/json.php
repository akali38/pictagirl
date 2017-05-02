<?php

class MonstroThemeJson
{
    private static $instance = false;
    private $isPrecache = false;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new MonstroThemeJson();
        }
        return self::$instance;
    }

    private function defaultAction()
    {
        get_template_part('monstrotheme/monstro-wp-data');
        $WPData = new MonstroWPData();
        return $WPData->getData();
    }

    private function getMenu()
    {
        $location = $_GET['location'];
        get_template_part('monstrotheme/json/MonstroJsonMenuWalker');
        $walker = new MonstroJsonMenuWalker();
        wp_nav_menu(array(
            'theme_location' => $location,
            'container' => '',
            'items_wrap' => '',
            'echo' => false,
            'walker' => $walker
        ));
        return $walker->getResponse();
    }

    private function sidebars()
    {
        $sidebars = get_option(SIDEBARS_OPTION_NAME, false);
        if (!is_array($sidebars)) {
            $sidebars = array();
        }
        if (isset($_REQUEST['includeDefault'])) {
            $sidebars[] = array(
                'name' => __(MONSTROTHEME_NAME . ' ' . __("default sidebar", 'monstrotheme')),
                'id' => MONSTROTHEME_SLUG . '-default-sidebar',
            );
        }
        return $sidebars;
    }

    private function doLatestQuery($post_type='post')
    {
        global $wp_query;
        $monstrotheme = MonstroTheme::getInstance();
        remove_filter('pre_get_posts', array($monstrotheme, 'onPreGetPosts'));
        $args = array(
            'orderby' => 'created',
            'post_type' => $post_type
        );
        if(isset($_GET['number'])){
            $args['posts_per_page'] = $_GET['number'];
        }
        $wp_query = new WP_Query($args);
    }

    public function __construct()
    {
        header('Content-Type: application/json');
        switch ($_GET[MONSTROTHEME_JSON_ENDPOINT]) {
            case 'menu':
                $response = $this->getMenu();
                break;
            case 'latest-posts':
                $this->doLatestQuery();
                $response = $this->defaultAction();
                break;
            case 'latest-videos':
                $this->doLatestQuery('video');
                $response = $this->defaultAction();
                break;
            case 'categories':
                $response = get_categories( array('hide_empty' => 0) );
                break;
            case 'video-categories':
                $response = get_terms('video-category', array('hide_empty' => false) );
                break;
            case 'sidebars':
                $response = $this->sidebars();
                break;
            default:
                $response = $this->defaultAction();
        }
        echo json_encode($response);
        exit;
    }
}
MonstroThemeJson::getInstance();

