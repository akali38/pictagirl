<?php
$monstrotheme = MonstroTheme::getInstance();
$currentContentLayout = $monstrotheme->WPData['currentContentLayout'];
$contentLayout = $monstrotheme->settings->contentLayouts->{$currentContentLayout};
if($contentLayout->related->show && $monstrotheme->doRelatedQuery($contentLayout->related->taxonomy)){?>
<div class="related-box monstro-toggleable-element" data-ng-if="showRelatedPosts()">
    <h3 id="related-title"><?php _e('Related posts', 'monstrotheme');?></h3>
    <div class="monstro-grid-view row" data-monstro-include="getRelatedPosts()">
        <?php get_template_part('templates/views/single/related/related-posts');?>
    </div>
</div>
<?php wp_reset_query();?>
<?php } ?>
