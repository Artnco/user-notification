<?php

// Ajout de l'action AJAX pour les utilisateurs connectés
add_action('wp_ajax_mark_messages_as_read', 'mark_messages_as_read_callback');

function mark_messages_as_read_callback() {
    if (!isset($_POST['user_id'])) {
        wp_send_json_error('User ID non fourni.');
    }

    $user_id = intval($_POST['user_id']);

    if ($user_id <= 0) {
        wp_send_json_error('User ID invalide.');
    }
    
    $notification_settings = get_option('notification_settings', array('post_type' => 'messages', 'meta_key' => 'id-user'));
    
    $args = array(
        'post_type' => $notification_settings['post_type'],
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => $notification_settings['meta_key'],
                'value' => $user_id,
                'compare' => '=',
            ),
            array(
                'key' => 'message_read',
                'compare' => 'NOT EXISTS',
            ),
        ),
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            update_post_meta(get_the_ID(), 'message_read', true);
        }
    } else {
        wp_send_json_success('Aucun nouveau message');
        return;
    }

    wp_send_json_success('Messages marqués comme lus.');

    wp_reset_postdata();
}

add_action('wp_ajax_check_unread_messages_count', 'check_unread_messages_count_callback');
add_action('wp_ajax_nopriv_check_unread_messages_count', 'check_unread_messages_count_callback');

function check_unread_messages_count_callback() {
    if (!isset($_POST['user_id'])) {
        wp_send_json_error('User ID non fourni.');
    }

    $user_id = intval($_POST['user_id']);
    if ($user_id) {
        $count = notification_user_get_notification_count($user_id);
        wp_send_json_success($count);
    } else {
        wp_send_json_error('User ID invalide.');
    }
}






