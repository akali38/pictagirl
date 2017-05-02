<?php
require_once get_template_directory() . '/monstrotheme/partials.php';
$monstrotheme = MonstroTheme::getInstance();
$partial = MonstroThemePartial::getInstance();
$menuWrapperClasses = array();
if($monstrotheme->settings->header->menu->enableDropdownMenu){
    $menuWrapperClasses[] = 'drop-down';
}
?>

<div class="row">
    <div class="should-hide-for-small large-5 columns <?php echo implode(' ', $menuWrapperClasses);?>">
        <?php $partial->menu('header_menu', 1);?>
    </div>

    <div class="small-1 hide-for-large columns">
        <a href="javascript:void(0)" id="trigger" class="menu-trigger">&nbsp;</a>
    </div>

    <div class="small-5 large-2 columns">
        <?php get_template_part('noscript/header/logo/' . $monstrotheme->settings->header->logo->type);?>
    </div>

    <div class="should-hide-for-small large-5 columns <?php echo implode(' ', $menuWrapperClasses);?>">
        <?php $partial->menu('header_menu', 2);?>
    </div>

    <?php if($monstrotheme->settings->header->showSocialIcons || $monstrotheme->settings->header->search->show){?>
        <div class="small-3 large-12 columns">
            <?php if($monstrotheme->settings->header->showSocialIcons){?>
                <?php get_template_part('noscript/header/social-icons');?>
            <?php } else {?>
                <!-- directive: monstro-placeholder getSocialIcons() -->
            <?php } ?>
        </div>
        <div class="small-3 large-12 columns">
            <?php if($monstrotheme->settings->header->search->show){?>
                <?php get_template_part('noscript/header/search/searchform');?>
            <?php } ?>
        </div>
    <?php } ?>
</div>