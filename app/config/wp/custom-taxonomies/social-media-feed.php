<?php

function custom_taxonomy_social_media_feed()
{
    // Set UI labels for Custom Taxonomy
    $labels = [
        'name'              => _x('Social Media Feeds', 'Taxonomy general name', 'gbgb'),
        'singular_name'     => _x('Social Media Feed', 'Taxonomy singular name', 'gbgb'),
        'search_items'      => __('Search Social Media Feeds', 'gbgb'),
        'all_items'         => __('All Social Media Feeds', 'gbgb'),
        'parent_item'       => __('Parent Social Media Feed', 'gbgb'),
        'parent_item_colon' => __('Parent Social Media Feed:', 'gbgb'),
        'edit_item'         => __('Edit Social Media Feed', 'gbgb'),
        'update_item'       => __('Update Social Media Feed', 'gbgb'),
        'add_new_item'      => __('Add New Social Media Feed', 'gbgb'),
        'new_item_name'     => __('New Social Media Feed Name', 'gbgb'),
        'menu_name'         => __('Social Media Feed', 'gbgb'),
    ];

    // Set other options for Custom Taxonomy
    // https://codex.wordpress.org/Function_Reference/register_taxonomy
    $args = [
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
    ];

    // Registering your Custom Taxonomy
    register_taxonomy('socialmediafeed', ['socialmediapost'], $args);
}

add_action('init', 'custom_taxonomy_social_media_feed', 0);
