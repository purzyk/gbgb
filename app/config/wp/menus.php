<?php

/**
 * Registers a custom Navigation Menu in the custom menu editor
 */
function base_camp_register_menus()
{
    register_nav_menu('main_menu', __('Main menu', 'gbgb'));
    register_nav_menu('footer_menu', __('Footer menu', 'gbgb'));
    register_nav_menu('prefooter_left_menu', __('Prefooter left menu', 'gbgb'));
    register_nav_menu('prefooter_right_menu', __('Prefooter right menu', 'gbgb'));
}

add_action('after_setup_theme', 'base_camp_register_menus');

/**
 * Render metabox for custom menu item types.
 */
add_action( 'load-nav-menus.php', 'base_camp_add_menu_item_types_metabox' );

function base_camp_add_menu_item_types_metabox() {
    add_meta_box(
        'base_camp_menu_item_types',
        'Special',
        'base_camp_menu_item_types_metabox',
        'nav-menus',
        'side',
        'default'
    );
}

function base_camp_menu_item_types_metabox($post) {
    ?>
    <div id="base-camp" class="posttypediv">

        <div class="tabs-panel tabs-panel-active">
            <ul class="categorychecklist form-no-clear">
                <li>
                    <label class="menu-item-title">
                        <input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1"> Search
                    </label>
                    <input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
                    <input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="Search">
                    <input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="#search">
                    <input type="hidden" class="menu-item-classes" name="menu-item[-1][menu-item-classes]" value="SearchBarIcon">
                </li>
            <li>
                    <label class="menu-item-title">
                        <input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1"> My Kennel
                    </label>
                    <input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
                    <input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="My Kennel">
                    <input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="#myKennel">
                    <input type="hidden" class="menu-item-classes" name="menu-item[-1][menu-item-classes]" value="SearchBarIcon">
                </li>
            </ul>
        </div>

        <!-- Actions -->
        <p class="button-controls wp-clearfix">
            <span class="add-to-menu">
                <input type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu' ); ?>" name="add-post-type-menu-item" id="<?php echo esc_attr( 'submit-base-camp' ); ?>" />
                <span class="spinner"></span>
            </span>
        </p>
    </div>
<?php
}
