<?php
class MonstroMlPushMenuWalker extends Walker_Nav_Menu{
    private $parentTitles = array();
    private $startingALevel = false;
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        if($depth || $depth === 0){
            $output .= PHP_EOL . $indent . '<div class="mp-level" data-level="' .  ($depth + 2) . '">' . PHP_EOL;
            $this->startingALevel = true;
        } else {
            parent::end_lvl($output, $depth, $args);
        }
    }

    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        if($depth || $depth == 0){
            $output .= $indent . '</ul>' . PHP_EOL;
            $output .= $indent . '</div>' . PHP_EOL;
        } else {
            parent::end_lvl($output, $depth, $args);
        }
    }

    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $this->parentTitles[$item->ID] = $item->title;
        if($this->startingALevel){
            $output .= PHP_EOL . $indent . '<h2>' . $this->parentTitles[$item->menu_item_parent] . '</h2>';
            $output .= PHP_EOL . $indent . '<a class="mp-back" href="javascript:void(0);">' . __('back', 'monstrotheme') . '</a>';
            $output .= PHP_EOL . $indent . '<ul>';
            $this->startingALevel = false;
        }
        parent::start_el($output, $item, $depth, $args, $id);
    }
}
$menuArgs = array(
    'container' => false,
    'container_class' => 'main-menu hide-for-medium hide-for-small',
    'echo' => true,
    'walker' => new MonstroMlPushMenuWalker()
);
if(has_nav_menu('header_menu')){
    $menuArgs['theme_location'] = 'header_menu';
}
?>

<div class="mp-level" data-level="1">
    <h2><?php bloginfo('name');?></h2>
    <?php wp_nav_menu($menuArgs);?>
</div>