<?php

class WP_Announcement
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('init', [$this, 'load_text_domain']);
        add_action('wp_body_open', [$this, 'announcement_html']);
        add_action('admin_enqueue_scripts', [$this, 'add_admin_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'add_frontend_scripts']);

        // ajax
        add_action('init', [$this, 'init_ajax']);

        // filter and action to add column in the data table
        add_filter('manage_announcement_posts_columns', [$this, 'add_table_column']);
        add_action('manage_announcement_posts_custom_column', [$this, 'add_table_column_value'], 10, 2);
    }

    public function init_ajax()
    {
        if (is_user_logged_in()) {
            add_action('wp_ajax_wpa_increment_click_count', [$this, 'handle_click_count']);
        } else {
            add_action('wp_ajax_nopriv_wpa_increment_click_count', [$this, 'handle_click_count']);
        }
    }

    /**
     * Localization
     *
     * @return void
     */
    public function load_text_domain()
    {
        load_plugin_textdomain('wp-announcement', false, WPANN_PLUGIN_PATH . '/languages');
    }

    /**
     * Add admin scripts
     *
     * @param [type] $hook
     * @return void
     */
    public function add_admin_scripts($hook)
    {
        wp_register_style('wpa-admin-css', WPANN_PLUGIN_URL . '/assets/css/admin.css', [], WPANN_PLUGIN_VERSION, 'all');
        wp_register_script('wpa-admin-js', WPANN_PLUGIN_URL . '/assets/js/admin.js', ['jquery'], WPANN_PLUGIN_VERSION, 'all');

        //enqueue styles and scripts
        wp_enqueue_style('wpa-admin-css');
        wp_enqueue_script('wpa-admin-js');
        wp_deregister_style('wp-admin-forms');
    }

    /**
     * Add frontend scripts
     *
     * @param [type] $hook
     * @return void
     */
    public function add_frontend_scripts($hook)
    {
        wp_register_script('wpa-frontend-js', WPANN_PLUGIN_URL . '/assets/js/frontend.js', ['jquery'], WPANN_PLUGIN_VERSION, 'all');

        wp_enqueue_script('wpa-frontend-js');

        $ajax_object = array(
            'ajax_url' => admin_url('admin-ajax.php')
        );
        wp_localize_script('wpa-frontend-js', 'ajax_object', $ajax_object);
    }

    /**
     * Click count handler
     *
     * @return void
     */
    public function handle_click_count()
    {
        $id = $_POST['notice_id'];
        $count = get_post_meta($id, 'click_count', true);
        $count = $count ? $count : 0;
        update_post_meta($id, 'click_count', $count + 1);
        wp_send_json_success('done' . $count + 1);
    }

    /**
     * Add new column in data table of announcement post type
     *
     * @return void
     */
    public function add_table_column($columns)
    {
        $columns['click_count'] = 'Clicks';
        return $columns;
    }

    /**
     * Add value for the new column in data table of announcement post type
     *
     * @return void
     */
    public function add_table_column_value($column, $post_id)
    {
        if ($column == 'click_count') {
            $custom_field_value = get_post_meta($post_id, 'click_count', true); // Replace 'my_custom_field_key' with your actual custom field key
            $custom_field_value = $custom_field_value ? $custom_field_value : 0;
            echo $custom_field_value;
        }
    }

    /**
     * Add the notice section in frontend of the website
     *
     * @return void
     */
    public function announcement_html()
    {
        $current_page_id = get_queried_object_id();

        //query notice for this page
        $args = [
            'post_type' => 'announcement',
            'meta_query' => [
                [
                    'key' => 'page_ids',
                    'value' => '"' . $current_page_id . '"',
                    'compare' => 'LIKE'
                ]
            ],
            'posts_per_page' => 1
        ];
        $query = new WP_Query($args);

        //if no notice found for this page then check if any global notice exists
        if (!$query->have_posts()) {
            $args = [
                'post_type' => 'announcement',
                'meta_query' => [
                    [
                        'key' => 'page_ids',
                        'value' => '',
                    ]
                ],
                'posts_per_page' => 1
            ];
            $query = new WP_Query($args);
        }

        //show the banner or notice if exist
        while ($query->have_posts()) {
            $query->the_post();

            //get meta data and process it
            $data = get_post_meta(get_the_ID(), '', true);
            $data = array_map(fn($val) => $val[0], $data);

            //creating callback for retriving dynamic values
            $template_field = function ($key, $fallback = '') use ($data) {
                return isset($data[$data['layout'] . '_' . $key]) && $data[$data['layout'] . '_' . $key] ? $data[$data['layout'] . '_' . $key] : $fallback;
            };
            $common_field = function ($key, $fallback = '') use ($data) {
                return isset($data[$key]) && $data[$key] ? $data[$key] : $fallback;
            };

            // check if its disable for single post page
            if (!($common_field('disable_single_post_page', 'no') == 'yes' && is_singular('post'))) {
                //including the view/html of the section
                $layout_options = WPANN_LAYOUT_INPUT_OPTIONS;
                if (isset($layout_options[$data['layout']])) {
                    if (isset($layout_options[$data['layout']]['html'])) {
                        require_once($layout_options[$data['layout']]['html']);
                    }
                }
            }
        }

        //reset post data
        wp_reset_postdata();
    }
}
