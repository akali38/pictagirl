<?php
flush_rewrite_rules();

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
global $wpdb;
$table_name = $wpdb->prefix . 'monstro_views';
$sql = "CREATE TABLE $table_name (
      post_id int(11) NOT NULL,
      user_ip varchar(15) NOT NULL,
      user_id int(11),
      PRIMARY KEY  (post_id,user_ip)
    );
";
@dbDelta( $sql );

$table_name = $wpdb->prefix . 'monstro_votes';
$sql = "CREATE TABLE $table_name (
    post_id int(11) NOT NULL,
    user_ip varchar(15) NOT NULL,
    user_id int(11),
    vote int(1) NOT NULL,
    PRIMARY KEY  (post_id,user_ip)
  );
";
@dbDelta( $sql );