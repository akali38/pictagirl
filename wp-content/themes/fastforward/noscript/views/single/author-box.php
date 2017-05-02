<?php
$monstrotheme = MonstroTheme::getInstance();
$currentContentLayout = $monstrotheme->WPData['currentContentLayout'];
$author = $monstrotheme->WPData['posts'][0]['author'];
$authorBoxColumns = $monstrotheme->settings->contentLayouts->{$currentContentLayout}->showPostSource ? 6 : 12;
?>
<div class=" monstro-toggleable-element columns large-<?php echo $authorBoxColumns;?>" data-ng-controller="AuthorBoxCtrl" data-monstro-class="getAuthorBoxClasses()"
    data-ng-if="showAuthorBox()">
    <div class="post-author-box">
        <a href="<?php echo $author['url'];?>">
            <?php echo $author['avatar'];?>
        </a>
        <h5 class="author-title">
            <a href="<?php echo strlen($author['website']) ? $author['website'] : $author['url'];?>"
                title="<?php echo __('Visit', 'monstrotheme') . ' ' . $author['name'] . ' ' . __("'s site", 'monstrotheme');?>"
                rel="author external">
                <?php echo $author['name'];?>
            </a>
        </h5>
        <?php if(strlen($author['bio'])){?>
            <div class="author-box-info">
                <?php echo $author['bio'];?>
            </div>
        <?php } ?>
    </div>
</div>