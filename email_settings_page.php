<?php
function email_settings_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }
    if (isset($_GET['settings-updated'])) {
        add_settings_error('notification_settings_messages', 'notification_settings_message', 'Paramètres enregistrés', 'updated');
    }
    settings_errors('notification_settings_messages');
    $options = get_option('notification_settings');
    $post_types = get_post_types(['public' => true], 'objects');
    wp_enqueue_script('notification-user-settings', plugin_dir_url(__FILE__) . 'js/settings.js', array('jquery'), '1.0.0', true);
    wp_localize_script('notification-user-settings', 'notification_user_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'saved_meta_key' => isset($options['meta_key']) ? $options['meta_key'] : ''
    ));
    ?>
    
    <div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <p class="starting-mobule">To get started, insert the shortcode <b>[notification_bell]</b> wherever you want.</p>
    
    <form action="options.php" method="post" id="settings-form">
        <?php
        settings_fields('notification_settings');
        do_settings_sections('notification_settings');
        
        echo '<h2 class="settings-header">Select the post type *</h2>';
        echo '<p class="settings-description">The selected post should be the one that allows message exchange between users</p>';
        echo '<select name="notification_settings[post_type]" required>';
        foreach ($post_types as $post_type) {
            $selected = selected($options['post_type'], $post_type->name, false);
            echo "<option value='" . esc_attr($post_type->name) . "' $selected>" . esc_html($post_type->labels->singular_name) . "</option>";
        }
        echo '</select>';
        ?>
        
        <div class="meta-select-container meta-select-has-search" id="meta-select-meta-key">
            <h2 class="settings-header">Meta Key Search *</h2>
            <p class="settings-description">The selected meta field should be the one that dynamically adds the user ID who is supposed to receive the notification</p>
            <input class="meta-select-search" type="text" placeholder="Enter at least 3 characters" minlength="3" autocomplete="off" required />
            <svg class="meta-select-icon" viewBox="0 0 16 16" width="13" height="13">
              
            </svg>
            <ul class="meta-select-results"></ul>
            <input type="hidden" name="notification_settings[meta_key]" id="selected_meta_key" value="<?php echo esc_attr($options['meta_key']); ?>" required />
        </div>

        <h2 class="settings-header">Select the redirection page *</h2>
        <p class="settings-description">This page will be the page where the user, who receives the notification, will be redirected to.<br>For example, your page that displays the received messages.</p>
        <?php
        
        $all_pages = get_pages();
        ?>
       
        <select name="notification_settings[redirection_page]" id="redirection_page" required>
            <?php foreach ($all_pages as $page) : ?>
                <option value="<?php echo get_page_link($page->ID); ?>" 
                    <?php selected($options['redirection_page'], get_page_link($page->ID)); ?>>
                    <?php echo $page->post_title; ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <h2 class="settings-header">Notification Icon and Counter Colors</h2>
        <p class="settings-description">Choose the colors for the notification icon and counter.</p>
    
        <p>
            <label for="notification_bell_color">Notification Bell Color: </label>
            <input type="color" id="notification_bell_color" name="notification_settings[bell_color]" value="<?php echo esc_attr($options['bell_color']); ?>" />
        </p>
    
        <p>
            <label for="notification_counter_bg_color">Notification Counter Background Color: </label>
            <input type="color" id="notification_counter_bg_color" name="notification_settings[counter_bg_color]" value="<?php echo esc_attr($options['counter_bg_color']); ?>" />
        </p>
    
        <p>
            <label for="notification_counter_text_color">Notification Counter Text Color: </label>
            <input type="color" id="notification_counter_text_color" name="notification_settings[counter_text_color]" value="<?php echo esc_attr($options['counter_text_color']); ?>" />
        </p>
    
        <?php submit_button('Save Settings'); ?>
    </form>
    <p>To learn more about how the plugin works, you can <a href="https://github.com/Artnco/user-notification/tree/main" target="_blank">click here</a>.</p>
</div>

<?php
}
function notification_meta_key_search_callback() {
    global $wpdb;

    $search_str = isset($_POST['search_str']) ? sanitize_text_field($_POST['search_str']) : '';
    if (strlen($search_str) < 3) {
        wp_send_json_error('Please enter at least 3 characters.');
    }

    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT DISTINCT meta_key 
         FROM $wpdb->postmeta 
         WHERE meta_key LIKE %s", 
        '%' . $wpdb->esc_like($search_str) . '%'
    ));

    if (!empty($results)) {
        wp_send_json_success($results);
    } else {
        wp_send_json_error('No results found.');
    }
}

add_action('wp_ajax_notification_meta_key_search', 'notification_meta_key_search_callback');
