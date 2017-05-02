<?php
add_action( 'save_post', 'ifba_save_form' );

function ifba_save_form( $post_id ) {

	$post_type = get_post_type($post_id);
// If this isn't a 'sfba_subscribe_form' post, don't update it.
	if ( "ifba_instagram_feed" != $post_type ) {
		return;
	}

// Stop WP from clearing custom fields on autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
		return;
	}

// Prevent quick edit from clearing custom fields
	if (defined('DOING_AJAX') && DOING_AJAX){
		return;
	}
// - Update the post's metadata.
	if ( isset( $_POST['ifba_private_access_token'] ) ) {
		update_post_meta( $post_id, '_ifba_private_access_token', sanitize_text_field( $_POST['ifba_private_access_token'] ) );
	}
	if ( isset( $_POST['ifba_shortcode_value'] ) ) {
		update_post_meta( $post_id, '_ifba_shortcode_value', sanitize_text_field( $_POST['ifba_shortcode_value'] ) );
	}
	if ( isset( $_POST['ifba_theme_selection'] ) ) {
		update_post_meta( $post_id, '_ifba_theme_selection', sanitize_text_field( $_POST['ifba_theme_selection'] ) );
	}
	if ( isset( $_POST['ifba_feed_post_size'] ) ) {
		update_post_meta( $post_id, '_ifba_feed_post_size', sanitize_text_field( $_POST['ifba_feed_post_size'] ) );
	}
	if ( isset( $_POST['ifba_limit_post_characters'] ) ) {
		update_post_meta( $post_id, '_ifba_limit_post_characters', sanitize_text_field( $_POST['ifba_limit_post_characters'] ) );
	}
	if ( isset( $_POST['ifba_column_count'] ) ) {
		update_post_meta( $post_id, '_ifba_column_count', sanitize_text_field( $_POST['ifba_column_count'] ) );
	}
	if ( isset( $_POST['ifba_thumbnail_size'] ) ) {
		update_post_meta( $post_id, '_ifba_thumbnail_size', sanitize_text_field( $_POST['ifba_thumbnail_size'] ) );
	}
	if ( isset( $_POST['ifba_feed_style'] ) ) {
		update_post_meta( $post_id, '_ifba_feed_style', sanitize_text_field( $_POST['ifba_feed_style'] ) );
	}
	if ( isset( $_POST['ifba_show_photos_from'] ) ) {
		update_post_meta( $post_id, '_ifba_show_photos_from', sanitize_text_field( $_POST['ifba_show_photos_from'] ) );
	}
	if ( isset( $_POST['ifba_date_posted_lang'] ) ) {
		update_post_meta( $post_id, '_ifba_date_posted_lang', sanitize_text_field( $_POST['ifba_date_posted_lang'] ) );
	}
	if ( isset( $_POST['ifba_user_id'] ) ) {
		update_post_meta( $post_id, '_ifba_user_id', sanitize_text_field( $_POST['ifba_user_id'] ) );
	}
	if ( isset( $_POST['ifba_hashtag'] ) ) {
		update_post_meta( $post_id, '_ifba_hashtag', sanitize_text_field( $_POST['ifba_hashtag'] ) );
	}
	if ( isset( $_POST['ifba_location'] ) ) {
		update_post_meta( $post_id, '_ifba_location', sanitize_text_field( $_POST['ifba_location'] ) );
	}
	if ( isset( $_POST['ifba_container_width'] ) ) {
		update_post_meta( $post_id, '_ifba_container_width', sanitize_text_field( $_POST['ifba_container_width'] ) );
	}
	if ( isset( $_POST['ifba_number_of_photos'] ) ) {
		update_post_meta( $post_id, '_ifba_number_of_photos', sanitize_text_field( $_POST['ifba_number_of_photos'] ) );
	}
	if ( isset( $_REQUEST['ifba_show_photos_only'] ) ) {
		update_post_meta($post_id, '_ifba_show_photos_only', TRUE);
	} else {
		update_post_meta($post_id, '_ifba_show_photos_only', FALSE);
	}
	if ( isset( $_REQUEST['ifba_date_posted'] ) ) {
		update_post_meta($post_id, '_ifba_date_posted', TRUE);
	} else {
		update_post_meta($post_id, '_ifba_date_posted', FALSE);
	}
	if ( isset( $_REQUEST['ifba_profile_picture'] ) ) {
		update_post_meta($post_id, '_ifba_profile_picture', TRUE);
	} else {
		update_post_meta($post_id, '_ifba_profile_picture', FALSE);
	}
	if ( isset( $_REQUEST['ifba_caption_text'] ) ) {
		update_post_meta($post_id, '_ifba_caption_text', TRUE);
	} else {
		update_post_meta($post_id, '_ifba_caption_text', FALSE);
	}
	if ( isset( $_REQUEST['ifba_link_photos_to_instagram'] ) ) {
		update_post_meta($post_id, '_ifba_link_photos_to_instagram', TRUE);
	} else {
		update_post_meta($post_id, '_ifba_link_photos_to_instagram', FALSE);
	}
}