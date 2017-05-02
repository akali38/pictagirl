<?php
$monstrotheme = MonstroTheme::getInstance();
$socialAndLoginWrapperColumns = 2;
if(!$monstrotheme->settings->header->search->show){
    $socialAndLoginWrapperColumns+=2;
}
$logoWrapperColumns = 7;
if(!($monstrotheme->settings->header->showSocialIcons || $monstrotheme->settings->header->loginMenu->show)){
    $logoWrapperColumns += $socialAndLoginWrapperColumns;
}
?>
<div class="row">
    <div class="small-1 large-1 columns">
        <a href="#" id="trigger" class="menu-trigger">&nbsp;</a>
    </div>

    <div class="small-5 large-<?php echo $logoWrapperColumns;?> columns">
        <?php get_template_part('noscript/header/logo/' . $monstrotheme->settings->header->logo->type);?>
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
        <div class="small-3 large-2 columns" data-ng-if="showSearchform()">
            <?php get_template_part('noscript/header/search/searchform');?>
        </div>
    <?php } ?>
</div>