<div class="entry-meta monstro-toggleable-element" data-ng-if="showMetaAbove()">
    <ul>
        <?php if(has_category()){?>
        <li>
            <?php the_category();?>
        </li>
        <?php } ?>
        <li>
            <?php _e('By', 'monstrotheme');?> <?php the_author_posts_link();?>
        </li>
        <li>
            <?php _e('on', 'monstrotheme');?>
            <a href="<?php echo get_day_link(get_the_time('Y'), get_the_time('m'), get_the_time('d'));?>">
                <?php the_date();?>
            </a>
        </li>
        <?php if(comments_open()){?>
            <li>
                <a class="comment" href="<?php comments_link();?>">
                    <span><?php comments_number();?></span>
                </a>
            </li>
        <?php } ?>
        <?php if(has_post_format()){?>
            <li>
                <ul class="post-format">
                    <li>
                        <a href="<?php echo get_post_format_link(get_post_format());?>" class="format-<?php echo get_post_format();?>"><?php echo get_post_format_string(get_post_format());?></a>
                    </li>
                </ul>
            </li>
        <?php } ?>
    </ul>
</div>