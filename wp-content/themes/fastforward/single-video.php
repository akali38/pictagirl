<?php
// AJAX template for video posts is templates/views/single/video.html
// SEO templates are in noscript/views
// Keep in mind that we'll be replacing .html and .php templates with unified Mustache templates ASAP
?>
<?php get_header();?>
<div data-ng-if="contentLayout.sidebar.id && ('video' != WPData.currentContentLayout) && ('left' == contentLayout.sidebar.position)"
    data-ng-include="('sidebar&amp;id=' + contentLayout.sidebar.id) | partial"
    class="columns sidebar-left" data-ng-class="{
        'hide-for-small': 1 == contentLayout.sidebar.hideForSmall,
        'large-2': '1/6' == contentLayout.sidebar.size,
        'large-3': '1/4' == contentLayout.sidebar.size,
        'large-4': '1/3' == contentLayout.sidebar.size,
        'sidebar-has-background': contentLayout.sidebar.withbg
    }"></div>

<div data-ng-controller="ContentCtrl" data-ng-include="getContentView()" class="columns content page-transition-target" data-ng-class="{
    'content-left': contentLayout.sidebar.id && ('right' == contentLayout.sidebar.position),
    'content-right': contentLayout.sidebar.id && ('left' == contentLayout.sidebar.position),
    'no-sidebars': !contentLayout.sidebar.id,
    'large-12': !contentLayout.sidebar.id || ('video' == WPData.currentContentLayout),
    'large-10': contentLayout.sidebar.id && ('video' != WPData.currentContentLayout) && ('1/6' == contentLayout.sidebar.size),
    'large-9': contentLayout.sidebar.id && ('video' != WPData.currentContentLayout) && ('1/4' == contentLayout.sidebar.size),
    'large-8': contentLayout.sidebar.id && ('video' != WPData.currentContentLayout) && ('1/3' == contentLayout.sidebar.size),
    'small-12': contentLayout.sidebar.hideForSmall
}"></div>

<div data-ng-if="contentLayout.sidebar.id && ('video' != WPData.currentContentLayout) && ('right' == contentLayout.sidebar.position)"
    data-ng-include="('sidebar&amp;id=' + contentLayout.sidebar.id) | partial"
    class="columns sidebar-right" data-ng-class="{
        'hide-for-small': 1 == contentLayout.sidebar.hideForSmall,
        'large-2': '1/6' == contentLayout.sidebar.size,
        'large-3': '1/4' == contentLayout.sidebar.size,
        'large-4': '1/3' == contentLayout.sidebar.size,
        'sidebar-has-background': contentLayout.sidebar.withbg
    }"></div>
<?php get_footer();?>