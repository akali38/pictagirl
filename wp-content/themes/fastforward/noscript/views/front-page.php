<?php while(have_posts()){?>
    <?php the_post();?>
    <article <?php post_class();?>>
        <header class="entry-header">
            <?php if(has_post_thumbnail()){?>
                <div class="featimg">
                    <a class="image" href="<?php the_permalink();?>" title="<?php _e('Permalink to', 'monstrotheme');?> <?php the_title();?>" rel="bookmark">
                        <?php the_post_thumbnail();?>
                    </a>
                </div>
            <?php } ?>
        </header>
        <section class="entry-content">
            <div class="entry-content">
                <?php the_content();?>
            </div>
        </section>
    </article>
<?php } ?>