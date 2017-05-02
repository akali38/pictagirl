<?php 
/*
Template Name: Fixed Image Grid
*/
get_header(); 
	//VAR SETUP
	$crumbs = get_theme_mod('themolitor_customizer_bread_onoff');
	$showtitle = get_theme_mod('themolitor_customizer_post_title_onoff'); 
	$showsample = get_theme_mod('themolitor_customizer_excerpt_onoff');
	$previewLink = get_theme_mod('themolitor_customizer_preview');

	if ($crumbs && function_exists('dimox_breadcrumbs')) dimox_breadcrumbs();
?>

	<div id="listing">
	
	<?php
	//CATEGORY
	$data = get_post_meta( $post->ID, 'key', true );
	if(!empty($data[ 'category' ])){$category = $data[ 'category' ];}
	if($category){$categoryID = get_category_id($category);}
	//LOOP
	$temp = $wp_query;
	$wp_query= null;
	$wp_query = new WP_Query();
	$wp_query->query('cat='. $categoryID .'&paged='.$paged);
	while ($wp_query->have_posts()) : $wp_query->the_post(); 
	$data = get_post_meta( $post->ID, 'key', true );
	if(!empty($data[ 'video' ])){$video = $data[ 'video' ];}
	?>
		
		<div <?php post_class(); ?>>
		
		<?php if (has_post_thumbnail()) { ?>
			<a class="thumbLink" href="<?php if($previewLink == "image") { $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large'); echo $large_image_url[0]; } else { the_permalink(); } ?>" title="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail(); ?>
			</a>
		<?php } elseif ($video) { echo $video; } ?>
		
		<?php if($showtitle){ ?>
		<h2 class="postLink"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
		<?php } ?>
				
		<?php if($showsample){?>
			<?php the_excerpt();?>
			<a class="readMore" href="<?php the_permalink() ?>"><?php _e('Read More','themolitor');?> &rarr;</a>
		<?php } ?>
		
		</div><!--end post-->

		<?php endwhile; ?>
	
		</div><!--end listing-->

	<?php 
	get_template_part("navigation");
	$wp_query = null; $wp_query = $temp;
	get_footer(); 
	?>