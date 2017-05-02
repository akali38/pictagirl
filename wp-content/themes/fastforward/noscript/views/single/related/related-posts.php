<?php get_template_part('monstrotheme/monstro-likes-and-views');?>
<?php $likesAndViews = new MonstroLikesAndViews();?>
<?php if(have_posts()){?>
    <?php while(have_posts()){?>
        <?php the_post();
            if(get_post_type( get_the_ID() ) == "video"){
                $customClass = "video";
            }else{
                $customClass = null;
            }
        ?>
        <article class="status-publish format-image columns large-3 <?php echo $customClass ?>">
            <?php $postClasses = array();?>
            <?php if(!has_post_thumbnail()){?>
                <?php $postClasses[] = 'no-feat-img';?>
            <?php } ?>
            <header class="entry-header meta-above <?php echo implode(' ', $postClasses);?>">
                <div class="featimg" data-hover-effeckt-type="{{settings.contentLayouts.frontPage.hoverAnimation}}">
                    <a class="image" href="<?php the_permalink();?>"
                       title="<?php echo __('Permalink to', 'monstrotheme') . get_the_title();?>" rel="bookmark">
                        <?php
                        if(has_post_thumbnail()){
                            require_once get_template_directory() . '/vendors/aq_resizer.php';
                            $monstrotheme = MonstroTheme::getInstance();
                            $imageSize = $monstrotheme->settings->imageSizes->grid;
                            $thumbID = get_post_thumbnail_id();
                            $src = wp_get_attachment_url($thumbID);
                        ?>
                            <img src="<?php echo aq_resize( $src, $imageSize->width, $imageSize->crop ? $imageSize->height : null, true, true, true);?>"/>
                        <?php } ?>
                    </a>
                    <div class="hover-toggle" ng-if="1 == settings.votes.enable">
                        <div monstro-votes icon="settings.votes.icon" votes="<?php echo $likesAndViews->getVotes();?>"
                             vote="<?php echo $likesAndViews->getVote();?>" post-id="<?php the_ID();?>"></div>
                    </div>
                </div>
            </header>

            <section class="entry-content">
                <div class="entry-meta">
                    <ul>
                        <li>
                            <?php _e('on', 'monstrotheme');?>
                            <?php echo get_the_date();?>
                        </li>
                    </ul>
                </div>
                <h2 class="entry-title">
                    <a href="<?php the_permalink();?>"
                       title="<?php echo __('Permalink to', 'monstrotheme') . get_the_title();?>" rel="bookmark">
                        <?php the_title();?>
                    </a>
                </h2>
            </section>
        </article>
    <?php } ?>
<?php } else { ?>
    <div class="large-12 columns"><p><?php _e('No related posts found', 'monstrotheme');?></p></div>
<?php } ?>