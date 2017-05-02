<?php $monstrotheme = MonstroTheme::getInstance();?>
<div class="logo">
    <a href="<?php echo home_url();?>">
        <div class="logo-image">
            <img src="<?php echo $monstrotheme->settings->header->logo->url;?>" alt="<?php _e('Logo', 'monstrotheme');?>>">
        </div>
    </a>
</div>