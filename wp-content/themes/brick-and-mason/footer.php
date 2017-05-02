<div class="clear"></div>
</div><!--end content-->
</div><!--end contentContainer-->

<?php
//VAR SETUP
$twitter = get_theme_mod('themolitor_customizer_twitter');
$facebook = get_theme_mod('themolitor_customizer_facebook');
$rss = get_theme_mod('themolitor_customizer_rss_onoff');
$youtube = get_theme_mod('themolitor_customizer_youtube');
$vimeo = get_theme_mod('themolitor_customizer_vimeo');
$skype = get_theme_mod('themolitor_customizer_skype');
$myspace = get_theme_mod('themolitor_customizer_myspace');
$flickr = get_theme_mod('themolitor_customizer_flikr');
$linkedin = get_theme_mod('themolitor_customizer_linkedin');
$themeColor = get_theme_mod('themolitor_customizer_theme_skin');
$footer = get_theme_mod('themolitor_customizer_footer'); 
?>

<div id="footerContainer">
<div id="footer">  
	
	<div id="copyright">
	
	<?php if ($rss == 1) { ?>
	<a class="socialicon" id="rssIcon" href="<?php bloginfo('rss2_url'); ?>"  title="<?php _e('Subscribe via RSS','themolitor');?>" rel="nofollow"></a>
	<?php } if ($skype) { ?>
	<a class="socialicon" id="skypeIcon" href="<?php echo $skype; ?>"  title="Skype" rel="nofollow"></a>
	<?php } if ($myspace) { ?>
	<a class="socialicon" id="myspaceIcon" href="<?php echo $myspace; ?>"  title="MySpace" rel="nofollow"></a>
	<?php } if ($flickr) { ?>
	<a class="socialicon" id="flickrIcon" href="<?php echo $flickr; ?>"  title="Flickr" rel="nofollow"></a>
	<?php } if ($linkedin) { ?>
	<a class="socialicon" id="linkedinIcon" href="<?php echo $linkedin; ?>"  title="LinkedIn" rel="nofollow"></a>
	<?php } if ($youtube) { ?> 
	<a class="socialicon" id="youtubeIcon" href="<?php echo $youtube; ?>" title="YouTube Channel"  rel="nofollow"></a>
	<?php } if ($vimeo) { ?> 
	<a class="socialicon" id="vimeoIcon" href="<?php echo $vimeo; ?>"  title="Vimeo Profile" rel="nofollow"></a>
	<?php } if ($facebook) { ?> 
	<a class="socialicon" id="facebookIcon" href="<?php echo $facebook; ?>"  title="Facebook Profile" rel="nofollow"></a>
	<?php } if ($twitter) { ?> 
	<a class="socialicon" id="twitterIcon" href="<?php echo $twitter; ?>" title="Follow on Twitter"  rel="nofollow"></a>
	<?php } ?>
	
	
	&copy; <?php echo date("Y "); bloginfo('name'); ?>. <?php if($footer) { echo $footer;} ?>
	</div>

	<a id='backTop' href='#'></a>
</div><!--end footer-->
</div><!--end footerContainer-->

<!--<div id="loaderGif"><img src="<?php echo get_template_directory_uri(); ?>/scripts/loader_light.gif" alt="loading.." /></div>-->

<div id="sidebarToggle" class="openSide">
	<div id="plusSign">&nbsp;</div>
	<div id="sidebarAccent">&nbsp;</div>
</div>

<?php get_sidebar(); ?>

<script src="<?php echo get_template_directory_uri(); ?>/scripts/scripts.js" type="text/javascript"></script>
<script>
jQuery.noConflict(); 
jQuery(document).ready(function(){ 
			
	molitorscripts(); 

	//PRETTY PHOTO
	function prettyP(){
		jQuery("a[href$='jpg'],a[href$='png'],a[href$='gif']").attr({rel: "prettyPhoto"});
		jQuery(".gallery-icon > a[href$='jpg'],.gallery-icon > a[href$='png'],.gallery-icon > a[href$='gif'], .postImg").attr({rel: "prettyPhoto[pp_gal]"});
		jQuery("a[rel^='prettyPhoto']").prettyPhoto({
			animation_speed: 'normal', // fast/slow/normal 
			opacity: 0.35, // Value betwee 0 and 1 
			show_title: false, // true/false 
			allow_resize: true, // true/false 
			overlay_gallery: false,
			counter_separator_label: ' of ', // The separator for the gallery counter 1 "of" 2 
			//theme: 'light_rounded', // light_rounded / dark_rounded / light_square / dark_square 
			hideflash: true, // Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto 
			modal: false // If set to true, only the close button will close the window 
		});
	}		
	prettyP();
	/*
	// infinitescroll
	jQuery('#listing,.listing').infinitescroll({
		loading: {
        	img: "<?php echo get_template_directory_uri(); ?>/scripts/loader_light.gif"
  		},
		navSelector  : ".navigation",            
 		nextSelector : ".pagenav a",     
		itemSelector : ".post, .page"
	},function( newElements ) {
		jQuery(this).masonry({ 
			appendedContent: jQuery( newElements ) 
		});
	    prettyP();
	});
		
	jQuery("#videoEmbed").next("#postImgs").css({"marginTop":"430px"});*/

});


</script>

<?php wp_footer(); ?>


<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-86097484-3', 'auto');
  ga('send', 'pageview');

</script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/zoom.css" type="text/css"/>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="<?php echo get_template_directory_uri(); ?>/scripts/zoom.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri(); ?>/scripts/transition.js" type="text/javascript"></script>

</body>
</html>