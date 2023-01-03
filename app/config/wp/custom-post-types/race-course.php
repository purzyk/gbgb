<?php

function custom_post_type_race_course()
{
    // Set UI labels for Custom Post Type
    $labels = [
        'name'               => _x('Race Courses', 'Post Type General Name', 'gbgb'),
        'singular_name'      => _x('Race Course', 'Post Type Singular Name', 'gbgb'),
        'menu_name'          => __('Race Courses', 'gbgb'),
        'parent_item_colon'  => __('Parent Race Course', 'gbgb'),
        'all_items'          => __('All Race Courses', 'gbgb'),
        'view_item'          => __('View Race Course', 'gbgb'),
        'add_new_item'       => __('Add New Race Course', 'gbgb'),
        'add_new'            => __('Add New', 'gbgb'),
        'edit_item'          => __('Edit Race Course', 'gbgb'),
        'update_item'        => __('Update Race Course', 'gbgb'),
        'search_items'       => __('Search Race Course', 'gbgb'),
        'not_found'          => __('Not Found', 'gbgb'),
        'not_found_in_trash' => __('Not found in Trash', 'gbgb'),
    ];

    // Set other options for Custom Post Type
    $args = [
        'label'               => __('Race Courses', 'gbgb'),
        'description'         => __('Race Course', 'gbgb'),
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
        'menu_position'       => 8,
        'menu_icon'           => 'dashicons-welcome-learn-more',
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
    ];

    // Registering your Custom Post Type
    register_post_type('racecourse', $args);
}

add_action('init', 'custom_post_type_race_course', 0);


