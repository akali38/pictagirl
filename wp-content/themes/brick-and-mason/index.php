<?php 

get_header();

//VAR SETUP
$homeCategory = get_option('themolitor_home_category'); 
$homeNumber = get_theme_mod('themolitor_customizer_home_number'); 
$showtitle = get_theme_mod('themolitor_customizer_post_title_onoff'); 
$showsample = get_theme_mod('themolitor_customizer_excerpt_onoff');
$previewLink = get_theme_mod('themolitor_customizer_preview');

	$temp = $wp_query;
	$wp_query= null;
	$not_duplicate = get_the_ID();
	//$wp_query = new WP_Query();
	//$wp_query = new WP_Query(array( 'cat' => $homeCategory, 'showposts' => $homeNumber, 'paged' => $paged, 'orderby' => 'rand', 'post__not_in' => array($not_duplicate)));
	$wp_query = new WP_Query(array( 'cat' => $homeCategory, 'showposts' => $homeNumber, 'paged' => $paged, 'order' => 'ASC'));

	//$wp_query->query('cat='. $homeCategory .'&showposts='. $homeNumber .'&paged='. $paged .'&orderby=rand');
	//var_dump($wp_query->request);
	//var_dump($wp_query);

	if($wp_query->have_posts()): ?>
	
	<div id="listing">
	<div class="mid-content">
	<?php while ($wp_query->have_posts()) : $wp_query->the_post(); 
	$data = get_post_meta( $post->ID, 'game_locked', true );
	if(!empty($data[ 'game_locked' ])){$video = $data[ 'game_locked' ];}
	//var_dump($data);

	?>
	
		<div <?php post_class(); ?>>
		
		<?php 
		if (has_post_thumbnail()) { ?>

			<?php $alt = the_title_attribute('echo=0'); ?>
			<a id="<?php the_title_attribute(); ?>" class="thumbLink" href="<?php if($previewLink == "image") { $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large'); echo $large_image_url[0]; } else { the_permalink(); } ?>" title="<?php the_title_attribute(); ?>" >
				<?php the_post_thumbnail('grid', array( 'alt' =>$alt )); ?>
			</a>
			<h3 class="follow-index"><?php the_title_attribute();?></h3>
			
		<?php } elseif ($video) { echo $video; } ?>
		
		<?php if($showtitle){ ?>
		<h2 class="postLink"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to','themolitor');?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
		<?php } ?>
				
		<?php if($showsample){?>
			<?php the_excerpt();?>
			<a class="readMore" href="<?php the_permalink() ?>"><?php _e('Read More','themolitor');?> &rarr;</a>
		<?php } ?>
		
		</div><!--end post-->

		<?php endwhile;?>
	</div>
</div><!--end listing-->

<?php 
get_template_part('navigation'); 
endif;
$wp_query = null; $wp_query = $temp;
?>

<div class="intro_div">

	<p class="intro">	<span class="hot"> The hottest Instagram girl</span>
These are the most popular hot girl on Instagram that you need to follow. Our goal is to regroup the most beautiful or the hottest women in the world from Instagram. <br>
	It's not a mystery that since Instagram was made and introduced to the public, it has been used by bunch of millions people around the world, especially for men and women, since they are able to show off their successful and beautiful
	pictures on daily basis. There are definitely many hot girls that you can find on Instagram, Pictagirl help you to find those Instagram girls account and follow them as you like.
	</p>
</div>
<?php
get_footer(); 
?>
