<?php
$monstrotheme = MonstroTheme::getInstance();
$currentContentLayout = $monstrotheme->WPData['currentContentLayout'];
$contentLayout = $monstrotheme->settings->contentLayouts->{$currentContentLayout};
$bodyClasses = array();
$bodyClasses[] = $contentLayout->width . '-view';
switch($monstrotheme->settings->header->type){
    case 'left': $bodyClasses[] = 'header-left'; break;
    case 'centered':
        if($monstrotheme->settings->header->menu->enableHamburgerMenu){
            $bodyClasses[] = 'header-hamburger';
        } else {
            $bodyClasses[] = 'header-centered';
        }
        break;
}
if('left' == $monstrotheme->settings->header->type){
}

$mainSectionClasses = array();
if( 'left' != $monstrotheme->settings->header->type && $contentLayout->boxedContent &&
    'full' != $contentLayout->width){
    $mainSectionClasses[] = 'boxed';
}

$wrapperClasses = array();
if('left' != $monstrotheme->settings->header->type){
    $wrapperClasses[] = "align-" . $contentLayout->position;
}

$headerClasses = array();
if( ('default' == $monstrotheme->settings->header->type && $monstrotheme->settings->header->enableVerticalAlign) ||
    ('centered' == $monstrotheme->settings->header->type && 'logoInBetween' == $monstrotheme->settings->header->menu->position) ||
    ('centered' == $monstrotheme->settings->header->type && $monstrotheme->settings->header->menu->enableHamburgerMenu)){
    $headerClasses[] = 'vertical-align';
}
if('centered' == $monstrotheme->settings->header->type && !$monstrotheme->settings->header->menu->enableHamburgerMenu){
    switch($monstrotheme->settings->header->menu->position){
        case 'belowLogo': $headerClasses[] = 'menu-below-logo'; break;
        case 'logoInBetween': $headerClasses[] = 'menu-centered-logo'; break;
        case 'aboveLogo': $headerClasses[] = 'menu-above-logo'; break;
    }
}
if('left' != $monstrotheme->settings->header->type &&
    $contentLayout->boxedHeader &&
    'full' != $contentLayout->width ){
    $headerClasses[] = 'boxed';
}

$mainContainerClasses = array();
if(!empty($contentLayout->sidebar)){
    if($contentLayout->sidebar->id){
        if($contentLayout->sidebar->delimited){
            $mainContainerClasses[] = 'delimited shadow';
        }
        if($contentLayout->sidebar->withbg){
            $mainContainerClasses[] = 'withbg';
        }
        if('full' == $contentLayout->width || (
                !$contentLayout->boxedHeader &&
                !$contentLayout->boxedContent &&
                !$contentLayout->boxedFooter )){
            if($contentLayout->sidebar->floating){
                $mainContainerClasses[] = 'floating-sidebar';
            }
        }
    }

    $sidebarClasses = array();
    $sidebarClasses[] = 'columns';
    $sidebarClasses[] = 'sidebar-' . $contentLayout->sidebar->position;
    if($contentLayout->sidebar->hideForSmall){
        $sidebarClasses[] = 'hide-for-small';
    }
    switch($contentLayout->sidebar->size){
        case '1/6': $sidebarClasses[] = 'large-2'; break;
        case '1/4': $sidebarClasses[] = 'large-3'; break;
        case '1/3': $sidebarClasses[] = 'large-4'; break;
    }
    if($contentLayout->sidebar->withbg){
        $sidebarClasses[] = 'sidebar-has-background';
    }

    $contentClasses = array();
    $contentClasses[] = 'columns';
    $haveSidebar = !!$contentLayout->sidebar->id;
    if(!$haveSidebar){
        $contentClasses[] = 'large-12';
    }
    if($contentLayout->sidebar->hideForSmall){
        $contentClasses[] = 'small-12';
    }
    if(!$haveSidebar || 'right' == $contentLayout->sidebar->position){
        $contentClasses[] = 'content-left';
    }
    if($haveSidebar && 'left' == $contentLayout->sidebar->position){
        $contentClasses[] = 'content-right';
    }
    switch($contentLayout->sidebar->size){
        case '1/6': $contentClasses[] = 'large-10'; break;
        case '1/4': $contentClasses[] = 'large-9'; break;
        case '1/3': $contentClasses[] = 'large-8'; break;
    }
}

$footerClasses = array();
if('left' != $monstrotheme->settings->header->type &&
    $contentLayout->boxedFooter &&
    'full' != $contentLayout->width ){
    $footerClasses[] = 'boxed';
}
?>

<div id="wrapper" class="<?php echo implode(' ', $wrapperClasses);?>">
    <div id="mp-pusher" class="mp-pusher has-background">
        <header id="header-container" class="<?php echo implode(' ', $headerClasses);?>">
            <?php if(in_array($monstrotheme->settings->header->type, array('default', 'left'))){?>
                <?php get_template_part('noscript/header/default');?>
            <?php } else if($monstrotheme->settings->header->menu->enableHamburgerMenu){?>
                <?php get_template_part('noscript/header/hamburger');?>
            <?php } else { ?>
                <?php get_template_part('noscript/header/centered/' . $monstrotheme->settings->header->menu->position);?>
            <?php } ?>
        </header>

        <section id="main" class="<?php echo implode(' ', $mainSectionClasses);?>">
            <div class="main-container <?php echo implode(' ', $mainContainerClasses);?>">
                <div class="row">
                    <?php if( !empty($contentLayout->sidebar) && $contentLayout->sidebar->id && 'left' == $contentLayout->sidebar->position){?>
                        <div class="<?php echo implode(' ', $sidebarClasses);?>">
                            <?php dynamic_sidebar($contentLayout->sidebar->id);?>
                        </div>
                    <?php } ?>

                    <div class="<?php  if(!empty($contentClasses)){ echo implode(' ', $contentClasses);}?>">
                        <?php if(is_archive()){?>
                            <div class="archive-title">
                                <h2 class="post-title">
                                    <?php echo $monstrotheme->WPData['archiveTitle'];?>
                                </h2>
                            </div>
                        <?php } ?>
                        <?php if(is_front_page() && !is_home()){?>
                            <?php get_template_part('noscript/views/front-page');?>
                        <?php } else if(is_page()){?>
                            <?php get_template_part('noscript/views/page');?>
                        <?php }else if(is_single()){?>
                            <?php get_template_part('noscript/views/single/' . $currentContentLayout);?>
                        <?php } else { ?>
                            <?php get_template_part('noscript/views/list-view');?>
                        <?php } ?>
                    </div>

                    <?php if(!empty( $contentLayout->sidebar) && $contentLayout->sidebar->id && 'right' == $contentLayout->sidebar->position){?>
                        <div class="<?php echo implode(' ', $sidebarClasses);?>">
                            <?php dynamic_sidebar($contentLayout->sidebar->id);?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
        <footer id="footer-container" class="<?php echo implode(' ', $footerClasses);?>"></footer>
    </div>
</div>