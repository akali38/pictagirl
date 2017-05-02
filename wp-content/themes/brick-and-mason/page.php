<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="leftContent">
		<h2 class="entrytitle"><?php the_title(); ?></h2>
	
	  	<div id="commentsection">
			<?php comments_template(); ?>
    	</div>
	</div>
	
	<div id="rightContent">	
	<div class="entry">
	<?php the_content(); ?>				
	<br />
							
	</div>
	</div>
	<?php endwhile; endif; ?>
  
<div class="clear"></div>

<?php get_footer(); ?>