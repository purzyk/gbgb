<?php

/**
 * Add favicon to Admin Page
 */
add_action('admin_head', function () {
    echo '<link rel="shortcut icon" href="' . images_path('favicon.png') . '" />';

    //hide any option to manually update
    ?>
    <style>
        a[href$="update-core.php"],
        .update-nag {
            display:none !important;
        }
    </style>
    <?php
});

/**
 * Re-order menu elements in wp-admin
 */
add_filter('custom_menu_order', '__return_true');
add_filter('menu_order', function () {
    return ['index.php', 'upload.php', 'edit.php?post_type=page'];
});

/**
 * Remove comments from wp-admin
 */
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

/**
 * Change default icon of Posts
 */
add_action('admin_menu', function() {
    global $menu;
    foreach ($menu as $key => $val) {
        if ('Posts' == $val[0]) {
            $menu[$key][6] = 'dashicons-format-aside';
        }
    }
});
