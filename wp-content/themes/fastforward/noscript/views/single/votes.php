<?php
$likesAndViews = MonstroLikesAndViews::getInstance();
$monstrotheme = MOnstroTheme::getInstance();
?>
<div class="monstro-vote monstro-toggleable-element" data-ng-controller="VotesCtrl" data-ng-if="showVotes()">
    <div class="monstro-vote-sticky">
        <div class="monstro-float-vote">
            <ul class="<?php echo $monstrotheme->settings->votes->icon;?>" data-monstro-class="getUlClasses()">
                <li class="count"><span data-ng-bind="post.votes"><?php echo $likesAndViews->getVotes();?></span></li>
                <li>
                    <a class="good<?php echo $likesAndViews->hasUpvoted() ? ' voted' : '';?>" href="javascript:void(0);"
                        data-ng-click="like(post)" data-monstro-class="getUpvoteClasses()">
                        <span class="icon"></span>
                    </a>
                </li>
                <li>
                    <a class="meh<?php echo $likesAndViews->hasDownvoted() ? ' voted' : '';?>" href="javascript:void(0);"
                        data-ng-click="dislike(post)" data-monstro-class="getDownvoteClasses()">
                        <span class="icon"></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>