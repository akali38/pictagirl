<?php
get_template_part('monstrotheme/partials');
$monstrotheme = MonstroTheme::getInstance();
$currentContentLayout = $monstrotheme->WPData['currentContentLayout'];
$contentLayout = $monstrotheme->settings->contentLayouts->{$currentContentLayout};
$headerClasses = array();
if('no' == $contentLayout->metaPosition){
    $headerClasses = 'no-meta';
} else {
    $headerClasses[] = 'meta-' . $contentLayout->metaPosition;
}
$meta = $monstrotheme->WPData['posts'][0]['meta'];
?>
<article <?php post_class();?> data-ng-controller="SinglePostCtrl">
    <header class="entry-header <?php echo implode(' ', $headerClasses);?>" data-monstro-class="getHeaderClasses()">
        <?php if('above' == $contentLayout->metaPosition){?>
        <?php get_template_part('templates/views/single/meta/above');?>
        <?php } else { ?>
        <!-- directive: monstro-placeholder getMetaAbove() -->
        <?php } ?>

        <h2 class="post-title"><?php the_title();?></h2>

        <?php if('below' == $contentLayout->metaPosition){?>
        <?php get_template_part('templates/views/single/meta/below');?>
        <?php } else { ?>
        <!-- directive: monstro-placeholder getMetaBelow() -->
        <?php } ?>

        <?php if($contentLayout->sharingAndStats->showAbove){?>
        <?php get_template_part('templates/views/single/sharing-and-stats/above');?>
        <?php } else {?>
        <!-- directive: monstro-placeholder getSharingAndStatsAbove() -->
        <?php } ?>

        <?php if(has_post_thumbnail() && $contentLayout->showFeaturedImage){?>
        <div class="featimg monstro-toggleable-element" data-ng-if="showFeaturedImage()">
            <a class="image" href="<?php the_permalink();?>" title="<?php _e('Permalink to', 'monstrotheme');?> <?php the_title();?>" rel="bookmark">
                <?php the_post_thumbnail();?>
            </a>
        </div>
        <?php } else { ?>
            <!-- directive: monstro-placeholder getFeaturedImage() -->
        <?php } ?>
    </header>

    <section class="entry-content">
        <div class="entry-content">
            <?php the_content();?>
        </div>
    </section>

    <?php $footerClasses = array();?>
    <?php if(!(comments_open() || $contentLayout->enableFacebookComments)){?>
        <?php $footerClasses[] = 'comments-disabled';?>
    <?php } ?>
    <footer class="entry-footer <?php echo implode(' ', $footerClasses);?>" data-monstro-class="getFooterClasses()">
        <?php if($contentLayout->sharingAndStats->showBelow){?>
        <?php get_template_part('templates/views/single/sharing-and-stats/below');?>
        <?php } else {?>
        <!-- directive: monstro-placeholder getSharingAndStatsBelow() -->
        <?php } ?>

        <div class="row">
            <?php if($contentLayout->showAuthorBox){?>
            <?php get_template_part('templates/views/single/author-box');?>
            <?php } else {?>
            <!-- directive: monstro-placeholder getAuthorBox() -->
            <?php } ?>

            <?php if($contentLayout->showPostSource &&
                    isset($meta['postSource']) && strlen($meta['postSource'])){?>
            <?php get_template_part('templates/views/single/post-source');?>
            <?php } else {?>
            <!-- directive: monstro-placeholder getPostSource() -->
            <?php } ?>

            <?php if(has_tag()){?>
            <div class="columns large-12">
                <ul class="entry-tags ">
                    <li class="label">
                        <span><?php _e('Related tags:', 'monstrotheme');?></span>
                    </li>
                    <?php the_tags('<li>', '</li><li>', '<li>');?>
                </ul>
            </div>
            <?php } ?>
        </div>
        <?php if(comments_open() || $contentLayout->enableFacebookComments){?>
        <div class="monstro-comments" ng-if="showComments()">
            <ul class="comments-tabs">
                <?php if(comments_open() || pings_open()){?>
                <li id="tab-wp" class="active" ng-if="haveWpComments()">
                    <a href="javascript:void(0)" class="tab active">
                        <?php _e('WordPress comments', 'monstrotheme');?>
                        (<?php comments_number();?>)
                    </a>
                </li>
                <?php } ?>

                <?php if($contentLayout->enableFacebookComments){?>
                <li id="tab-fb" data-ng-if="showFacebookComments()">
                    <a href="javascript:void(0)" class="tab">
                        <?php _e('Facebook comments', 'monstrotheme');?>
                        (<span class="fb_comments_count">3</span>)
                    </a>
                </li>
                <?php } else { ?>
                <!-- directive: monstro-placeholder getFacebookCommentsLabel() -->
                <?php } ?>
            </ul>
            <div class="comments-wrapper">
                <?php if(comments_open() || pings_open()){?>
                <?php comments_template();?>
                <?php } ?>

                <?php if($contentLayout->enableFacebookComments){?>
                <div id="content-fb" class="comments-content" data-ng-if="showFacebookComments()">
                    <div id="comments">
                        <fb:comments href="<?php the_permalink();?>" num_posts="5" width="790" height="120" reverse="true" fb-xfbml-state="rendered" class="fb_iframe_widget" style="width: 100%;">
                                            <span style="height: 776px; width: 100%;">
                                                <iframe id="fdccdbc74" name="f34b645dfc" scrolling="no" title="Facebook Social Plugin" class="fb_ltr" src="https://www.facebook.com/plugins/comments.php?api_key=&amp;channel_url=http%3A%2F%2Fstatic.ak.facebook.com%2Fconnect%2Fxd_arbiter.php%3Fversion%3D28%23cb%3Df75a85df%26domain%3Drewind.monstrothemes.com%26origin%3Dhttp%253A%252F%252Frewind.monstrothemes.com%252Ff2b0c72468%26relation%3Dparent.parent&amp;href=http%3A%2F%2Frewind.monstrothemes.com%2F2013%2F03%2F29%2Fmaecenas-dictum-tortor-eu-est%2F&amp;locale=en_US&amp;numposts=5&amp;sdk=joey&amp;width=790" style="border: none; overflow: hidden; height: 776px; width: 100%;"></iframe>
                                            </span>
                        </fb:comments>
                    </div>
                </div>
                <?php } else { ?>
                    <!-- directive: monstro-placeholder getFacebookComments() -->
                <?php } ?>
            </div>
        </div>
        <?php } else {?>
        <!-- directive: monstro-placeholder getComments() -->
        <?php } ?>
        <?php get_template_part('templates/views/single/related/related');?>
    </footer>
</article>