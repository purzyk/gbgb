<?php

function custom_taxonomy_content_type()
{
    // Set UI labels for Custom Taxonomy
    $labels = [
        'name'              => _x('Content Types', 'Taxonomy general name', 'gbgb'),
        'singular_name'     => _x('Content Type', 'Taxonomy singular name', 'gbgb'),
        'search_items'      => __('Search Content Types', 'gbgb'),
        'all_items'         => __('All Content Types', 'gbgb'),
        'parent_item'       => __('Parent Content Type', 'gbgb'),
        'parent_item_colon' => __('Parent Content Type:', 'gbgb'),
        'edit_item'         => __('Edit Content Type', 'gbgb'),
        'update_item'       => __('Update Content Type', 'gbgb'),
        'add_new_item'      => __('Add New Content Type', 'gbgb'),
        'new_item_name'     => __('New Content Type Name', 'gbgb'),
        'menu_name'         => __('Content Type', 'gbgb'),
    ];

    // Set other options for Custom Taxonomy
    // https://codex.wordpress.org/Function_Reference/register_taxonomy
    $args = [
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
    ];

    // Registering your Custom Taxonomy
    register_taxonomy('contenttype', ['post'], $args);
}

add_action('init', 'custom_taxonomy_content_type', 0);
