<?php
class MonstroJsonMenuWalker extends Walker{
    private $response = array();
    var $db_fields = array(
        'parent' => 'menu_item_parent',
        'id'     => 'db_id'
    );

    private function getMenuItem($id){
        $item = $this->response[$id];
        $children = array();
        foreach($this->response as $childID=>$child){
            if($child['parent'] == $id){
                $children[] = $this->getMenuItem($childID);
            }
        }
        if(count($children)){
            $item['children'] = $children;
        }
        return $item;
    }

    function start_lvl( &$output, $depth = 0, $args = array() ) {

    }

    function end_lvl( &$output, $depth = 0, $args = array() ) {

    }

    function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
        $this->response[$object->ID] = array(
            'url' => $object->url,
            'title' => $object->title,
            'parent' => $object->menu_item_parent,
            'object' => $object->object,
            'object_type' => $object->type,
            'object_id' => $object->object_id
        );
    }

    function end_el( &$output, $object, $depth = 0, $args = array() ) {
    }

    public function getResponse(){
        $tree = array(
            'children' => array()
        );
        foreach($this->response as $id=>$item){
            if(0 == $item['parent']){
                $tree['children'][] = $this->getMenuItem($id);
            }
        }
        return $tree;
    }


}