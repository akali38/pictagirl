<?php
///////////////////////
// Localization Support
///////////////////////

load_theme_textdomain( 'themolitor', get_template_directory().'/languages' );
$locale = get_locale();
$locale_file = get_template_directory().'/languages/$locale.php';
if ( is_readable($locale_file) )
    require_once($locale_file);
    
///////////////////////
//FEED LINKS STUFF
///////////////////////
add_theme_support('automatic-feed-links' );

///////////////////////
//FEATURED IMAGE SUPPORT
///////////////////////
add_theme_support( 'post-thumbnails', array( 'post' ) );
set_post_thumbnail_size( 280, 280, true );
add_image_size( 'grid',280 ,9999 );

///////////////////////
//CATEGORY ID FROM NAME FOR PAGE TEMPLATES
///////////////////////
function get_category_id($cat_name){
	$term = get_term_by('name', $cat_name, 'category');
	return $term->term_id;
}

///////////////////////
//CONTENT WIDTH STUFF
///////////////////////
if ( ! isset( $content_width ) ) $content_width = 960;

///////////////////////
//EXCERPT STUFF
///////////////////////
function new_excerpt_length($length) {
	return 25;
}
add_filter('excerpt_length', 'new_excerpt_length');
function new_excerpt_more($more) {
       global $post;
	return ' ...';
}
add_filter('excerpt_more', 'new_excerpt_more');

///////////////////////
//ADD MENU SUPPORT
///////////////////////
add_theme_support( 'menus' );
register_nav_menu('main', 'Main Navigation Menu');

///////////////////////
//BREADCRUMBS
///////////////////////
function dimox_breadcrumbs() {
  $delimiter = '&nbsp;&nbsp;/&nbsp;&nbsp;';
  $name = __('Home','themolitor');
  $currentBefore = '<span class="current">';
  $currentAfter = '</span> ';
  if ( !is_home() && !is_front_page() || is_paged() ) {
    echo '<div id="crumbs">';
    global $post;
    $home = home_url();
    echo '<a href="' . $home . '">' . $name . '</a> ' . $delimiter . ' ';
    if ( is_category() ) {
      global $wp_query;
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
      echo $currentBefore . '';
      single_cat_title();
      echo '' . $currentAfter;
    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $currentBefore . get_the_time('d') . $currentAfter;
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $currentBefore . get_the_time('F') . $currentAfter;
    } elseif ( is_year() ) {
      echo $currentBefore . get_the_time('Y') . $currentAfter;
    } elseif ( is_single() && !is_attachment() ) {
      $cat = get_the_category(); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo $currentBefore;
      _e("Current Page",'themolitor');
      echo $currentAfter;
    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
      echo $currentBefore;
      the_title();
      echo $currentAfter;
    } elseif ( is_page() && !$post->post_parent ) {
      echo $currentBefore;
      the_title();
      echo $currentAfter;
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
      echo $currentBefore;
      the_title();
      echo $currentAfter;
    } elseif ( is_search() ) {
      echo $currentBefore . __('Search Results','themolitor') . $currentAfter;
    } elseif ( is_tag() ) {
      echo $currentBefore . __('Posts tagged &#39;','themolitor');
      single_tag_title();
      echo '&#39;' . $currentAfter;
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $currentBefore . __('Articles posted by ','themolitor') . $userdata->display_name . $currentAfter;
    } elseif ( is_404() ) {
      echo $currentBefore . __('Error 404','themolitor') . $currentAfter;
    }
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page','themolitor') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
    echo '</div>';
  }
}

///////////////////////
//SIDEBAR GENERATOR (FOR SIDEBAR AND FOOTER)
///////////////////////
if ( function_exists('register_sidebar') )
register_sidebar(array('name'=>'Live Widgets',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
));

///////////////////////
//CUSOTM POST OPTIONS
///////////////////////
$key = "key";

$meta_boxes = array(

"category" => array(
"name" => "category",
"title" => "Category for page template - pages only",
"description" => "If using a page template, enter the category name you would like to use. For example, 'My Category Name'. This only applies to pages (not posts)."),

"video" => array(
"name" => "video",
"title" => "Media Embed Code (YouTube, Vimeo, or Google Maps) - posts only",
"description" => "NOTE: Width must be 280. For fixed grid layout, ideal dimensions are 280x170")

);
function create_meta_box() {
	global $key;
	if( function_exists( 'add_meta_box' ) ) {
		add_meta_box( 'new-meta-boxes', ' Custom Post Options', 'display_meta_box', 'post', 'normal', 'high' );
		add_meta_box( 'new-meta-boxes', ' Custom Post Options', 'display_meta_box', 'page', 'normal', 'high' );
	}
}
function display_meta_box() {
	global $post, $meta_boxes, $key;
?>
<div class="form-wrap">
<?php wp_nonce_field( plugin_basename( __FILE__ ), $key . '_wpnonce', false, true );
foreach($meta_boxes as $meta_box) {
	$data = get_post_meta($post->ID, $key, true);
?>
<div class="form-field form-required">
	<label for="<?php echo $meta_box[ 'name' ]; ?>"><?php echo $meta_box[ 'title' ]; ?></label>
	<input type="text" name="<?php echo $meta_box[ 'name' ]; ?>" value="<?php if(!empty($data[ $meta_box[ 'name' ] ])){ echo htmlspecialchars( $data[ $meta_box[ 'name' ] ] );} ?>" />
	<p><?php echo $meta_box[ 'description' ]; ?></p>
</div>
<?php } ?>
</div>
<?php
}
function save_meta_box( $post_id ) {
	global $post, $meta_boxes, $key;
	foreach( $meta_boxes as $meta_box ) {
		$data[ $meta_box[ 'name' ] ] = $_POST[ $meta_box[ 'name' ] ];
	}
	if ( !wp_verify_nonce( $_POST[ $key . '_wpnonce' ], plugin_basename(__FILE__) ) )
	return $post_id;
	if ( !current_user_can( 'edit_post', $post_id ))
	return $post_id;
	update_post_meta( $post_id, $key, $data );
}
add_action( 'admin_menu', 'create_meta_box' );
add_action( 'save_post', 'save_meta_box' );

////////////////////////////
//THEME CUSTOMIZER MENU ITEM
////////////////////////////
function themolitor_customizer_admin() {
    add_theme_page( __('Theme Options','themolitor'),  __('Theme Options','themolitor'), 'edit_theme_options', 'customize.php' ); 
}
add_action ('admin_menu', 'themolitor_customizer_admin');

////////////////////////////
//THEME CUSTOMIZER SETTINGS
////////////////////////////
add_action( 'customize_register', 'themolitor_customizer_register' );

function themolitor_customizer_register($wp_customize) {

	//CREATE TEXTAREA OPTION
	class Example_Customize_Textarea_Control extends WP_Customize_Control {
    	public $type = 'textarea';
 
    	public function render_content() { ?>
        	<label>
        	<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        	<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
        	</label>
        <?php }
	}
	
	//CREATE CATEGORY DROP DOWN OPTION
	$options_categories = array();  
	$options_categories_obj = get_categories();
	$options_categories[''] = 'Select a Category';
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	//-------------------------------
	//GENERAL SECTION
	//-------------------------------
	
	//ADD GENERAL SECTION
	$wp_customize->add_section( 'themolitor_customizer_general_section', array(
		'title' => __( 'General', 'themolitor' ),
		'priority' => 1
	));
	
	//LOGO
	$wp_customize->add_setting( 'themolitor_customizer_logo');
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'themolitor_customizer_logo', array(
    	'label'    => __('Logo', 'themolitor'),
    	'section'  => 'themolitor_customizer_general_section',
    	'settings' => 'themolitor_customizer_logo',
    	'priority' => 1
	)));
	
	//THEME SKIN
	$wp_customize->add_setting('themolitor_customizer_theme_skin', array(
	    'capability'     => 'edit_theme_options',
	    'default'        => 'light'
	));
	$wp_customize->add_control( 'themolitor_customizer_theme_skin', array(
 	   	'label'   => __('Theme Skin','themolitor'),
		'section' => 'themolitor_customizer_general_section',
   	 	'type'    => 'select',
   	 	'choices' => array('light' => 'Light','dark' => 'Dark'),
   	 	'settings' => 'themolitor_customizer_theme_skin',
   	 	'priority' => 2
	));

	//DISPLAY BREADCRUMBS
	$wp_customize->add_setting( 'themolitor_customizer_bread_onoff', array(
    	'default' => 1
	));
	$wp_customize->add_control( 'themolitor_customizer_bread_onoff', array(
    	'label' => 'Display Category Breadcrumbs',
    	'type' => 'checkbox',
    	'section' => 'themolitor_customizer_general_section',
    	'settings' => 'themolitor_customizer_bread_onoff',
    	'priority' => 3
	));
				
	//-------------------------------
	//COLORS SECTION
	//-------------------------------
	
	//ACCENT COLOR
	$wp_customize->add_setting( 'themolitor_customizer_accent_color', array(
		'default' => '#748494'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'themolitor_customizer_accent_color', array(
		'label'   => __( 'Accent Color', 'themolitor'),
		'section' => 'colors',
		'settings'   => 'themolitor_customizer_accent_color'
	)));		
	
	//-------------------------------
	//HOME SETTINGS SECTION
	//-------------------------------
	
	//ADD HOME SETTINGS SECTION
	$wp_customize->add_section( 'themolitor_customizer_home_section', array(
		'title' => __( 'Home Settings', 'themolitor' ),
		'priority' => 197
	));
	
	//HOME CATEGORY
	$wp_customize->add_setting('themolitor_home_category', array(
	    'capability'     => 'edit_theme_options',
	    'type'           => 'option'
	));
	$wp_customize->add_control( 'themolitor_home_category', array(
 	   'settings' => 'themolitor_home_category',
 	   'label'   => __('Home Category','themolitor'),
   	 	'section' => 'themolitor_customizer_home_section',
   	 	'type'    => 'select',
   	 	'choices' => $options_categories,
   	 	'priority' => 1
	));

	//NUMBER OF ITEMS
    $wp_customize->add_setting( 'themolitor_customizer_home_number',array(
    	'default' => '8'
    ));
	$wp_customize->add_control('themolitor_customizer_home_number', array(
   		'label'   => __( 'Number of items to display initially', 'themolitor'),
    	'section' => 'themolitor_customizer_home_section',
    	'settings'   => 'themolitor_customizer_home_number',
    	'type' => 'text',
    	'priority' => 2
	));
	
	//-------------------------------
	//PORTFOLIO SETTINGS SECTION
	//-------------------------------

	//ADD FOOTER SECTION
	$wp_customize->add_section( 'themolitor_customizer_portfolio_section', array(
		'title' => __( 'Portfolio Settings', 'themolitor' ),
		'priority' => 198
	));
	
	//DISPLAY POST TITLE
	$wp_customize->add_setting( 'themolitor_customizer_post_title_onoff', array(
    	'default' => 1
	));
	$wp_customize->add_control( 'themolitor_customizer_post_title_onoff', array(
    	'label' => 'Display Post Title',
    	'type' => 'checkbox',
    	'section' => 'themolitor_customizer_portfolio_section',
    	'settings' => 'themolitor_customizer_post_title_onoff',
    	'priority' => 1
	));
	
	//DISPLAY EXCERPT
	$wp_customize->add_setting( 'themolitor_customizer_excerpt_onoff', array(
    	'default' => 1
	));
	$wp_customize->add_control( 'themolitor_customizer_excerpt_onoff', array(
    	'label' => 'Display Post Excerpt',
    	'type' => 'checkbox',
    	'section' => 'themolitor_customizer_portfolio_section',
    	'settings' => 'themolitor_customizer_excerpt_onoff',
    	'priority' => 2
	));
	
	//PREVIEW IMAGE LINK
	$wp_customize->add_setting('themolitor_customizer_preview', array(
	    'capability'     => 'edit_theme_options',
	    'default'        => 'post'
	));
	$wp_customize->add_control( 'themolitor_customizer_preview', array(
 	   	'label'   => __('Preview Image Links to...','themolitor'),
		'section' => 'themolitor_customizer_portfolio_section',
   	 	'type'    => 'select',
   	 	'choices' => array('post' => 'Post','image' => 'Image'),
   	 	'settings' => 'themolitor_customizer_preview'
	));
		
	//-------------------------------
	//FOOTER SECTION
	//-------------------------------

	//ADD FOOTER SECTION
	$wp_customize->add_section( 'themolitor_customizer_footer_section', array(
		'title' => __( 'Footer', 'themolitor' ),
		'priority' => 199
	));
	
	//FOOTER TEXT
    $wp_customize->add_setting( 'themolitor_customizer_footer');
	$wp_customize->add_control('themolitor_customizer_footer', array(
   		'label'   => __( 'Footer Text', 'themolitor'),
    	'section' => 'themolitor_customizer_footer_section',
    	'settings'   => 'themolitor_customizer_footer',
    	'type' => 'text',
    	'priority' => 1
	));
			
	//DISPLAY RSS BUTTON
	$wp_customize->add_setting( 'themolitor_customizer_rss_onoff', array(
    	'default' => 1
	));
	$wp_customize->add_control( 'themolitor_customizer_rss_onoff', array(
    	'label' => 'Display RSS Button',
    	'type' => 'checkbox',
    	'section' => 'themolitor_customizer_footer_section',
    	'settings' => 'themolitor_customizer_rss_onoff',
    	'priority' => 5
	));
	
	//TWITTER
    $wp_customize->add_setting( 'themolitor_customizer_twitter');
	$wp_customize->add_control('themolitor_customizer_twitter', array(
   		'label'   => __( 'Twitter URL', 'themolitor'),
    	'section' => 'themolitor_customizer_footer_section',
    	'settings'   => 'themolitor_customizer_twitter',
    	'type' => 'text',
    	'priority' => 6
	));
	
	//FACEBOOK
    $wp_customize->add_setting( 'themolitor_customizer_facebook');
	$wp_customize->add_control('themolitor_customizer_facebook', array(
   		'label'   => __( 'Facebook URL', 'themolitor'),
    	'section' => 'themolitor_customizer_footer_section',
    	'settings'   => 'themolitor_customizer_facebook',
    	'type' => 'text',
    	'priority' => 7
	));
	
	//FLIKr
    $wp_customize->add_setting( 'themolitor_customizer_flikr');
	$wp_customize->add_control('themolitor_customizer_flikr', array(
   		'label'   => __( 'Flikr URL', 'themolitor'),
    	'section' => 'themolitor_customizer_footer_section',
    	'settings'   => 'themolitor_customizer_flikr',
    	'type' => 'text',
    	'priority' => 8
	));
	
	//MYSPACE
    $wp_customize->add_setting( 'themolitor_customizer_myspace');
	$wp_customize->add_control('themolitor_customizer_myspace', array(
   		'label'   => __( 'MySpace URL', 'themolitor'),
    	'section' => 'themolitor_customizer_footer_section',
    	'settings'   => 'themolitor_customizer_myspace',
    	'type' => 'text',
    	'priority' => 9
	));
	
	//LINKEDIN
    $wp_customize->add_setting( 'themolitor_customizer_linkedin');
	$wp_customize->add_control('themolitor_customizer_linkedin', array(
   		'label'   => __( 'LinkedIn URL', 'themolitor'),
    	'section' => 'themolitor_customizer_footer_section',
    	'settings'   => 'themolitor_customizer_linkedin',
    	'type' => 'text',
    	'priority' => 10
	));
	
	//YOUTUBE
    $wp_customize->add_setting( 'themolitor_customizer_youtube');
	$wp_customize->add_control('themolitor_customizer_youtube', array(
   		'label'   => __( 'YouTube URL', 'themolitor'),
    	'section' => 'themolitor_customizer_footer_section',
    	'settings'   => 'themolitor_customizer_youtube',
    	'type' => 'text',
    	'priority' => 11
	));
	
	//SKYPE
    $wp_customize->add_setting( 'themolitor_customizer_skype');
	$wp_customize->add_control('themolitor_customizer_skype', array(
   		'label'   => __( 'Skype URL', 'themolitor'),
    	'section' => 'themolitor_customizer_footer_section',
    	'settings'   => 'themolitor_customizer_skype',
    	'type' => 'text',
    	'priority' => 12
	));
	
	//VIMEO
    $wp_customize->add_setting( 'themolitor_customizer_vimeo');
	$wp_customize->add_control('themolitor_customizer_vimeo', array(
   		'label'   => __( 'Vimeo URL', 'themolitor'),
    	'section' => 'themolitor_customizer_footer_section',
    	'settings'   => 'themolitor_customizer_vimeo',
    	'type' => 'text',
    	'priority' => 13
	));
	
	//-------------------------------
	//GOOGLE FONT SECTION
	//-------------------------------

	//ADD GOOGLE FONT SECTION
	$wp_customize->add_section( 'themolitor_customizer_googlefont_section', array(
		'title' => __( 'Google Custom Font', 'themolitor' ),
		'priority' => 200
	));
	
	//GOOGLE API
    $wp_customize->add_setting( 'themolitor_customizer_google_api', array(
    	'default' => '<link href="http://fonts.googleapis.com/css?family=Droid+Sans" rel="stylesheet" type="text/css">'
	));
	$wp_customize->add_control('themolitor_customizer_google_api', array(
   		'label'   => __( 'Google Font API Link', 'themolitor'),
    	'section' => 'themolitor_customizer_googlefont_section',
    	'settings'   => 'themolitor_customizer_google_api',
    	'type' => 'text',
    	'priority' => 1
	));
	
	//GOOGLE KEYWORD
    $wp_customize->add_setting( 'themolitor_customizer_google_key', array(
    	'default' => 'Droid Sans'
	));
	$wp_customize->add_control('themolitor_customizer_google_key', array(
   		'label'   => __( 'Google Font Keyword', 'themolitor'),
    	'section' => 'themolitor_customizer_googlefont_section',
    	'settings'   => 'themolitor_customizer_google_key',
    	'type' => 'text',
    	'priority' => 2
	));
		
	//-------------------------------
	//CUSTOM CSS SECTION
	//-------------------------------
	
	//ADD CSS SECTION
	$wp_customize->add_section( 'themolitor_customizer_custom_css', array(
		'title' => __( 'CSS', 'themolitor' ),
		'priority' => 201
	));
			
	//CUSTOM CSS
    $wp_customize->add_setting( 'themolitor_customizer_css');
	$wp_customize->add_control( new Example_Customize_Textarea_Control( $wp_customize, 'themolitor_customizer_css', array(
   		'label'   => __( 'Custom CSS', 'themolitor'),
    	'section' => 'themolitor_customizer_custom_css',
    	'settings'   => 'themolitor_customizer_css'
	)));

}

function wpb_move_comment_field_to_bottom( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;
	return $fields;
	}

	add_filter( 'comment_form_fields', 'wpb_move_comment_field_to_bottom' );

?>