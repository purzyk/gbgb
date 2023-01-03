<?php

function custom_post_type_kennel()
{
    // Set UI labels for Custom Post Type
    $labels = [
        'name'               => _x('Kennels', 'Post Type General Name', 'gbgb'),
        'singular_name'      => _x('Kennel', 'Post Type Singular Name', 'gbgb'),
        'menu_name'          => __('Kennels', 'gbgb'),
        'parent_item_colon'  => __('Parent Kennel', 'gbgb'),
        'all_items'          => __('All Kennels', 'gbgb'),
        'view_item'          => __('View Kennel', 'gbgb'),
        'add_new_item'       => __('Add New Kennel', 'gbgb'),
        'add_new'            => __('Add New', 'gbgb'),
        'edit_item'          => __('Edit Kennel', 'gbgb'),
        'update_item'        => __('Update Kennel', 'gbgb'),
        'search_items'       => __('Search Kennel', 'gbgb'),
        'not_found'          => __('Not Found', 'gbgb'),
        'not_found_in_trash' => __('Not found in Trash', 'gbgb'),
    ];

    // Set other options for Custom Post Type
    $args = [
        'label'               => __('Kennels', 'gbgb'),
        'description'         => __('Kennel', 'gbgb'),
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
        'menu_position'       => 7,
        'menu_icon'           => 'dashicons-networking',
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
    ];

    // Registering your Custom Post Type
    register_post_type('kennel', $args);
}

add_action('init', 'custom_post_type_kennel', 0);


