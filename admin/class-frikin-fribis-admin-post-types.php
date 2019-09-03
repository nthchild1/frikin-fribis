<?php

/**
 * The custom post types of the plugin.
 *
 * @link       https://frik-in.io
 * @since      1.0.0
 * @package     Frikin_Events
 * @subpackage    Frikin_Events/admin
 * @author        Carlos Cortés <tky2048@frik-in.com>
 */
class Frikin_Fribis_Admin_Post_Types
{
    /**
     * The post meta data
     *
     * @since        1.0.0
     * @access        private
     * @var            string $meta The post meta data.
     */
    private $meta;

    /**
     * The ID of this plugin.
     *
     * @since        1.0.0
     * @access        private
     * @var            string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since        1.0.0
     * @access        private
     * @var            string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $New_Frikin_Api The name of this plugin.
     * @param string $version The version of this plugin.
     * @since        1.0.0
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Creates a new custom post type called "event".
     *
     * @since 1.0.0
     * @access public
     * @uses register_post_type()
     */
    public static function new_cpt_fribi()
    {
        $cap_type = 'post';
        $plural = __('Fribis', 'frikin-fribis');
        $single = __('Fribi', 'frikin-fribis');
        $cpt_name = 'fribi';

        $opts['can_export'] = TRUE;
        $opts['capability_type'] = $cap_type;
        $opts['description'] = '';
        $opts['exclude_from_search'] = FALSE;
        $opts['has_archive'] = TRUE;
        $opts['hierarchical'] = FALSE;
        $opts['map_meta_cap'] = TRUE;
        $opts['menu_icon'] = 'dashicons-welcome-add-page';
        $opts['menu_position'] = 26;
        $opts['public'] = TRUE;
        $opts['publicly_querable'] = TRUE;
        $opts['query_var'] = TRUE;
        $opts['register_meta_box_cb'] = '';
        $opts['rewrite'] = FALSE;
        $opts['show_in_admin_bar'] = TRUE;
        $opts['show_in_menu'] = TRUE;
        $opts['show_in_nav_menu'] = TRUE;
        $opts['show_in_rest'] = TRUE;
        $opts['supports'] = array('title', 'editor', 'author', 'thumbnail', 'revisions', 'excerpt', 'custom-fields');
        $opts['taxonomies'] = array('category', 'post_tag', 'event-type');

        $opts['labels']['name'] = _x('Fribis', 'Post type general name', 'frikin-fribis');
        $opts['labels']['singular_name'] = _x('Fribi', 'Post type singular name', 'frikin-fribis');
        $opts['labels']['menu_name'] = _x('Fribis', 'Admin Menu text', 'frikin-fribis');
        $opts['labels']['name_admin_bar'] = _x('Fribi', 'Add New on Toolbar', 'frikin-fribis');
        $opts['labels']['add_new'] = __('Add New', 'frikin-fribis');
        $opts['labels']['add_new_item'] = __('Add New Fribi', 'frikin-fribis');
        $opts['labels']['new_item'] = __('New Fribi', 'frikin-fribis');
        $opts['labels']['edit_item'] = __('Edit Fribi', 'frikin-fribis');
        $opts['labels']['view_item'] = __('View Fribi', 'frikin-fribis');
        $opts['labels']['all_items'] = __('All Fribis', 'frikin-fribis');
        $opts['labels']['search_items'] = __('Search Fribis', 'frikin-fribis');
        $opts['labels']['parent_item_colon'] = __('Parent Fribi:', 'frikin-fribis');
        $opts['labels']['not_found'] = __('No fribis found.', 'frikin-fribis');
        $opts['labels']['not_found_in_trash'] = __('No fribis found in Trash.', 'frikin-fribis');
        $opts['labels']['featured_image'] = _x('Fribi Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'frikin-fribis');
        $opts['labels']['set_featured_image'] = _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'frikin-fribis');
        $opts['labels']['remove_featured_image'] = _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'frikin-fribis');
        $opts['labels']['use_featured_image'] = _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'frikin-fribis');
        $opts['labels']['archives'] = _x('Fribi archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'frikin-fribis');
        $opts['labels']['insert_into_item'] = _x('Insert into fribi', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'frikin-fribis');
        $opts['labels']['uploaded_to_this_item'] = _x('Uploaded to this fribi', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'frikin-fribis');
        $opts['labels']['filter_items_list'] = _x('Filter fribis list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'frikin-fribis');
        $opts['labels']['items_list_navigation'] = _x('Fribis list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'frikin-fribis');
        $opts['labels']['items_list'] = _x('Fribis list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'frikin-fribis');

        $opts['rewrite']['slug'] = strtolower($plural);

        $opts = apply_filters('frikin-fribis-fribi-options', $opts);

        register_post_type(strtolower($cpt_name), $opts);
    }

    /**
     * Add "fribi"  capabilities to user roles.
     *
     * @since 1.0.0
     * @access public
     * @uses add_cap()
     */
    public static function add_custom_capabilities()
    {
        $admins = get_role('administrator');
        $editors = get_role('editor');

        // Event
        $admins->add_cap('edit_others_fribis');
        $admins->add_cap('publish_fribis');
        $admins->add_cap('read_private_fribis');
        $admins->add_cap('delete_fribis');
        $admins->add_cap('delete_private_fribis');
        $admins->add_cap('delete_published_fribis');
        $admins->add_cap('delete_others_fribis');
        $admins->add_cap('edit_private_fribis');
        $admins->add_cap('edit_published_fribis');
        $editors->add_cap('edit_fribis');
        $editors->add_cap('publish_fribis');
        $editors->add_cap('read_private_fribis');
        $editors->add_cap('delete_fribis');
        $editors->add_cap('delete_private_fribis');
        $editors->add_cap('delete_published_fribis');
        $editors->add_cap('delete_others_fribis');
        $editors->add_cap('edit_private_fribis');
        $editors->add_cap('edit_published_fribis');

    }
}
