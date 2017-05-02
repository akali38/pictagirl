<?php
$likesAndViews = MonstroLikesAndViews::getInstance();
$monstrotheme = MOnstroTheme::getInstance();
?>
<div class="monstro-vote monstro-toggleable-element">
    <div class="monstro-vote-sticky">
        <div class="monstro-float-vote">
            <ul class="<?php echo $monstrotheme->settings->votes->icon;?>">
                <li>
                    <a class="good<?php echo $likesAndViews->hasUpvoted() ? ' voted' : '';?>" href="javascript:void(0);">
                        <span class="icon"></span>
                    </a>
                </li>
                <li class="count"><span><?php echo $likesAndViews->getVotes();?></span></li>
                <li>
                    <a class="meh<?php echo $likesAndViews->hasDownvoted() ? ' voted' : '';?>" href="javascript:void(0);">
                        <span class="icon"></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>