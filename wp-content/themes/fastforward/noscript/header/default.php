<?php
require_once get_template_directory() . '/monstrotheme/partials.php';
$monstrotheme = MonstroTheme::getInstance();
$partial = MonstroThemePartial::getInstance();
$socialAndLoginWrapperColumns = 2;
if(!$monstrotheme->settings->header->search->show){
    $socialAndLoginWrapperColumns+=2;
}
$menuWrapperColumns = 6;
if(!($monstrotheme->settings->header->showSocialIcons || $monstrotheme->settings->header->loginMenu->show)){
    $menuWrapperColumns += $socialAndLoginWrapperColumns;
}
$menuWrapperClasses = array();
$menuWrapperClasses[] = "large-" . $menuWrapperColumns;
if($monstrotheme->settings->header->menu->enableDropdownMenu){
    $menuWrapperClasses[] = 'drop-down';
}
?>
<div class="row">
    <div class="small-1 hide-for-large columns">
        <a href="javascript:void(0)" id="trigger" class="menu-trigger">&nbsp;</a>
    </div>
    <div class="small-5 large-2 columns">
        <?php get_template_part('noscript/header/logo/' . $monstrotheme->settings->header->logo->type);?>
    </div>
    <div class="should-hide-for-small <?php echo implode(' ', $menuWrapperClasses);?> columns">
        <?php $partial->menu();?>
    </div>
    <?php if($monstrotheme->settings->header->showSocialIcons || $monstrotheme->settings->header->loginMenu->show){?>
    <div class="small-3 large-<?php echo $socialAndLoginWrapperColumns;?> columns">
        <?php if($monstrotheme->settings->header->showSocialIcons){?>
        <?php get_template_part('noscript/header/social-icons');?>
        <?php } ?>
        <?php if($monstrotheme->settings->header->loginMenu->show){?>
        <?php get_template_part('noscript/header/login-menu');?>
        <?php } ?>
    </div>
    <?php } ?>

    <?php if($monstrotheme->settings->header->search->show){?>
        <div class="small-3 large-2 columns">
            <?php get_template_part('noscript/header/search/searchform');?>
        </div>
    <?php } ?>
</div>