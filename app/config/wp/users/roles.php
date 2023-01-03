<?php
namespace Basecamp;

/**
 * Setup custom user roles.
 */
class GBGBUserRoles {

    public static $gbgbCustomRoles = [
        'trainer' => 'Trainer',
        'owner' => 'Owner',
        'vet' => 'Vet',
        'apprentice' => 'Apprentice',
        'stadium' => 'Stadium'
    ];

    /**
     * Init
     */
    public static function init()
    {
        //User Roles
        add_action('init', array(__CLASS__, 'setupUserRoles'));
        //Hide admin bar for non admins
        add_action('after_setup_theme', array(__CLASS__, 'remove_admin_bar'));
    }

    /**
     * Hide admin bar for non admins
     */
    public static function remove_admin_bar() {
        $isPrivilleged = current_user_can('administrator') || current_user_can('editor');
        if (!$isPrivilleged) {
            show_admin_bar(false);
        }
    }

    /**
     * Setup custom user roles.
     */
    public static function setupUserRoles() {
        foreach (self::$gbgbCustomRoles as $role => $label) {
            if (get_role($role) === null) {
                $cap_name = $role . "_access";
                add_role(
                    $role,
                    $label,
                    array(
                        'read'    => true,
                        $cap_name => true,
                    )
                );
            }
        }
    }

    /**
     * Check if current user has role, always return true for admins/editors, always false for logged out
     *
     * @param string $roleSlug
     * @return bool
     */
    public static function currentUserHasRole($roleSlug) {
        if (!is_user_logged_in()) {
            return false;
        }
        $isPrivilleged = current_user_can('administrator') || current_user_can('editor');
        return $isPrivilleged || current_user_can($roleSlug);
    }
}
