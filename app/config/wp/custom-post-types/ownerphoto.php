<?php

function custom_post_type_ownerphoto()
{
    // Set UI labels for Custom Post Type
    $labels = [
        'name'               => _x('Owner photos', 'Post Type General Name', 'gbgb'),
        'singular_name'      => _x('Owner photo', 'Post Type Singular Name', 'gbgb'),
        'menu_name'          => __('Owner photos', 'gbgb'),
        'parent_item_colon'  => __('Parent Owner photo', 'gbgb'),
        'all_items'          => __('All Owner photos', 'gbgb'),
        'view_item'          => __('View Owner photo', 'gbgb'),
        'add_new_item'       => __('Add New Owner photo', 'gbgb'),
        'add_new'            => __('Add New', 'gbgb'),
        'edit_item'          => __('Edit Owner photo', 'gbgb'),
        'update_item'        => __('Update Owner photo', 'gbgb'),
        'search_items'       => __('Search Owner photo', 'gbgb'),
        'not_found'          => __('Not Found', 'gbgb'),
        'not_found_in_trash' => __('Not found in Trash', 'gbgb'),
    ];

    // Set other options for Custom Post Type
    $args = [
        'label'               => __('Owner photos', 'gbgb'),
        'description'         => __('Owner photo', 'gbgb'),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => [
            'title',
            'custom-fields',
        ],
        // You can associate this CPT with a taxonomy or custom taxonomy.
        'taxonomies'          => [],
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
        'menu_position'       => 9,
        'menu_icon'           => 'dashicons-images-alt',
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'capability_type'     => 'page',
    ];

    // Registering your Custom Post Type
    register_post_type('ownerphoto', $args);
}

add_action('init', 'custom_post_type_ownerphoto', 0);


