<?php 
get_header();

//VAR SETUP
$crumbs = get_theme_mod('themolitor_customizer_bread_onoff');

if ($crumbs && function_exists('dimox_breadcrumbs')) dimox_breadcrumbs();
?>

	<div class="listing">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
		<div <?php post_class(); ?>>
		
		
		<h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
		
		<!--<div class="metaStuff">
			<div class="categoryStuff metaItem"><?php the_category(', '); ?></div>
			<div class="dateStuff metaItem"><?php the_time('F j, Y'); ?></div>
			<div class="authorStuff metaItem"><?php the_author(); ?></div>
			<div class="commentStuff metaItem"><?php comments_popup_link(__('0 Comments','themolitor'), __('1 Comment','themolitor'), __('% Comments','themolitor')); ?></div>
			<div class="tagStuff metaItem"><?php the_tags(''); ?></div>
		</div>-->
		
		
		<?php if (has_post_thumbnail()) { ?>
		<div class="column">
		<?php get_template_part("thumbnail"); ?>
		</div>
		
		<div class="column">
		<?php } else { ?>
		<div class="noImgColumn">			
		<?php } ?>
		
		<?php the_excerpt(); ?>
		<!--<a class="readMore" href="<?php the_permalink() ?>"><?php _e('Read More','themolitor');?> &rarr;</a>-->
		</div>
		
        <div class="clear"></div>
		</div><!--end post-->

		<?php endwhile; ?>
		
		</div><!--end listing-->

		<?php 
		get_template_part("navigation");
		else: 
		?>
		
		<h2 class="posttitle"><?php _e('Nothing found - Try a different search?','themolitor');?></h2>
		<?php get_template_part("searchform"); ?>
		
		</div><!--end listing-->
		
		<?php 
		endif;
		get_footer(); 
		?>