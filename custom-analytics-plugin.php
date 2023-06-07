<?php
/*
Plugin Name: Custom Analytics Plugin
Description: Track user activity on your website.
Version: 1.0
Author: Your Name
Author URI: Your Website
*/

// Enqueue the JavaScript file and localize script
function custom_analytics_enqueue_scripts() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'custom-analytics-script', plugins_url( '/custom-analytics.js', __FILE__ ), array( 'jquery' ), '1.0', true );
    wp_localize_script( 'custom-analytics-script', 'custom_analytics_data', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'current_user_id' => get_current_user_id(),
        'user_ip' => $_SERVER['REMOTE_ADDR'],
        'user_browser' => $_SERVER['HTTP_USER_AGENT']
    ) );
    
}
add_action( 'wp_enqueue_scripts', 'custom_analytics_enqueue_scripts' );

// Track page view and store user information
function custom_analytics_track_page_view() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_analytics'; // Replace with your table name

    $page_url = sanitize_text_field( $_POST['page_url'] );
    $start_time = absint( $_POST['start_time'] );
    $user_id = absint( $_POST['user_id'] );
    $user_ip = sanitize_text_field( $_POST['user_ip'] );
    $user_browser = sanitize_text_field( $_POST['user_browser'] );

    $wpdb->insert(
        $table_name,
        array(
            'page_url' => $page_url,
            'start_time' => $start_time,
            'end_time' => 0, // Set the initial end time to 0
            'duration' => 0, // Set the initial duration to 0
            'user_id' => $user_id,
            'user_ip' => $user_ip,
            'user_browser' => $user_browser
        ),
        array(
            '%s',
            '%d',
            '%d',
            '%d',
            '%d',
            '%s',
            '%s'
        )
    );

    wp_die();
}
add_action( 'wp_ajax_custom_analytics_track_page_view', 'custom_analytics_track_page_view' );
add_action( 'wp_ajax_nopriv_custom_analytics_track_page_view', 'custom_analytics_track_page_view' );

// Track time spent and update database record
function custom_analytics_track_time_spent() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_analytics'; // Replace with your table name

    $page_url = sanitize_text_field( $_POST['page_url'] );
    $start_time = absint( $_POST['start_time'] );
    $end_time = absint( $_POST['end_time'] );
    $duration = absint( $_POST['duration'] );
    $user_id = absint( $_POST['user_id'] );

    $wpdb->update(
        $table_name,
        array(
            'end_time' => $end_time,
            'duration' => $duration
        ),
        array(
            'page_url' => $page_url,
            'start_time' => $start_time,
            'user_id' => $user_id
        ),
        array(
            '%d',
            '%d'
        ),
        array(
            '%s',
            '%d',
            '%d'
        )
    );

    wp_die();
}
add_action( 'wp_ajax_custom_analytics_track_time_spent', 'custom_analytics_track_time_spent' );
add_action( 'wp_ajax_nopriv_custom_analytics_track_time_spent', 'custom_analytics_track_time_spent' );

// Create the database table on plugin activation
function custom_analytics_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_analytics'; // Replace with your table name

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT(11) NOT NULL AUTO_INCREMENT,
        page_url VARCHAR(255) NOT NULL,
        start_time BIGINT(20) NOT NULL,
        end_time BIGINT(20) NOT NULL,
        duration BIGINT(20) NOT NULL,
        user_id BIGINT(20) NOT NULL,
        user_ip VARCHAR(255),
        user_browser VARCHAR(255),
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook( __FILE__, 'custom_analytics_create_table' );


    