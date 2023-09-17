<?php

function send_notification_email($post_ID, $post, $update) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if ($update) {
        return;
    }

    $notification_settings = get_option('notification_settings', array('meta_key' => 'id-user'));
    $email_settings = get_option('notification_email_settings');
    $meta_key = $notification_settings['meta_key'];
    
    $post_type_setting = isset($notification_settings['post_type']) ? $notification_settings['post_type'] : null;
    if ($post->post_type !== $post_type_setting) {
        return;
    }

    if($meta_key) {
        $user_id_meta_value = get_post_meta($post_ID, $meta_key, true);

        if($user_id_meta_value) {
            
            $user_info = get_userdata($user_id_meta_value);
            if ($user_info) {
                $user_email = $user_info->user_email;

                
                $to = $user_email;
                $subject = $email_settings['email_subject'];
                $message = $email_settings['email_body'];
                
               
                wp_mail($to, $subject, $message);
            }
        } else {
            wp_schedule_single_event(time() + 10, 'retry_send_notification_email', array($post_ID, $meta_key));
        }
    }
}

function retry_send_notification_email($post_ID, $meta_key) {
    $user_id_meta_value = get_post_meta($post_ID, $meta_key, true);
    if(!empty($user_id_meta_value)) {
        
        $email_settings = get_option('notification_email_settings');
        $user_info = get_userdata($user_id_meta_value);
        if ($user_info) {
            $user_email = $user_info->user_email;
            
           
            $to = $user_email;
            $subject = $email_settings['email_subject'];
            $message = $email_settings['email_body'];
            
           
            wp_mail($to, $subject, $message);
        }
    }
}

add_action('retry_send_notification_email', 'retry_send_notification_email', 10, 2);
add_action('save_post', 'send_notification_email', 99, 3);
