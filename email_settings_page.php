<?php

function notification_email_settings_page_html() {
    // Vérifiez les permissions de l'utilisateur
    if (!current_user_can('manage_options')) {
        return;
    }

    // Récupérer les options existantes
    $options = get_option('notification_email_settings');

    // HTML pour la page de paramètres
    echo '<div class="wrap notif_wrap">';
    echo '<h1>Email Settings</h1>';
    
    echo '<form method="post" action="options.php" class="notif_form">';
    
    settings_fields('notification_email_settings');
    do_settings_sections('notification_email_settings');
    
    // Champ pour le titre de l'email
    echo '<div class="notif_form-group">';
    echo '<label for="email_subject" class="notif_label">Email Subject</label>';
    echo '<input type="text" id="email_subject" name="notification_email_settings[email_subject]" value="' . esc_attr($options['email_subject']) . '" class="notif_input" />';
    echo '</div>';

    // Champ pour le contenu de l'email
    echo '<div class="notif_form-group">';
    echo '<label for="email_body" class="notif_label">Email Body</label>';
    echo '<textarea id="email_body" name="notification_email_settings[email_body]" rows="10" cols="50" class="notif_textarea">' . esc_textarea($options['email_body']) . '</textarea>';
    echo '</div>';
    
    submit_button('Save Settings');
    
    echo '</form>';
    echo '</div>';
    
}
