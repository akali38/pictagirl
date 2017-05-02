<?php
add_action( 'add_meta_boxes' , 'ifba_add_meta_boxes');

/* META BOXES */

function ifba_add_meta_boxes(){
// Shortcode meta box
	add_meta_box( 'ifba_shortcode_meta_box' , 'Shortcode' , 'ifba_shortcode_meta_box_UI' , 'ifba_instagram_feed','side');

}
function ifba_shortcode_meta_box_UI( $post ){
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );

	?>
	<p id="ifba_shortcode_label">Use this shortcode to add Instagram Feed in your Posts, Pages & Text Widgets: </p>
	<input style="width: 100%;
    text-align: center;
    font-weight: bold;
    font-size: 20px;" type="text" readonly id="ifba_shortcode_value" name="ifba_shortcode_value" value="[arrow_feed id='<?php echo $post->ID; ?>']" />
	<?php
}