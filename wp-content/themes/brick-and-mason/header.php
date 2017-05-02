<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta name="robots" content="index, follow">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=10, user-scalable=yes"/>

<meta name="description" content="Pictagirl - Discover new hot girls from Instagram everyday, without Instagram registration !">
<meta property="og:title" content="Pictagirl - Hot Girls pictures">
<meta property="og:description" content="Pictagirl - Discover new hot girls from Instagram everyday, without Instagram registration !">
<meta property="og:url" content="https://pictagirl.com">


<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/x-icon" /> 

<?php
//VAR SETUP
$logo = get_theme_mod('themolitor_customizer_logo');
$themeColor = get_theme_mod('themolitor_customizer_theme_skin');
$color = get_theme_mod('themolitor_customizer_accent_color');
$googleFont = get_theme_mod('themolitor_customizer_google_api');
$googleStyle = get_theme_mod('themolitor_customizer_google_key');
$customCss = get_theme_mod('themolitor_customizer_css');
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>?v=1.00" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/scripts/prettyPhoto.css" type="text/css" media="screen" />

<?php
if($googleFont){ 
	echo $googleFont; 
} else { 
	echo "<link href='https://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>";
} ?>

<style>

<?php if($googleStyle) { ?>
	body {font-family:'<?php echo $googleStyle; ?>', Sans-Serif;}
<?php } else { ?>
	body {font-family:'Droid Sans', Sans-Serif;}
<?php } 
	if($color){ } else {
		$color = "#748494";
} ?>

#sidebarAccent,
#commentform input[type="submit"], 
input[type="submit"],
#footer #copyright,
.widget_tag_cloud a {background-color: <?php echo $color;?>;}

a {color: <?php echo $color;?>;}

<?php if($customCss) { echo $customCss; }?>

</style>

<?php 
wp_enqueue_script('jquery');
wp_head(); 
if ( is_singular() ) wp_enqueue_script( "comment-reply" );
?>

<!--[if lt IE 9]>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/ie8.css" type="text/css" media="screen" />
<![endif]-->
<!--[if lt IE 8]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<![endif]-->


</head>

<body <?php body_class();?>>

<div id="headerContainer">
	
	<div id="header">
	
		<?php if($logo){ ?>
		<a id="logo" href="<?php echo home_url(); ?>"><img src="<?php echo $logo; ?>" alt="<?php bloginfo('name'); ?>" /></a><!--end logo-->  
				<h1>Pictagirl - Instagram Girl</h1>

		<?php } ?>
		
		<?php if (has_nav_menu( 'main' ) ) { wp_nav_menu(array('theme_location' => 'main', 'container_id' => 'navigation', 'menu_id' => 'dropmenu')); }?>
		<div class="clear"></div>
	
	</div><!--end header-->
</div><!--end headerContainer-->	

<div id="contentContainer">
	<div id="content">
	
