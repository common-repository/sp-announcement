<?php

class WPANN_Post_Type
{
    private static $common_meta_box_fields = [];

    private static $dynamic_metabox_fields = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('plugins_loaded', [self::class, 'init']);

        //requried actions for post type
        add_action('init', [self::class, 'register_custom_post_type']);
        add_action('add_meta_boxes', [self::class, 'register_meta_boxes']);
        add_action('save_post_announcement', [self::class, 'update_meta_box']);

        //add duplicate system
        add_filter('post_row_actions', [self::class, 'add_duplicate_post_row_action'], 10, 2);
        add_action('post_submitbox_misc_actions', [self::class, 'add_duplicate_post_button']);
        add_action('admin_action_duplicate_post', [self::class, 'duplicate_post_action']);
    }

    /**
     * Init options/config
     *
     * @return void
     */
    public static function init()
    {

        //set the values
        self::$common_meta_box_fields = WPANN_COMMON_METABOX_FIELDS;
        self::$dynamic_metabox_fields = WPANN_TEMPLATE_BASED_METABOX_FIELDS;
    }

    /**
     * Register new notice post type to store the notces
     *
     * @return void
     */
    public static function register_custom_post_type()
    {

        $labels = array(
            'name'                  => _x('Announcements', 'Post Type General Name', 'wp-announcement'),
            'singular_name'         => _x('Announcement', 'Post Type Singular Name', 'wp-announcement'),
            'menu_name'             => __('Announcements', 'wp-announcement'),
            'name_admin_bar'        => __('Announcement', 'wp-announcement'),
            'archives'              => __('Announcement Archives', 'wp-announcement'),
            'attributes'            => __('Announcement Attributes', 'wp-announcement'),
            'parent_item_colon'     => __('Parent Item:', 'wp-announcement'),
            'all_items'             => __('All Announcements', 'wp-announcement'),
            'add_new_item'          => __('Add New Announcement', 'wp-announcement'),
            'add_new'               => __('Add New', 'wp-announcement'),
            'new_item'              => __('New Announcement', 'wp-announcement'),
            'edit_item'             => __('Edit Announcement', 'wp-announcement'),
            'update_item'           => __('Update Announcement', 'wp-announcement'),
            'view_item'             => __('View Announcement', 'wp-announcement'),
            'view_items'            => __('View Items', 'wp-announcement'),
            'search_items'          => __('Search Announcement', 'wp-announcement'),
            'not_found'             => __('Not found', 'wp-announcement'),
            'not_found_in_trash'    => __('Not found in Trash', 'wp-announcement'),
            'featured_image'        => __('Featured Image', 'wp-announcement'),
            'set_featured_image'    => __('Set featured image', 'wp-announcement'),
            'remove_featured_image' => __('Remove featured image', 'wp-announcement'),
            'use_featured_image'    => __('Use as featured image', 'wp-announcement'),
            'insert_into_item'      => __('Insert into item', 'wp-announcement'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'wp-announcement'),
            'items_list'            => __('Items list', 'wp-announcement'),
            'items_list_navigation' => __('Items list navigation', 'wp-announcement'),
            'filter_items_list'     => __('Filter items list', 'wp-announcement'),
        );
        $capabilities = array(
            'edit_post'           => 'edit_announcement',
            'read_post'           => 'read_announcement',
            'delete_post'         => 'delete_announcement',
            'edit_posts'          => 'edit_announcements',
            'edit_others_posts'   => 'edit_others_announcements',
            'publish_posts'       => 'publish_announcements',
            'read_private_posts'  => 'read_private_announcements',
        );
        $args = array(
            'label'                 => __('Announcement', 'wp-announcement'),
            'description'           => __('Announcement Message', 'wp-announcement'),
            'labels'                => $labels,
            'supports'              => array('title'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'         => 'dashicons-megaphone',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'capabilities' => $capabilities,
        );
        register_post_type('announcement', $args);

        // Get the "Author" role
        $admin_role = get_role('administrator');

        // Add custom capabilities for the "announcement" post type
        $admin_role->add_cap('edit_announcement');
        $admin_role->add_cap('read_announcement');
        $admin_role->add_cap('delete_announcement');
        $admin_role->add_cap('edit_announcements');
        $admin_role->add_cap('edit_others_announcements');
        $admin_role->add_cap('publish_announcements');
        $admin_role->add_cap('read_private_announcements');
    }

    public static function register_meta_boxes()
    {
        add_meta_box(
            'announcement_meta_box',
            __('Announcement Setting', 'wp-announcement'),
            [self::class, 'meta_box_html'],
            'announcement',
            'normal',
            'high'
        );
    }

    /**
     * Show the HTML of the metabox
     *
     * @param mixed $post
     * @return void
     */
    public static function meta_box_html($post)
    {
        //get meta data and process it
        $data = get_post_meta($post->ID, '', true);
        $data = array_map(fn ($val) => $val[0], $data);

        //tabs
        $tabs = [];
        $tabs['common'] = self::$common_meta_box_fields;
        $tabs['dynamic'] = self::$dynamic_metabox_fields;
        // echo '<pre>';
        // print_r(self::$common_meta_box_fields);
        // echo '</pre>';

        require_once(WPANN_PLUGIN_PATH . '/views/admin/announcement_meta_box.php');
    }

    /**
     * Clean unused fields
     *
     * @param int $id
     * @param string $layout
     * @return void
     */
    public static function clean_unused_fields($id, $all_fields)
    {
        $oldArr = get_post_meta($id);
        $fields = array_diff_key($oldArr, $all_fields); //get the unused array items

        foreach ($fields as $key => $val) {
            if($key == 'click_count' || $key == '_edit_last' || $key == '_edit_lock'){
                continue;
            }

            delete_post_meta($id, $key);
        }
    }

    /**
     * Update metabox items on post type data update
     *
     * @param int $post_id
     * @return void
     */
    public static function update_meta_box(int $post_id)
    {
        // stop if its not saving data
        if (!isset($_POST) || count($_POST) < 1) {
            return;
        }

        $layout = $_POST['layout'];

        $all_fields = array_merge(
            self::$common_meta_box_fields['layout'] ?? [],
            self::$common_meta_box_fields['content'] ?? [],
            self::$common_meta_box_fields['styles'] ?? [],
            self::$common_meta_box_fields['settings'] ?? []
        );

        // merge
        foreach (self::$dynamic_metabox_fields as $tab => $templates) {
            foreach ($templates as $template_key => $template_fields) {
                if ($template_key == $layout) {
                    foreach ($template_fields as $field_key => $field_options) {
                        $all_fields[$template_key . '_' . $field_key] = $field_options;
                    }
                }
            }
        }

        self::clean_unused_fields($post_id, $all_fields);

        foreach ($all_fields as $field => $optionsArray) {
            $value = $_POST[$field];
            //sanitize post_ids field value
            if (isset($optionsArray['type']) && $optionsArray['type'] == 'multi_dropdown_pages' && is_array($value)) {
                $nums = count($value);
                if ($nums == 1) {
                    $value = $value[0] != '' ? '"' . $value[0] . '"' : '';
                } elseif ($nums > 1) {
                    $str = '';
                    $i = 0;
                    while ($i < $nums) {
                        if ($i > 0) {
                            $str .= ',';
                        }
                        $str .= '"';
                        $str .= $value[$i];
                        $str .= '"';
                        $i++;
                    }
                    $value = $str;
                }
            }

            // make sure there is a value
            // $value = $value ? $value : $optionsArray['value'];

            //check if the value is array type and sanitize its values
            $is_array_or_loop = (isset($optionsArray['type']) && ($optionsArray['type'] == 'loop_text' || $optionsArray['type'] == 'multiselect'));
            if($is_array_or_loop){
                foreach($value as $i => $item){
                    if(!is_array($item)){
                        $value[$i] = sanitize_text_field($item);
                    }
                }
            }

            //update field values
            if (array_key_exists($field, $_POST)) {
                update_post_meta(
                    $post_id,
                    $field,
                    $is_array_or_loop ? wp_json_encode($value) : sanitize_text_field($value) //encode if its msg array field
                );
            }
        }
    }

    /**
     * add duplicate option btn
     *
     * @param [type] $actions
     * @param [type] $post
     * @return void
     */
    public static function add_duplicate_post_row_action($actions, $post)
    {
        if ('announcement' === $post->post_type) {
            $duplicate_url = esc_url(wp_nonce_url(admin_url('admin.php?action=duplicate_post&post=' . $post->ID), 'duplicate-post_' . $post->ID));
            $duplicate_action = '<a href="' . $duplicate_url . '">' . __('Duplicate', 'wp-announcement') . '</a>';
            $actions['duplicate'] = $duplicate_action;
        }

        return $actions;
    }

    /**
     * Add duplicate button in edit page
     *
     * @return void
     */
    public static function add_duplicate_post_button()
    {
        global $post;

        if ($post && 'announcement' === $post->post_type) { ?>
            <div class="misc-pub-section">
                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?action=duplicate_post&post=' . $post->ID), 'duplicate-post_' . $post->ID)); ?>" class="button"><?php esc_html_e('Duplicate Post', 'wp-announcement'); ?></a>
            </div>
<?php
        }
    }

    /**
     * Handle duplicate button click action
     *
     * @return void
     */
    public static function duplicate_post_action()
    {
        if (!isset($_GET['post']) || !isset($_GET['_wpnonce'])) {
            wp_die('Invalid request.');
        }

        $post_id = intval($_GET['post']);
        $nonce = $_GET['_wpnonce'];

        if (!wp_verify_nonce($nonce, 'duplicate-post_' . $post_id)) {
            wp_die('Invalid request.');
        }

        if (!current_user_can('edit_post', $post_id)) {
            wp_die('Permission denied.');
        }

        $post = get_post($post_id);

        if (empty($post)) {
            wp_die('Invalid post.');
        }

        $new_post_args = array(
            'post_title'   => $post->post_title . ' (Copy)',
            'post_content' => $post->post_content,
            'post_status'  => 'draft', // Set the desired status for the duplicated post
            'post_type'    => $post->post_type,
            'post_author'  => get_current_user_id(),
        );

        $new_post_id = wp_insert_post($new_post_args);

        if ($new_post_id) {
            // Duplicate post meta
            $post_meta = get_post_meta($post_id);
            foreach ($post_meta as $meta_key => $meta_values) {
                foreach ($meta_values as $meta_value) {
                    add_post_meta($new_post_id, $meta_key, $meta_value);
                }
            }

            // Redirect to the duplicated post
            wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
            exit;
        } else {
            wp_die('Error duplicating post.');
        }
    }
}
