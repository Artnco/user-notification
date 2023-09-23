<?php
/*
Plugin Name: Notification User
Description: Creation of a notification system between users
Version: 1.0.0
Author: Art&co
Author URI: Art&co
License: GPL2
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit;
}

function notification_user_enqueue_scripts() {
    wp_enqueue_style('notification-user-style', plugin_dir_url(__FILE__) . 'css/style.css', array(), '1.0.0');
    wp_enqueue_script('notification-user-settings', plugin_dir_url(__FILE__) . 'js/settings.js', array('jquery'), '1.0.0', true);

    wp_localize_script('notification-user-settings', 'notification_user_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

add_action('wp_enqueue_scripts', 'notification_user_enqueue_scripts');
add_action('admin_enqueue_scripts', 'notification_user_enqueue_scripts');

require_once(plugin_dir_path(__FILE__) . 'notification_shortcode.php');
require_once plugin_dir_path(__FILE__) . 'notification_ajax_handlers.php';
require_once plugin_dir_path(__FILE__) . 'notification-settings-page.php';
require_once plugin_dir_path(__FILE__) . 'email_settings_page.php';
require_once plugin_dir_path(__FILE__) . 'notification-emails.php';

function notification_settings_menu() {
    add_menu_page(
        'Notifications',
        'Notifications settings',
        'manage_options',
        'notification-settings',
        'notification_settings_page_html',
        'dashicons-bell',
        '',
        100
    );
}
add_action('admin_menu', 'notification_settings_menu');

function notification_email_settings_menu() {
    add_submenu_page(
        'notification-settings',
        'Email Settings',
        'Email Settings',
        'manage_options',
        'notification-email-settings',
        'notification_email_settings_page_html', 
        5
    );
}
add_action('admin_menu', 'notification_email_settings_menu');

function register_notification_email_settings() {
    register_setting('notification_email_settings', 'notification_email_settings');
}
add_action('admin_init', 'register_notification_email_settings');

function add_notification_settings_link($links) {
    $settings_link = '<a href="admin.php?page=notification-settings">' . __('Réglages', 'notification-user') . '</a>';
    array_push($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'add_notification_settings_link');
