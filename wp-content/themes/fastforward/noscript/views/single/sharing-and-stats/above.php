<?php $likesAndViews = MonstroLikesAndViews::getInstance(); ?>
<?php $monstrotheme = MonstroTheme::getInstance();?>
<div class="row margin-bottom-30 monstro-toggleable-element" data-ng-if="showSharingAndStatsAbove()" data-ng-controller="SharingAndStatsCtrl">
     <div class="large-8 small-8 columns">
        <div class="monstro-stats">
            <ul>
                <li>
                    <strong data-ng-bind="post.views"><?php echo $likesAndViews->getViews();?></strong>
                    <span data-ng-pluralize data-count="post.views" data-when="pluralizeViews"><?php echo _n('view', 'views', $likesAndViews->getViews());?></span>
                </li>
            </ul>
        </div>

        <div class="socialicons share">
            <ul class="monstro-social">
                <li>
                    <a data-ng-href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink());?>" class="fb" target="_blank">
                        <i class="icon-facebook"></i>
                    </a>
                </li>
                <li>
                    <a data-ng-href="https://plus.google.com/share?url=<?php echo urlencode(get_permalink());?>" class="gplus" target="_blank">
                        <i class="icon-gplus"></i>
                    </a>
                </li>
                <li>
                    <a data-ng-href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink());?>" class="twitter" target="_blank">
                        <i class="icon-twitter"></i>
                    </a>
                </li>
                <li>
                    <a data-ng-href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink());?>" class="pinterest" target="_blank">
                        <i class="icon-pinterest"></i>
                    </a>
                </li>
                <li><a data-ng-href="mailto:?to=&body=<?php echo urlencode(get_permalink());?>" class="email"><i class="icon-email"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="large-4 small-4 columns">
        <?php if($monstrotheme->settings->votes->enable){?>
        <?php get_template_part('templates/views/single/votes');?>
        <?php } else { ?>
        <!-- directive: monstro-placeholder getVotes() -->
        <?php } ?>
    </div>
</div>