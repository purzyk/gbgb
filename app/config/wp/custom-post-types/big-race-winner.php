<?php

function custom_post_type_big_race_winner()
{
    // Set UI labels for Custom Post Type
    $labels = [
        'name'               => _x('Big Race Winners', 'Post Type General Name', 'gbgb'),
        'singular_name'      => _x('Big Race Winner', 'Post Type Singular Name', 'gbgb'),
        'menu_name'          => __('Big Race Winners', 'gbgb'),
        'parent_item_colon'  => __('Parent Big Race Winner', 'gbgb'),
        'all_items'          => __('All Big Race Winners', 'gbgb'),
        'view_item'          => __('View Big Race Winner', 'gbgb'),
        'add_new_item'       => __('Add New Big Race Winner', 'gbgb'),
        'add_new'            => __('Add New', 'gbgb'),
        'edit_item'          => __('Edit Big Race Winner', 'gbgb'),
        'update_item'        => __('Update Big Race Winner', 'gbgb'),
        'search_items'       => __('Search Big Race Winner', 'gbgb'),
        'not_found'          => __('Not Found', 'gbgb'),
        'not_found_in_trash' => __('Not found in Trash', 'gbgb'),
    ];

    // Set other options for Custom Post Type
    $args = [
        'label'               => __('Big Race Winners', 'gbgb'),
        'description'         => __('Big Race Winner', 'gbgb'),
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
        'menu_position'       => 6,
        'menu_icon'           => 'dashicons-awards',
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
    ];

    // Registering your Custom Post Type
    register_post_type('bigracewinner', $args);
}

add_action('init', 'custom_post_type_big_race_winner', 0);


