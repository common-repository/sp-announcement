<?php
/*
Plugin Name: WP Announcement
Plugin URI: https://subscriptionpro.co
Description: Enhance WordPress sites with WP Announcement: Dynamic banner, notification & countdowns, integrated with WooCommerce & Dokan for impactful promotions.
Author: SubscriptionPro
Version: 2.0.13
Author URI: https://subsciptionpro.co
License: GPLv2 or later
Text Domain: wp-announcement
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    die('You are not allowed!');
}

//define consts
define('WPANN_PLUGIN_VERSION', '2.0.13');
define('WPANN_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WPANN_PLUGIN_URL', plugin_dir_url(__FILE__));

//filter for layout options, and metabox fields
add_action('plugins_loaded', function () {
    define('WPANN_LAYOUT_INPUT_OPTIONS', apply_filters('wpann_layout_options', require_once(WPANN_PLUGIN_PATH . '/includes/config/layout-options.php')));
    define('WPANN_COMMON_METABOX_FIELDS', apply_filters('wpann_common_custom_fields', require_once(WPANN_PLUGIN_PATH . '/includes/config/common-metabox-fields.php')));
    define('WPANN_TEMPLATE_BASED_METABOX_FIELDS', apply_filters('wpann_dynamic_template_custom_fields', require_once(WPANN_PLUGIN_PATH . '/includes/config/dynamic-metabox-fields.php')));
});

//Post Type Class
if (!class_exists('WPANN_Post_Type')) {
    require_once(WPANN_PLUGIN_PATH . '/includes/class-wpann-post-type.php');
    new WPANN_Post_Type();
}

//Main Class
if (!class_exists('WP_Announcement')) {
    require_once(WPANN_PLUGIN_PATH . '/includes/class-wp-announcement.php');
    new WP_Announcement();
}

// Handle Uninstall
function WPANN_uninstall()
{
    $allposts = get_posts([
        'post_type' => 'announcement',
        'post_status'    => ['publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'],
        'numberposts' => -1
    ]);
    foreach ($allposts as $eachpost) {
        wp_delete_post($eachpost->ID, true);
    }
}

register_uninstall_hook(__FILE__, 'WPANN_uninstall');
