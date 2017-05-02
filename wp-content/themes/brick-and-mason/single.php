<?php 
get_header(); 
if (have_posts()) : while (have_posts()) : the_post(); 
$data = get_post_meta( $post->ID, 'key', true );
if(!empty($data[ 'video' ])){$video = $data[ 'video' ];}
?>
	
	<div  <?php post_class(); ?>>
		<a id="<?php the_title_attribute(); ?>" class="thumbLink" href="<?php if($previewLink == "image") { $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large'); echo $large_image_url[0]; } else { the_permalink(); } ?>" title="<?php the_title_attribute(); ?>" >
				<?php the_post_thumbnail('grid', array( 'alt' =>$alt )); ?>
			</a>
		
		<h2 class="posttitle"><a class="instalink" href="https://www.instagram.com/<?php the_title(); ?>" rel="nofollow" target="_blank">@<?php the_title(); ?></a>	<?php
			$follow = the_title_attribute('echo=0');
			$response = file_get_contents("https://www.instagram.com/$follow/?__a=1");
			if ($response !== false) {
				$data = json_decode($response, true);
				if ($data !== null) {
					$followedBy = $data['user']['followed_by']['count'];
					$certified = $data['user']['is_verified'];
					echo '<br><span class="follower">'. $followedBy .' </span>followers';
					if ($certified == 1)
					echo '<span class="verified coreSpriteVerifiedBadge" title="verified">Verified</span>';
				}
			}
			
		?>	<?php if(function_exists('the_ratings')) { the_ratings(); } ?></h2>
		
		<div class="metaStuff">
			<div class="categoryStuff metaItem"><?php the_category(', '); ?></div>
			<div class="dateStuff metaItem"><?php the_time('F j, Y'); ?></div>
			<!--<div class="authorStuff metaItem"><?php the_author(); ?></div> -->
			<div class="commentStuff metaItem"><?php comments_popup_link(__('0 Comments','themolitor'), __('1 Comment','themolitor'), __('% Comments','themolitor')); ?></div>
			<div class="tagStuff metaItem"><?php the_tags(''); ?></div>
		</div>
	
		<div class="entry">
				
		<?php the_content(); ?>
                		
		<div class="clear"></div>
        </div><!--end entry-->
        
        <br />
                        
        <div id="commentsection">
		<?php comments_template(); ?>
        </div>

	</div><!--end post-->
	
	<?php if ($video) { echo "<div id='videoEmbed'>". $video . "</div>"; } 
	
		$args = array(/*'exclude' => get_post_thumbnail_id(),*/ 'post_type' => 'attachment', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC', 'post_mime_type' => 'image' ,'post_status' => null, 'post_parent' => $post->ID );
		$attachments = get_posts($args);
		if ($attachments) { ?>
		
		<div id="postImgs">
			<?php foreach ( $attachments as $attachment ) { 
				$image_attributes = wp_get_attachment_image_src($attachment->ID,'medium');?> 
       				
      			<a class="postImg" href="<?php echo wp_get_attachment_url( $attachment->ID , false ); ?>"><img width="<?php echo $image_attributes[1] ?>" height="<?php echo $image_attributes[2] ?>" src="<?php echo wp_get_attachment_url( $attachment->ID , false ); ?>" alt="<?php the_title(); ?>" width="280" border="0" /></a>
			<?php } ?>
		</div>
		<?php } 
		endwhile; endif;
		get_footer(); 
		?>