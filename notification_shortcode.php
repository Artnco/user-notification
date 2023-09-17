<?php

function notification_user_get_notification_count($user_id) {
    $notification_settings = get_option('notification_settings', array('post_type' => 'messages', 'meta_key' => 'id-user'));

    $args = array(
        'post_type' => $notification_settings['post_type'],
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => $notification_settings['meta_key'],
                'value' => $user_id,
                'compare' => '=',
            ),
            'message_read_clause' => array(
                'relation' => 'OR',
                array(
                    'key' => 'message_read',
                    'compare' => 'NOT EXISTS',
                ),
                array(
                    'key' => 'message_read',
                    'value' => '1',
                    'compare' => '!=',
                ),
            ),
        ),
    );
    $query = new WP_Query($args);
    return $query->found_posts;
}



function notification_user_bell_shortcode() {
    $user_id = get_current_user_id();
    $count = notification_user_get_notification_count($user_id);
    $options = get_option('notification_settings');
    $redirection_page = !empty($options['redirection_page']) ? $options['redirection_page'] : get_home_url();
    $bell_color = !empty($options['bell_color']) ? $options['bell_color'] : '#000000'; 
    $counter_bg_color = !empty($options['counter_bg_color']) ? $options['counter_bg_color'] : '#000000'; 
    $counter_text_color = !empty($options['counter_text_color']) ? $options['counter_text_color'] : '#ffffff';
    

    ob_start();
    ?>
    <a href="<?php echo esc_url($redirection_page); ?>" class="notification-link">
        <div class="notification-bell custom-notification-bell" data-user-id="<?php echo get_current_user_id(); ?>" style="color: <?php echo esc_attr($bell_color); ?>;">
            <span class="dashicons dashicons-bell"></span>
            <?php if ($count > 0) : ?>
                <span id="notification-count" class="notification-count custom-notification-count" style="background-color: <?php echo esc_attr($counter_bg_color); ?>; color: <?php echo esc_attr($counter_text_color); ?>;"><?php echo $count; ?></span>
            <?php endif; ?>
        </div>
    </a>
    <?php
    return ob_get_clean();
}

add_shortcode('notification_bell', 'notification_user_bell_shortcode');
