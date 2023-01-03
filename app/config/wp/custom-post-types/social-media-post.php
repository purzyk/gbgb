<?php

function custom_post_type_social_media_post()
{
    // Set UI labels for Custom Post Type
    $labels = [
        'name'               => _x('Social Media Posts', 'Post Type General Name', 'gbgb'),
        'singular_name'      => _x('Social Media Post', 'Post Type Singular Name', 'gbgb'),
        'menu_name'          => __('Social Media Posts', 'gbgb'),
        'parent_item_colon'  => __('Parent Social Media Post', 'gbgb'),
        'all_items'          => __('All Social Media Posts', 'gbgb'),
        'view_item'          => __('View Social Media Post', 'gbgb'),
        'add_new_item'       => __('Add New Social Media Post', 'gbgb'),
        'add_new'            => __('Add New', 'gbgb'),
        'edit_item'          => __('Edit Social Media Post', 'gbgb'),
        'update_item'        => __('Update Social Media Post', 'gbgb'),
        'search_items'       => __('Search Social Media Post', 'gbgb'),
        'not_found'          => __('Not Found', 'gbgb'),
        'not_found_in_trash' => __('Not found in Trash', 'gbgb'),
    ];

    // Set other options for Custom Post Type
    $args = [
        'label'               => __('Social Media Posts', 'gbgb'),
        'description'         => __('Social Media Post', 'gbgb'),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => [
            'title',
            'excerpt',
            'thumbnail',
            'custom-fields',
            'editor',
        ],
        // You can associate this CPT with a taxonomy or custom taxonomy.
        'taxonomies'          => ['socialmediafeed'],
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => false,
        'menu_position'       => 11,
        'menu_icon'           => 'dashicons-share',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
    ];

    // Registering your Custom Post Type
    register_post_type('socialmediapost', $args);
}

add_action('init', 'custom_post_type_social_media_post', 0);


