<?php $monstrotheme = MonstroTheme::getInstance();?>
<!doctype html>
<html data-ng-app="monstro.theme.frontend" data-ng-controller="FrontendCtrl" <?php language_attributes(); ?>>
<!--[if IE 8]><html class="no-js lt-ie9"<?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"<?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri();?>/vendors/html5shiv/html5shiv.min.js"></script>
    <![endif]-->

    <!--[if IE 9]>
    <style>
        .header-left section#main {
            margin-left: 0px!important;
        }
    </style>

    <![endif]-->
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta name="robots" content="index, follow" />
    <meta name="description" content="<?php echo get_bloginfo('description'); ?>" />
    <?php if( is_single() || is_page() ){ ?>
        <meta property="og:title" content="<?php the_title() ?>" />
        <meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>" />
        <meta property="og:url" content="<?php the_permalink() ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:description" content="<?php echo get_bloginfo('description'); ?>"/>
        <?php if(isset($monstrotheme->settings->social->fbAppId) && $monstrotheme->settings->social->fbAppId) { ?>
            <meta property='fb:app_id' content='<?php echo $monstrotheme->settings->social->fbAppId; ?>'>
        <?php
        }
        global $post;
        $src = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ) , 'thumbnail' );
        if(strlen($src[0])){
            echo '<meta property="og:image" content="'.$src[0].'"/>';
            echo '<link rel="image_src" href="'.$src[0].'" />';
        }else{
            echo '<meta property="og:image" content="'.get_template_directory_uri().'/screenshot.png"/>';
        }
    }else{ ?>
        <meta property="og:title" content="<?php echo get_bloginfo('name'); ?>"/>
        <meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>"/>
        <meta property="og:url" content="<?php echo home_url() ?>/"/>
        <meta property="og:type" content="blog"/>
        <meta property="og:locale" content="en_US"/>
        <meta property="og:description" content="<?php echo get_bloginfo('description'); ?>"/>
        <meta property="og:image" content="<?php echo get_template_directory_uri();?>/screenshot.png"/>
    <?php
    }
    ?>
    <title data-ng-bind-html="WPData.title">
        <?php bloginfo('name'); ?> &raquo; <?php bloginfo('description'); ?><?php if ( is_single() ) { ?><?php } ?><?php wp_title(); ?>
    </title>
    <base href="<?php echo home_url();?>/"/>
    <?php wp_head();?>
</head>
<body <?php body_class();?> data-customizer-transition="push"
                            data-modal-transition="{{settings.modal.animation}}" data-ng-class="WPData.bodyClasses"
                            data-monstro-class="{
                                    'header-left': 'left' == settings.header.type,
                                    'header-hamburger': ('centered' == settings.header.type) && (1 == settings.header.menu.enableHamburgerMenu),
                                    'header-centered': ('centered' == settings.header.type) && (0 == settings.header.menu.enableHamburgerMenu),
                                    'header-sticky': stickyHeaderConfig.isSticky(),
                                    'full-view': 'full' == contentLayout.width,
                                    'large-view': 'large' == contentLayout.width,
                                    'narrow-view': 'narrow' == contentLayout.width
                                }" data-ng-scroll-event="scrollEvent($event, isEndEvent)">
<style data-ng-bind-template="
            header#header-container,
            .single.header-left header#header-container.sticked {
                background-color: {{settings.styling.headerBgColor}};
                color: {{settings.styling.headerTextColor}};
            }
            nav.main-menu ul > li {
                color: {{settings.styling.headerMenuColor}};
            }
            nav.main-menu ul > li ul li {
                color: {{settings.styling.headerSubmenuColor}};
            }
            .drop-down nav.main-menu ul > li.menu-item-has-children ul:before,
            .clickable .sb-icon-search {
                background-color: {{settings.styling.headerBgColor}};
            }
            .single header#header-container.sticked {
                background-color: {{settings.styling.headerStickyBgColor}};
                color: {{settings.styling.headerStickyTextColor}};
            }
            section#main,
            section#main.boxed {
                background-color: {{settings.styling.contentBgColor}};
                color: {{settings.styling.contentTextColor}};
            }
            a {
                color:{{settings.styling.contentLinksColor}};
            }
            a:hover {
                color:{{settings.styling.contentLinksHoverColor}};
            }
            .monstro-grid-view article .hover-toggle,
            .monstro-thumb-view article .hover-toggle,
            .widget article .featimg .hover-toggle {
                background-color: {{settings.styling.contentBgHoverColor}};
                color: {{settings.styling.contentTextHoverColor}};
            }
            .withbg .sidebar-right:before,
            .withbg .sidebar-left:before {
                background: {{contentLayout.sidebar.bgColor}};
            }
            .withbg .sidebar-right,
            .withbg .sidebar-left {
                color: {{contentLayout.sidebar.textColor}};
            }
            ul.entry-tags,
            ul.entry-tags:before{
                background-color: {{settings.styling.contentTagsBgColor}};
                color: {{settings.styling.contentTagsTextColor}};
            }
            .single-video article header.entry-header,
            .single-video article section.entry-content{
                color: {{settings.styling.videoTextColor}};
            }
            .single-video article header.entry-header:before,
            .single-video article section.entry-content:before{
                background-color: {{settings.styling.videoBgColor}};
            }
            footer#footer-container{
                background-color: {{settings.styling.footerBgColor}};
                color: {{settings.styling.footerTextColor}};
            }
            footer#footer-container a {
                color: {{settings.styling.footerLinksColor}};
            }
            footer#footer-container a:hover {
                color: {{settings.styling.footerLinksHoverColor}};
            }
            ">
</style>
<div id="fb-root"></div>
<?php if(isset($monstrotheme->settings->social->fbAppId) && $monstrotheme->settings->social->fbAppId){?>
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $monstrotheme->settings->social->fbAppId;?>";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
<?php } ?>
<div id="wrapper" data-ml-transition="{{settings.mlMenu.animation}}" data-ng-class="{
                                    'align-left': 'left' != settings.header.type && 'left' == contentLayout.position,
                                    'align-center': 'left' != settings.header.type && 'center' == contentLayout.position,
                                    'align-right': 'left' != settings.header.type && 'right' == contentLayout.position,
                                    'ml-menu-open':mlMenuOpen
                                }" data-ng-controller="MlMenuCtrl" class="monstro-cloak">

    <nav id="mp-menu" class="mp-menu mp-cover" data-ng-include="'mlMenu' | partial" onload="initMlMenu()"></nav>
    <div id="mp-pusher" class="mp-pusher has-background">
        <header id="header-container" data-ng-class="{
                    'vertical-align': (('default' == settings.header.type) && (1 == settings.header.enableVerticalAlign))
                        || (('centered' == settings.header.type) && ('logoInBetween' == settings.header.menu.position))
                        || (('centered' == settings.header.type) && (1 == settings.header.menu.enableHamburgerMenu)),
                    'menu-below-logo': ('centered' == settings.header.type) && (0 == settings.header.menu.enableHamburgerMenu) &&
                        ('belowLogo' == settings.header.menu.position),
                    'menu-centered-logo': ('centered' == settings.header.type) && (0 == settings.header.menu.enableHamburgerMenu) &&
                        ('logoInBetween' == settings.header.menu.position),
                    'menu-above-logo': ('centered' == settings.header.type) && (0 == settings.header.menu.enableHamburgerMenu) &&
                        ('aboveLogo' == settings.header.menu.position),
                    boxed: ('left' != settings.header.type) && ('full' != contentLayout.width) && (1 == contentLayout.boxedHeader),
                    sticked: stickyHeaderConfig.isSticky() && (stickyHeaderConfig.isSticked || ('left' == settings.header.type))
                    }" data-ng-show="menusLoaded.counter || (('centered' == settings.header.type) && (1 == settings.header.menu.enableHamburgerMenu))"
                data-monstro-sticky="stickyHeaderConfig">
            <div class="row" data-ng-include="getHeaderTemplate()" ng-hide="showSingleSticky()"></div>
            <div class="row single-sticked" data-ng-include="tdu + '/templates/header/single-sticked.html'" ng-show="showSingleSticky()"></div>
        </header>

        <section id="main" data-ng-class="{
                        boxed: (1 == contentLayout.boxedContent) && ('full' != contentLayout.width ) && ('left' != settings.header.type)
                    }" data-page-transition="{{settings.contentLayouts.pageAnimation}}">
            <div class="main-container" data-ng-controller="LayoutCtrl" data-ng-class="getMainContainerClasses()">
                <div class="row">