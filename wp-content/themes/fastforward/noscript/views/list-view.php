<?php
$monstrotheme = MonstroTheme::getInstance();
$currentContentLayout = $monstrotheme->WPData['currentContentLayout'];
$contentLayout = $monstrotheme->settings->contentLayouts->{$currentContentLayout};
$headerClasses = array();
if('no' == $contentLayout->metaPosition){
    $headerClasses = 'no-meta';
} else {
    $headerClasses[] = 'meta-' . $contentLayout->metaPosition;
}
?>
<div class="monstro-list-view row">
    <div class="large-12 columns">
        <?php while(have_posts()){ the_post();?>
        <article id="post-<?php the_ID();?>" <?php post_class();?> data-monstro-post-from-id="<?php the_ID();?>">
            <header class="entry-header <?php echo implode(' ', $headerClasses);?>">
                <?php if('above' == $contentLayout->metaPosition){?>
                    <?php get_template_part('noscript/views/single/meta/above');?>
                <?php } else { ?>

                <?php } ?>

                <h2 class="entry-title">
                    <a href="<?php the_permalink();?>" title="<?php the_title();?>" rel="bookmark"><?php the_title();?></a>
                </h2>

                <?php if('below' == $contentLayout->metaPosition){?>
                    <?php get_template_part('noscript/views/single/meta/below');?>
                <?php } else { ?>

                <?php } ?>
                <div class="featimg">
                    <?php if(has_post_thumbnail()){?>
                    <a class="image" href="<?php the_permalink();?>" title="<?php _e('Permalink to', 'monstrotheme');?> <?php the_title();?>" rel="bookmark">
                        <?php the_post_thumbnail();?>
                    </a>
                    <?php } ?>
                    <?php if($monstrotheme->settings->votes->enable){?>
                        <?php get_template_part('noscript/views/votes');?>
                    <?php } else { ?>

                    <?php } ?>
                </div>
            </header>
            <section class="entry-content">
                <div class="entry-excerpt">
                    <?php the_excerpt();?>
                </div>
            </section>
            <?php if($contentLayout->showFullStory || $contentLayout->showSocial || $contentLayout->showViews){?>
            <footer class="entry-footer monstro-toggleable-element">
                <div class="row">
                    <?php if($contentLayout->showSocial || $contentLayout->showViews){?>
                    <?php get_template_part('noscript/views/list-view/sharing-and-stats-wrapper');?>
                    <?php } ?>

                    <?php if($contentLayout->showFullStory){?>
                    <div class="large-4 medium-4 columns hide-for-small monstro-toggleable-element">
                        <a class="read-more" href="<?php the_permalink();?>" title="<?php the_title();?>" rel="bookmark">
                            <?php _e('Full story', 'monstrotheme');?>
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </footer>
            <?php } else { ?>

            <?php } ?>
        </article>
        <?php } ?>
        <?php
            if('infinite' != $contentLayout->pagination){
                get_template_part('noscript/views/pagination');
            }
        ?>
    </div>
</div>