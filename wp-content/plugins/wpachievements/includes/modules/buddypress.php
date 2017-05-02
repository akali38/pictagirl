<?php
/**
 * Module Name: BuddyPress Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/BuddyPress
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action('bp_activity_posted_update','wpachievements_my_bp_update_post_add', 10, 3);
 add_action('bp_activity_comment_posted','wpachievements_my_bp_update_comment_add', 10, 2);
 add_action('bp_activity_action_delete_activity','wpachievements_my_bp_delete_comment_add', 10, 2);
 add_action('groups_group_create_complete','wpachievements_my_bp_create_group_add');
 add_action('groups_delete_group','wpachievements_my_bp_create_group_remove', 10 , 2);
 add_action('groups_join_group','wpachievements_my_bp_join_group_add', 10, 2);
 add_action('groups_membership_accepted','wpachievements_my_bp_join_non_public_group_add', 10, 2);
 add_action('groups_accept_invite','wpachievements_my_bp_join_non_public_group_add', 10, 2);
 add_action('groups_leave_group','wpachievements_my_bp_leave_group_add_cppoints', 10, 2);
 add_action('bp_groups_posted_update','wpachievements_my_bp_update_group_add', 10, 4);
 add_action('friends_friendship_accepted','wpachievements_my_bp_friend_add', 10, 3);
 add_action('friends_friendship_deleted','wpachievements_my_bp_friend_delete_add', 10, 3);
 add_action('bp_forums_new_topic','wpachievements_my_bp_forum_new_topic_add');
 add_action('bp_forums_new_post','wpachievements_my_bp_forum_new_post_add');
 add_action('groups_edit_forum_topic','wpachievements_my_bp_forum_edit_topic_add');
 add_action('xprofile_avatar_uploaded','wpachievements_my_bp_avatar_add');
 add_action('groups_screen_group_admin_avatar','wpachievements_my_bp_group_avatar_add');
 add_action('messages_message_sent','wpachievements_my_bp_pm');
 //*************** Detect New Update ***************\\
 function wpachievements_my_bp_update_post_add( $content, $user_id, $activity_id ){
  if( !empty($user_id) ){
   $type='cp_bp_update'; $uid=$user_id; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_update_points');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
  }
 }
 //*************** Detect New Comment or Reply ***************\\
 function wpachievements_my_bp_update_comment_add( $comment_id, $params ){
  if( !empty($comment_id) ){
   $comment = get_comment( $comment_id );
   if( $comment->user_id ){
    $type='cp_bp_reply'; $uid=$comment->user_id; $postid='';
    if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
      $points = (int) wpachievements_get_site_option('wpachievements_bp_reply_points');
    }
    if(empty($points)){$points=0;}
    wpachievements_new_activity($type, $uid, $postid, $points);
   }
  }
 }
 //*************** Detect Removes Comment or Reply ***************\\
 function wpachievements_my_bp_delete_comment_add( $id, $user_id ){
  if( !empty($user_id) ){
   $type='cp_bp_update_removed'; $uid=$user_id; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
    $points = (int) wpachievements_get_site_option('wpachievements_bp_reply_points');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, -$points);
  }
 }
 //*************** Detect New Group ***************\\
 function wpachievements_my_bp_create_group_add( $new_group_id ){
   $current_user = wp_get_current_user();
   $type='cp_bp_group_create'; $uid=$current_user->ID; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_group_create');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
 }
 //*************** Detect Group Removed ***************\\
 function wpachievements_my_bp_create_group_remove( $id, $user_id ){
  if( !empty($user_id) ){
   $type='cp_bp_group_delete'; $uid=$user_id; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_group_create');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, -$points);
  }
 }
 //*************** Detect Join Group ***************\\
 function wpachievements_my_bp_join_group_add( $group_id, $user_id ){
  if( !empty($user_id) ){
   $type='cp_bp_group_joined'; $uid=$user_id; $postid=$group_id;
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_group_joined');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
  }
 }
 function wpachievements_my_bp_join_non_public_group_add( $user_id, $group_id ){
  if( !empty($user_id) ){
   $type='cp_bp_group_joined'; $uid=$user_id; $postid=$group_id;
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_group_joined');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
  }
 }
 //*************** Detect Leave Group ***************\\
 function wpachievements_my_bp_leave_group_add_cppoints( $group_id, $user_id ){
  if( !empty($user_id) ){
   $type='cp_bp_group_left'; $uid=$user_id; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_group_joined');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, -$points);
  }
 }
 //*************** Detect New Group Reply ***************\\
 function wpachievements_my_bp_update_group_add( $content, $user_id, $group_id, $activity_id ){
  if( !empty($user_id) ){
   $type='cp_bp_group_reply'; $uid=$user_id; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_group_reply');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
  }
 }
 //*************** Detect New Friend Added ***************\\
 function wpachievements_my_bp_friend_add($friendship_id, $inviter_id, $invitee_id){
   $type='cp_bp_new_friend'; $uid=$inviter_id; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_new_friend');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);

   $type='cp_bp_new_friend'; $uid=$invitee_id; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_new_friend');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
 }
 //*************** Detect Friend Removed ***************\\
 function wpachievements_my_bp_friend_delete_add($friendship_id, $initiator_userid, $friend_userid){
   $type='cp_bp_lost_friend'; $uid=$initiator_userid; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_new_friend');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, -$points);

   $type='cp_bp_lost_friend'; $uid=$friend_userid; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_new_friend');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, -$points);
 }
 //*************** Detect New Group Topic ***************\\
 function wpachievements_my_bp_forum_new_topic_add(){
   $type='cp_bp_new_group_forum_topic'; $uid=''; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_new_group_forum_topic');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
 }
 //*************** Detect New Group Forum Post ***************\\
 function wpachievements_my_bp_forum_new_post_add(){
   $type='cp_bp_new_group_forum_post'; $uid=''; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_new_group_forum_post');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
 }
 //*************** Detect Edit Group Forum Post ***************\\
 function wpachievements_my_bp_forum_edit_topic_add(){
   $type='cp_bp_new_group_forum_post_edit'; $uid=''; $postid=''; $points=0;
   wpachievements_new_activity($type, $uid, $postid, $points);
 }
 //*************** Detect New Avatar ***************\\
 function wpachievements_my_bp_avatar_add(){
   global $bp, $wpdb;
   define('CUBEPTS3', $wpdb->prefix . 'cp');
   $avatar_results = $wpdb->get_var("SELECT COUNT(*) FROM ".CUBEPTS3." WHERE type='cp_bp_avatar_uploaded' AND uid = ".$bp->loggedin_user->id);
   if($avatar_results < 1){
     $type='cp_bp_avatar_uploaded'; $uid=$bp->loggedin_user->id; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_bp_avatar_uploaded');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }
 //*************** Detect New Group Avatar ***************\\
 function wpachievements_my_bp_group_avatar_add(){
   global $bp, $wpdb;
   define('BBCPDB', $wpdb->prefix . 'bp_activity');
   define('CUBEPTS3', $wpdb->prefix . 'cp');
   $bp_cp_xgroups_created = $wpdb->get_var("SELECT COUNT(*) FROM ".BBCPDB." WHERE type='created_group' AND user_id = ".$bp->loggedin_user->id);
   $group_avatar_results = $wpdb->get_var("SELECT COUNT(*) FROM ".CUBEPTS3." WHERE type='cp_bp_group_avatar_uploaded' AND uid = ".$bp->loggedin_user->id);
   if($bp_cp_xgroups_created > $group_avatar_results){
     $type='cp_bp_group_avatar_uploaded'; $uid=$bp->loggedin_user->id; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       $points = (int) wpachievements_get_site_option('wpachievements_bp_group_avatar_uploaded');
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }
 //*************** Detect User Sending a Message ***************\\
 function wpachievements_my_bp_pm(){
   $type='cp_bp_message_sent'; $uid=''; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     $points = (int) wpachievements_get_site_option('wpachievements_bp_message_sent');
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_bp_desc', 10, 6);
 function achievement_bp_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $group = __('groups', 'wpachievements');
    $update = __('updates', 'wpachievements');
    $reply = __('replies', 'wpachievements');
    $friend = __('friends', 'wpachievements');
    $friendship = __('friendships', 'wpachievements');
    $topic = __('topics', 'wpachievements');
    $post = __('posts', 'wpachievements');
    $avatar = __('avatars', 'wpachievements');
    $message = __('messages', 'wpachievements');
  } else{
    $group = __('group', 'wpachievements');
    $update = __('update', 'wpachievements');
    $reply = __('reply', 'wpachievements');
    $friend = __('friend', 'wpachievements');
    $friendship = __('friendship', 'wpachievements');
    $topic = __('topic', 'wpachievements');
    $post = __('post', 'wpachievements');
    $avatar = __('avatar', 'wpachievements');
    $message = __('message', 'wpachievements');
  }
  switch($type){
   case 'cp_bp_group_create': { $text = sprintf( __('for creating %s new %s', 'wpachievements'), $times, $group); } break;
   case 'cp_bp_group_delete': { $text = sprintf( __('for deleting %s %s', 'wpachievements'), $times, $group); } break;
   case 'cp_bp_update': { $text = sprintf( __('for adding %s %s', 'wpachievements'), $times, $update); } break;
   case 'cp_bp_group_joined': { $text = sprintf( __('for joining %s %s', 'wpachievements'), $times, $group); } break;
   case 'cp_bp_group_left': { $text = sprintf( __('for leaving %s %s', 'wpachievements'), $times, $group); } break;
   case 'cp_bp_reply': { $text = sprintf( __('for creating %s %s', 'wpachievements'), $times, $reply); } break;
   case 'cp_bp_group_reply': { $text = sprintf( __('for creating %s group %s', 'wpachievements'), $times, $reply); } break;
   case 'cp_bp_update_removed': { $text = sprintf( __('for removing %s %s', 'wpachievements'), $times, $update); } break;
   case 'cp_bp_new_friend': { $text = sprintf( __('for adding %s %s', 'wpachievements'), $times, $friend); } break;
   case 'cp_bp_lost_friend': { $text = sprintf( __('for cancelling %s %s', 'wpachievements'), $times, $friendship); } break;
   case 'cp_bp_new_group_forum_topic': { $text = sprintf( __('for creating %s group forum %s', 'wpachievements'), $times, $topic); } break;
   case 'cp_bp_new_group_forum_post': { $text = sprintf( __('for creating %s new group forum %s', 'wpachievements'), $times, $post); } break;
   case 'cp_bp_new_group_forum_post_edit': { $text = sprintf( __('for editting %s group forum %s', 'wpachievements'), $times, $post); } break;
   case 'cp_bp_avatar_uploaded': { $text = sprintf( __('for uploading %s new %s', 'wpachievements'), $times, $avatar); } break;
   case 'cp_bp_group_avatar_uploaded': { $text = sprintf( __('for uploading %s new group %s', 'wpachievements'), $times, $avatar); } break;
   case 'cp_bp_message_sent': { $text = sprintf( __('for sending %s %s', 'wpachievements'), $times, $message); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_bp_desc', 10, 3);
 function quest_bp_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $group = __('groups', 'wpachievements');
    $update = __('updates', 'wpachievements');
    $reply = __('replies', 'wpachievements');
    $friend = __('friends', 'wpachievements');
    $friendship = __('friendships', 'wpachievements');
    $topic = __('topics', 'wpachievements');
    $post = __('posts', 'wpachievements');
    $avatar = __('avatars', 'wpachievements');
    $message = __('messages', 'wpachievements');
  } else{
    $group = __('group', 'wpachievements');
    $update = __('update', 'wpachievements');
    $reply = __('reply', 'wpachievements');
    $friend = __('friend', 'wpachievements');
    $friendship = __('friendship', 'wpachievements');
    $topic = __('topic', 'wpachievements');
    $post = __('post', 'wpachievements');
    $avatar = __('avatar', 'wpachievements');
    $message = __('message', 'wpachievements');
  }
  switch($type){
   case 'cp_bp_group_create': { $text = sprintf( __('Create %s new %s', 'wpachievements'), $times, $group); } break;
   case 'cp_bp_group_delete': { $text = sprintf( __('Delete %s %s', 'wpachievements'), $times, $group); } break;
   case 'cp_bp_update': { $text = sprintf( __('Add %s %s', 'wpachievements'), $times, $update); } break;
   case 'cp_bp_group_joined': { $text = sprintf( __('Join %s %s', 'wpachievements'), $times, $group); } break;
   case 'cp_bp_group_left': { $text = sprintf( __('Leave %s %s', 'wpachievements'), $times, $group); } break;
   case 'cp_bp_reply': { $text = sprintf( __('Create %s %s', 'wpachievements'), $times, $reply); } break;
   case 'cp_bp_group_reply': { $text = sprintf( __('Create %s group %s', 'wpachievements'), $times, $reply); } break;
   case 'cp_bp_update_removed': { $text = sprintf( __('Remove %s %s', 'wpachievements'), $times, $update); } break;
   case 'cp_bp_new_friend': { $text = sprintf( __('Add %s %s', 'wpachievements'), $times, $friend); } break;
   case 'cp_bp_lost_friend': { $text = sprintf( __('Cancel %s %s', 'wpachievements'), $times, $friendship); } break;
   case 'cp_bp_new_group_forum_topic': { $text = sprintf( __('Create %s group forum %s', 'wpachievements'), $times, $topic); } break;
   case 'cp_bp_new_group_forum_post': { $text = sprintf( __('Create %s new group forum %s', 'wpachievements'), $times, $post); } break;
   case 'cp_bp_new_group_forum_post_edit': { $text = sprintf( __('Edit %s group forum %s', 'wpachievements'), $times, $post); } break;
   case 'cp_bp_avatar_uploaded': { $text = sprintf( __('Upload %s new %s', 'wpachievements'), $times, $avatar); } break;
   case 'cp_bp_group_avatar_uploaded': { $text = sprintf( __('Upload %s new group %s', 'wpachievements'), $times, $avatar); } break;
   case 'cp_bp_message_sent': { $text = sprintf( __('Send %s %s', 'wpachievements'), $times, $message); } break;
  }
  return $text;
 }

add_filter( 'wpachievements_get_sections_module', 'wpachievements_add_section_bp' );
function wpachievements_add_section_bp( $sections ) {
	$sections['bp'] = __( 'BuddyPress', 'wpachievements' );
	return $sections;
}

//*************** Admin Settings ***************\\
add_filter('wpachievements_achievements_modules_admin_settings', 'wpachievements_bp_admin', 10, 3);
function wpachievements_bp_admin($defaultsettings, $shortname, $current_section){
  if ( $current_section == 'bp' ) {
    $settings[] = array( 'title' => __( 'BuddyPress', 'wpachievements' ), 'type' => 'title', 'desc' => '', 'id' => 'BuddyPress_options' );

    $settings[] = array(
            'title'   => __( 'User Adding Avatar', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user adds a avatar.', 'wpachievements' ),
            'id'      => $shortname.'_bp_avatar_uploaded',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Adding Friends', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user adds a friend.', 'wpachievements' ),
            'id'      => $shortname.'_bp_new_friend',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Sending Messages', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user adds a message.', 'wpachievements' ),
            'id'      => $shortname.'_bp_message_sent',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Adding Update', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user adds an update.', 'wpachievements' ),
            'id'      => $shortname.'_bp_update_points',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Adding Reply', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user adds a reply.', 'wpachievements' ),
            'id'      => $shortname.'_bp_reply_points',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Adding Groups', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user adds a group.', 'wpachievements' ),
            'id'      => $shortname.'_bp_group_create',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Adding Group Avatar', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user adds a group avatar.', 'wpachievements' ),
            'id'      => $shortname.'_bp_group_avatar_uploaded',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Joins Groups', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user joins a group.', 'wpachievements' ),
            'id'      => $shortname.'_bp_group_joined',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Adding Group Topic', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user adds a group topic.', 'wpachievements' ),
            'id'      => $shortname.'_bp_new_group_forum_topic',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Adding Group Reply', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user adds a group reply.', 'wpachievements' ),
            'id'      => $shortname.'_bp_group_reply',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array(
            'title'   => __( 'User Adding Group Forum Post', 'wpachievements' ),
            'desc'    => __( 'Points awarded when the user adds a group forum post.', 'wpachievements' ),
            'id'      => $shortname.'_bp_new_group_forum_post',
            'type'    => 'text',
            'default' => '0',
          );

    $settings[] = array( 'type' => 'sectionend', 'id' => 'BuddyPress_options');

    return $settings;
  /**
  * If not, return the standard settings
  **/
	} else {
		return $defaultsettings;
	}
}

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_bp_admin_events', 10);
 function achievement_bp_admin_events(){
   echo '<optgroup label="BuddyPress User Events">
     <option value="cp_bp_avatar_uploaded">'.__('The user uploads an avatar', 'wpachievements').'</option>
     <option value="cp_bp_message_sent">'.__('The user sends a message', 'wpachievements').'</option>
     <option value="cp_bp_new_friend">'.__('The user adds a new friend', 'wpachievements').'</option>
     <option value="cp_bp_lost_friend">'.__('The user removes a friend', 'wpachievements').'</option>
     <option value="cp_bp_update">'.__('The user adds an update', 'wpachievements').'</option>
     <option value="cp_bp_reply">'.__('The user creates a reply', 'wpachievements').'</option>
     <option value="cp_bp_update_removed">'.__('The user removes an update', 'wpachievements').'</option>
   </optgroup>
   <optgroup label="BuddyPress Group Events">
     <option value="cp_bp_group_create">'.__('The user creates a group', 'wpachievements').'</option>
     <option value="cp_bp_group_delete">'.__('The user deletes a group', 'wpachievements').'</option>
     <option value="cp_bp_group_joined">'.__('The user joins a group', 'wpachievements').'</option>
     <option value="cp_bp_group_left">'.__('The user leaves a group', 'wpachievements').'</option>
     <option value="cp_bp_group_reply">'.__('The user creates a group reply', 'wpachievements').'</option>
     <option value="cp_bp_group_avatar_uploaded">'.__('The user uploads a group avatar', 'wpachievements').'</option>
   </optgroup>
   <optgroup label="BuddyPress Forum Events">
     <option value="cp_bp_new_group_forum_topic">'.__('The user creates a new group forum topic', 'wpachievements').'</option>
     <option value="cp_bp_new_group_forum_post">'.__('The user creates a new group forum post', 'wpachievements').'</option>
     <option value="cp_bp_new_group_forum_post_edit">'.__('The user edits a group forum post', 'wpachievements').'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_bp_admin_triggers', 1, 10);
 function achievement_bp_admin_triggers($trigger){

   switch($trigger){
     case 'cp_bp_avatar_uploaded': { $trigger = __('The user uploads an avatar', 'wpachievements'); } break;
     case 'cp_bp_message_sent': { $trigger = __('The user sends a message', 'wpachievements'); } break;
     case 'cp_bp_new_friend': { $trigger = __('The user adds a new friend', 'wpachievements'); } break;
     case 'cp_bp_lost_friend': { $trigger = __('The user removes a friend', 'wpachievements'); } break;
     case 'cp_bp_update': { $trigger = __('The user adds an update', 'wpachievements'); } break;
     case 'cp_bp_reply': { $trigger = __('The user creates a reply', 'wpachievements'); } break;
     case 'cp_bp_update_removed': { $trigger = __('The user removes an update', 'wpachievements'); } break;
     case 'cp_bp_group_create': { $trigger = __('The user creates a group', 'wpachievements'); } break;
     case 'cp_bp_group_delete': { $trigger = __('The user deletes a group', 'wpachievements'); } break;
     case 'cp_bp_group_joined': { $trigger = __('The user joins a group', 'wpachievements'); } break;
     case 'cp_bp_group_left': { $trigger = __('The user leaves a group', 'wpachievements'); } break;
     case 'cp_bp_group_reply': { $trigger = __('The user creates a group reply', 'wpachievements'); } break;
     case 'cp_bp_group_avatar_uploaded': { $trigger = __('The user uploads a group avatar', 'wpachievements'); } break;
     case 'cp_bp_new_group_forum_topic': { $trigger = __('The user creates a new group forum topic', 'wpachievements'); } break;
     case 'cp_bp_new_group_forum_post': { $trigger = __('The user creates a new group forum post', 'wpachievements'); } break;
     case 'cp_bp_new_group_forum_post_edit': { $trigger = __('The user edits a group forum post', 'wpachievements'); } break;
   }

   return $trigger;

 }

 /**
  *************************************************
  *    A D D I T I O N A L   F U N C T I O N S    *
  *************************************************
  */
  //*************** Actions ***************\\
  add_action('wpachievements_after_new_achievement', 'show_ach_in_stream', 10, 3);
  add_action('wpachievements_after_new_custom_achievement', 'show_custom_ach_in_stream', 10, 6);
  add_action('wpachievements_after_new_quest', 'show_quest_in_stream', 10, 3);
  if( !function_exists(WPACHIEVEMENTS_USERPRO) ){
    add_action( 'bp_before_member_header_meta', 'wpachievements_bp_profile_achievements', 500 );
  }
  add_action('wpachievements_new_rank_gained', 'wpachievements_bp_profile_ranks', 10, 1);
  add_action( 'bp_before_member_header_meta', 'wpachievements_bp_profile' );
  //*************** Show achievement in steam ***************\\
  function show_ach_in_stream( $user_id, $ach_id, $achievement ){
   if(function_exists('bp_activity_add')){
     $message = '<img src="'.$achievement[6].'" alt="" class="achieves_gained_badge_feed" /> '. sprintf( __('I just gained the achievement %s and got %s points!', 'wpachievements'), '<strong>'.stripslashes($achievement[0]).'</strong>', '<strong>'.$achievement[2].'</strong>');
     $action  = bp_core_get_userlink($user_id) .' '. __('I just gained a new achievement!!!', 'wpachievements');
     bp_activity_add( array( 'user_id' => $user_id, 'content' => $message, 'action' => $action, 'component' => 'profile', 'type' => 'new_achievement' ));
   }
  }
  function show_custom_ach_in_stream( $type, $user_id, $postID, $points, $usersrank, $achievement ){
   if(function_exists('bp_activity_add')){
     $message = '<img src="'.$achievement[6].'" alt="" class="achieves_gained_badge_feed" /> '. sprintf( __('I just gained the achievement %s and got %s points!', 'wpachievements'), '<strong>'.stripslashes($achievement[0]).'</strong>', '<strong>'.$achievement[2].'</strong>');
     $action  = bp_core_get_userlink($user_id) .' '. __('I just gained a new achievement!!!', 'wpachievements');
     bp_activity_add( array( 'user_id' => $user_id, 'content' => $message, 'action' => $action, 'component' => 'profile', 'type' => 'new_achievement' ));
   }
  }
  function show_quest_in_stream( $user_id, $quest_ID, $quest ){
   if(function_exists('bp_activity_add')){
     $message = '<img src="'.$quest[6].'" alt="" class="achieves_gained_badge_feed" /> '. sprintf( __('I just gained the quest %s and got %s points!', 'wpachievements'), '<strong>'.stripslashes($quest[0]).'</strong>', '<strong>'.$quest[2].'</strong>');
     $action  = bp_core_get_userlink($user_id) .' '. __('I just gained a new quest!!!', 'wpachievements');
     bp_activity_add( array( 'user_id' => $user_id, 'content' => $message, 'action' => $action, 'component' => 'profile', 'type' => 'new_achievement' ));
   }
  }

  //*************** Show achievement in steam ***************\\
  function wpachievements_bp_profile_achievements(){

    $show = true;
    $show = apply_filters( 'wpachievements_before_bp_profile_achievements', $show );

    if( $show ){
      global $bp;
      $userachievement = get_user_meta( $bp->displayed_user->id, 'achievements_gained', true );
      echo '<div id="wpa_bp_achievements_hold">';
       echo '<h5 id="my_achievements_title">'.__('My Achievements', 'wpachievements').':</h5>';
       $already_counted = array();
       $sim_ach = get_option('wpachievements_sim_ach');
       if( !empty($userachievement) && $userachievement != '' ){
        $args = array(
          'post_type' => 'wpachievements',
          'post_status' => 'publish',
          'post__in' => $userachievement,
          'posts_per_page' => -1
        );
        $achievement_query = new WP_Query( $args );
        if( $achievement_query->have_posts() ){
          while( $achievement_query->have_posts() ){
            $achievement_query->the_post();
            $ach_ID = get_the_ID();
            $ach_title = get_the_title();
            $ach_desc = get_the_content();
            $ach_data = $ach_title.': '.$ach_desc;
            $ach_img = get_post_meta( $ach_ID, '_achievement_image', true );
            $ach_points = get_post_meta( $ach_ID, '_achievement_points', true );
            $ach_woopoints = get_post_meta( $ach_ID, '_achievement_woo_points', true );
            $ach_rank = get_post_meta( $ach_ID, '_achievement_rank', true );
            $ach_occurences = get_post_meta( $ach_ID, '_achievement_occurrences', true );
            $type = 'wpachievements_achievement_'.get_post_meta( $ach_ID, '_achievement_type', true );
            if($sim_ach == 'yes'){
              if( !array_key_exists($type,$already_counted) ){
                if( $type != 'wpachievements_achievement_custom_achievement' ){
                  $already_counted[$type] = $ach_occurences;
                }
                echo '<img src="'.$ach_img.'" width="30" class="achieves_gained_badge" alt="'.stripslashes($ach_title).__(' Icon','wpachievements').'" title="'.stripslashes($ach_title).': '.stripslashes(strip_tags($ach_desc)).'" />';
              } elseif( $already_counted[$type] <= $ach_occurences ){
                if( $type != 'wpachievements_achievement_custom_achievement' ){
                  $already_counted[$type] = $ach_occurences;
                }
                echo '<img src="'.$ach_img.'" width="30" class="achieves_gained_badge" alt="'.stripslashes($ach_title).': '.$ach_desc.'" title="'.stripslashes($ach_title).': '.stripslashes(strip_tags($ach_desc)).'" />';
              }
            } else{
              echo '<img src="'.$ach_img.'" width="30" class="achieves_gained_badge" alt="'.stripslashes($ach_title).__(' Icon','wpachievements').'" title="'.stripslashes($ach_title).': '.stripslashes(strip_tags($ach_desc)).'" />';
            }
           }
        }
        wp_reset_postdata();
       }
	  	 echo '<br /><div class="clear"></div></div>';
    }
   }
   //*************** Show new rank in steam ***************\\
   function wpachievements_bp_profile_ranks($uid){
    if(!empty($uid)){
     $usersrank = wpachievements_getRank($uid);
     $userlink = bp_core_get_userlink($uid);
     $message = sprintf( __('I was just promoted to the rank of %s', 'wpachievements'), '<strong>'.$usersrank.'</strong>');
     $action  = sprintf( __('%s gained a new rank', 'wpachievements'), $userlink);
     bp_activity_add( array( 'user_id' => $uid, 'content' => $message, 'action' => $action, 'component' => 'profile', 'type' => 'new_ranking' ));
    }
   }
   //*************** Show rank stats in profile ***************\\
   function wpachievements_bp_profile(){

    $show = true;
    $show = apply_filters( 'wpachievements_before_bp_profile_rank', $show );

    if( $show ){
      global $bp;
      echo '<span class="cupepoints_buddypress_rank activity"><strong>'.__('Rank', 'wpachievements').':</strong> '.wpachievements_getRank($bp->displayed_user->id).'</span>';
    }
  }
?>