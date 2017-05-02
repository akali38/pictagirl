<?php 
/*
Template Name: Full Width
*/
get_header(); 

if (have_posts()) : while (have_posts()) : the_post(); ?>
	
	<div id="fullWidth">
	
	<div id="leftContent">
		<h2 class="entrytitle"><?php the_title(); ?></h2>
	
	  	<div id="commentsection">
			<?php comments_template(); ?>
    	</div>
	</div>
		
	<div class="entry">
	<?php the_content(); ?>				
	<br />
							
	</div>
	</div>
	<?php endwhile; endif; ?>
		
  
<div class="clear"></div>

<?php get_footer(); ?>