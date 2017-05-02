<?php
$monstrotheme = MonstroTheme::getInstance();
$currentContentLayout = $monstrotheme->WPData['currentContentLayout'];
$postSource = $monstrotheme->WPData['posts'][0]['meta']['postSource'];
$postSourceColumns = $monstrotheme->settings->contentLayouts->{$currentContentLayout}->showAuthorBox ? 6 : 12;
?>
<div class="monstro-toggleable-element large-<?php echo $postSourceColumns;?> columns" data-ng-controller="PostSourceCtrl"
     data-monstro-class="getPostSourceClasses()" data-ng-if="showPostSource()">
    <div class="post-source">
        <h5 class="source-title">
            <a href="<?php echo $postSource;?>" data-ng-href="{{post.meta.postSource}}" data-ng-bind="post.meta.postSource">
                <?php echo $postSource;?>
            </a>
        </h5>
    </div>
</div>