jQuery(document).ready(function($) {
    function resetNotificationCount() {
        var user_id = $('.notification-bell').data('user-id');
        $.ajax({
            url: notification_user_ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'mark_messages_as_read',
                user_id: user_id,
            },
            success: function(response) {
                if (response.success) {
                    $('.notification-count').hide();
                }
            },
            error: function(error) {
                console.error('An error occurred while updating the messages.');
            },
        });
    }

    // Récupérez le chemin d'accès à la page sélectionnée depuis l'option de la base de données
    var selectedPagePath = "<?php echo parse_url(get_option('notification_redirection_page'), PHP_URL_PATH); ?>";
    
    // Si nous sommes sur la page sélectionnée, réinitialisez le compteur de notifications
    if (window.location.pathname === selectedPagePath) {
        resetNotificationCount();
    }

    // Ajoutez un gestionnaire de clics qui réinitialise le compteur lorsque l'utilisateur clique sur la notification bell
    $('.notification-bell').on('click', function() {
        resetNotificationCount();
    });
});

(function($) {
    function getNotificationCount() {
        // N'exécutez pas sur la page sélectionnée
        var selectedPagePath = "<?php echo parse_url(get_option('notification_redirection_page'), PHP_URL_PATH); ?>";
        if (window.location.pathname === selectedPagePath) return;


    var $notifBell = $('.notification-bell');
    var user_id = $notifBell.data('user-id');

    if (user_id) { // This line checks whether the user ID is defined and non-null
        $.ajax({
            url: notification_user_ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'check_unread_messages_count',
                user_id: user_id,
            },
            success: function(response) {
                if (response.success) {
                    var $notifCount = $notifBell.find('.notification-count');

                    if (response.data > 0) {
                        if (!$notifCount.length) { // If the .notification-count element does not exist, create it
                            $notifCount = $('<span/>', {
                                id: 'notification-count',
                                'class': 'notification-count',
                            }).appendTo($notifBell);
                        }
                        $notifCount.text(response.data);
                        $notifCount.show();
                    } else if ($notifCount.length) { // If the .notification-count element exists and the count is zero, remove it
                        $notifCount.remove();
                    }
                }
            },
            error: function() {
                console.error('An error occurred while retrieving the number of notifications.');
            },
        });
    } else {
        console.error('User ID not defined.');
    }
}


    // Call the function every 2 seconds (or any other interval of your choice)
    setInterval(getNotificationCount, 2000);
})(jQuery);




jQuery(document).ready(function($) {
    var savedMetaKey = notification_user_ajax_object.saved_meta_key;
    
    if (savedMetaKey) {
        $('.meta-select-search').val(savedMetaKey).after('<span class="meta-select-clear">✕</span>');
        $('#selected_meta_key').val(savedMetaKey);
    }

    var searchTimeout;
    $('.meta-select-search').on('input', function() {
        var query = $(this).val();
        clearTimeout(searchTimeout);
        if (query.length >= 3) {
            $('.meta-select-results').empty().append('<li class="meta-select-group"><strong class="meta-select-label">Recherche en cours...</strong></li>');
            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: notification_user_ajax_object.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'notification_meta_key_search',
                        search_str: query
                    },
                    success: function(response) {
                        $('.meta-select-results').empty();
                        if (response.success) {
                            var resultsHTML = '<li class="meta-select-group"><strong class="meta-select-label">Champ personnalisé de WordPress</strong><ul class="meta-select-group">';
                            response.data.forEach(function(item) {
                                resultsHTML += '<li class="meta-select-option">' + item.meta_key + '</li>';
                            });
                            resultsHTML += '</ul></li>';
                            $('.meta-select-results').append(resultsHTML);
                        } else {
                            $('.meta-select-results').append('<li class="meta-select-group"><strong class="meta-select-label">Aucun résultat trouvé.</strong></li>');
                        }
                    }
                });
            }, 500);
        } else {
            $('.meta-select-results').empty();
        }
    });

    $('.meta-select-results').on('click', '.meta-select-option', function() {
        var selectedKey = $(this).text();
        $('.meta-select-search').val(selectedKey).after('<span class="meta-select-clear">✕</span>');
        $('#selected_meta_key').val(selectedKey);
        $('.meta-select-results').empty();
    });

    $('body').on('click', '.meta-select-clear', function() {
        $('.meta-select-search').val('');
        $('#selected_meta_key').val('');
        $(this).remove();
    });
});





