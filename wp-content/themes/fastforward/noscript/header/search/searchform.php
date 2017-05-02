<?php
$monstrotheme = MonstroTheme::getInstance();
?>
<div id="searchform" class="<?php echo $monstrotheme->settings->header->search->type;?> searchform">
    <form id="ui-element" action="javascript:void(0);" method="post">
        <input class="searchform-input" placeholder="<?php _e('Search', 'monstrotheme');?>" type="search" name="search"
               id="search" data-ng-model="searchQuery" autocomplete="off"/>
        <input class="searchform-submit" type="submit" value="" />
        <span class="sb-icon-search"></span>
        <ul
            class="monstro-dropdown"></ul>
    </form>
</div>