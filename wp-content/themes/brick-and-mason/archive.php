<?php 

get_header();

//VAR SETUP
$crumbs = get_theme_mod('crumbs_on_off');
$showtitle = get_theme_mod('themolitor_customizer_post_title_onoff'); 
$showsample = get_theme_mod('themolitor_customizer_excerpt_onoff');
$previewLink = get_theme_mod('themolitor_customizer_preview');

if ($crumbs && function_exists('dimox_breadcrumbs')) dimox_breadcrumbs();
?>

	<div id="listing">
		<?php 
		if (have_posts()) : while (have_posts()) : the_post(); 
		$data = get_post_meta( $post->ID, 'key', true );
		if(!empty($data[ 'video' ])){$video = $data[ 'video' ];}
		?>
	
		<div <?php post_class(); ?>>
		
		<?php if (has_post_thumbnail()) { ?>
			<a class="thumbLink" href="<?php if($previewLink == 'image') { $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large'); echo $large_image_url[0]; } else { the_permalink(); } ?>" title="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail('grid'); ?>
			</a>
		<?php } elseif ($video) { 
			echo $video; 
		} if($showtitle){ ?>
			<h2 class="postLink"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to','themolitor');?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
		<?php } if($showsample){?>
			<?php the_excerpt();?>
			<a class="readMore" href="<?php the_permalink() ?>"><?php _e('Read More','themolitor');?> &rarr;</a>
		<?php } ?>
		
		</div><!--end post-->

		<?php endwhile; ?>
		
		</div><!--end listing-->
		
		<?php
		get_template_part('navigation');
		else : ?>
		
		<h2 class="center"><?php _e('Not Found','themolitor');?></h2>
		<p class="center"><?php _e("Sorry, but you are looking for something that isn't here.",'themolitor');?></p>
		
		</div><!--end listing-->
		
		<?php endif; ?>
	
<?php get_footer(); ?>