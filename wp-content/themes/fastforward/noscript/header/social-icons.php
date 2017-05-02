<?php
$monstrotheme = MonstroTheme::getInstance();
function getSocialIconUrl($theClass, $url){
    switch($theClass){
        case 'facebook': return 'http://facebook.com/' . $url;
        case 'twitter': return 'http://twitter.com/'. $url;
        default: return url;
    }
}
?>
<div class="socialicons">
    <ul class="monstro-social">
        <?php foreach($monstrotheme->settings->social->icons as $class=>$url){?>
            <li>
                <a href="<?php echo getSocialIconUrl($class, $url);?>" class="<?php echo $class;?>">
                    <i class="icon-<?php echo $class;?>"></i>
                </a>
            </li>
        <?php } ?>
        <?php if($monstrotheme->settings->social->showRss){?>
            <li>
                <a href="<?php bloginfo('rss2_url'); ?>" class="rss">
                    <i class="icon-rss"></i>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>
