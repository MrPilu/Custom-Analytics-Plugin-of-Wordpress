jQuery(document).ready(function($) {
    var startTime = new Date().getTime();
    var pageUrl = window.location.href;
    var userId = 0; // Default ID for guest users
    var userIp = ''; // Empty IP address for guest users
    var userBrowser = ''; // Empty browser details for guest users

    // Check if the user is logged in
    if (custom_analytics_data.current_user_id !== '0') {
        userId = custom_analytics_data.current_user_id;
    }

    // Check if the user IP address is available
    if (custom_analytics_data.user_ip !== '') {
        userIp = custom_analytics_data.user_ip;
    }

    // Check if the user browser details are available
    if (custom_analytics_data.user_browser !== '') {
        userBrowser = custom_analytics_data.user_browser;
    }

    // Track page view on document load
    $.ajax({
        url: custom_analytics_data.ajax_url,
        type: 'POST',
        data: {
            action: 'custom_analytics_track_page_view',
            page_url: pageUrl,
            start_time: startTime,
            user_id: userId,
            user_ip: userIp,
            user_browser: userBrowser
        }
    });

    // Track time spent periodically
    var updateTimeSpent = function() {
        var endTime = new Date().getTime();
        var duration = endTime - startTime;

        // Send the tracking data
        $.ajax({
            url: custom_analytics_data.ajax_url,
            type: 'POST',
            data: {
                action: 'custom_analytics_track_time_spent',
                page_url: pageUrl,
                start_time: startTime,
                end_time: endTime,
                duration: duration,
                user_id: userId,
                user_ip: userIp,
                user_browser: userBrowser
            },
            async: true // Allow the request to be asynchronous
        });
    };

    // Update time spent every 10 seconds (adjust as needed)
    setInterval(updateTimeSpent, 10000);

    // Track time spent when the user leaves the page or closes the tab
    $(window).on('beforeunload', function() {
        updateTimeSpent();
    });
});
