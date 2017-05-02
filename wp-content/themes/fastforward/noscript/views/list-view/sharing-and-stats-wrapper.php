<?php
$monstrotheme = MonstroTheme::getInstance();
$currentContentLayout = $monstrotheme->WPData['currentContentLayout'];
$contentLayout = $monstrotheme->settings->contentLayouts->{$currentContentLayout};
$likesAndViews = MonstroLikesAndViews::getInstance();
?>

<div class="large-8 medium-8 columns monstro-toggleable-element">
    <?php if($contentLayout->showViews){?>
    <div class="monstro-stats monstro-toggleable-element">
        <ul>
            <li>
                <strong><?php echo $likesAndViews->getViews();?></strong>
                <span><?php echo _n('view', 'views', $likesAndViews->getViews());?></span>
            </li>
        </ul>
    </div>
    <?php } ?>

    <?php if($contentLayout->showSocial){?>
    <div class="socialicons share monstro-toggleable-element">
        <ul class="monstro-social">
            <li>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink());?>" class="fb" target="_blank">
                    <i class="icon-facebook"></i>
                </a>
            </li>
            <li>
                <a href="https://plus.google.com/share?url=<?php echo urlencode(get_permalink());?>" class="gplus" target="_blank">
                    <i class="icon-gplus"></i>
                </a>
            </li>
            <li>
                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink());?>" class="twitter" target="_blank">
                    <i class="icon-twitter"></i>
                </a>
            </li>
            <li>
                <a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink());?>" class="pinterest" target="_blank">
                    <i class="icon-pinterest"></i>
                </a>
            </li>
            <li><a href="mailto:?to=&amp;body=<?php echo urlencode(get_permalink());?>" class="email"><i class="icon-email"></i></a></li>
        </ul>
    </div>
    <?php } ?>
</div>