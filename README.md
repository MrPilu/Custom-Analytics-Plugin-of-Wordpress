# Custom Analytics Plugin

Track user activity on your website with the Custom Analytics Plugin.

## Description

The Custom Analytics Plugin allows you to track user activity on your WordPress website. It captures information such as page views, time spent on each page, and user details like IP address and browser. This information can be useful for analyzing user behavior and optimizing your website's performance.

## Installation

1. Download the plugin zip file from the [GitHub repository](https://github.com/MrPilu/Custom-Analytics-Plugin-of-Wordpress.git).
2. Log in to your WordPress admin dashboard.
3. Navigate to "Plugins" > "Add New".
4. Click on the "Upload Plugin" button.
5. Choose the plugin zip file and click "Install Now".
6. After installation, click on the "Activate Plugin" button.

## Usage

The Custom Analytics Plugin automatically tracks page views and time spent by users. No additional configuration is required once the plugin is activated.

## Database Table

The plugin creates a custom database table to store the tracked data. On plugin activation, the table is automatically created with the following structure:

- `id`: INT(11) - The unique identifier for each record.
- `page_url`: VARCHAR(255) - The URL of the visited page.
- `start_time`: BIGINT(20) - The start time of the user's visit.
- `end_time`: BIGINT(20) - The end time of the user's visit.
- `duration`: BIGINT(20) - The duration of the user's visit in milliseconds.
- `user_id`: BIGINT(20) - The ID of the user. (0 for guest users)
- `user_ip`: VARCHAR(255) - The IP address of the user. (empty for guest users)
- `user_browser`: VARCHAR(255) - The browser details of the user. (empty for guest users)

## Contributing

Contributions to the Custom Analytics Plugin are welcome! If you encounter any issues or have suggestions for improvements, please create a new issue on the [GitHub repository](https://github.com/MrPilu/Custom-Analytics-Plugin-of-Wordpress).

## License

This plugin is licensed under the [MIT License](LICENSE).
