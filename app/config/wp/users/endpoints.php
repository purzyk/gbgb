<?php
namespace Basecamp;
use \WP_User;
use Basecamp\Api\KennelController;
use Basecamp\GBGBUserRoles;

/**
 * Setup endpoints for My Kennel page.
 */
class GBGBUserEndpoints {

    /**
     * Init
     */
    public static function setupEndpoint($context) {
        //Route to correct endpoint
        $endpoint = self::getEndpoint();
        $context['myKennel']['title'] = "Account";
        $context['myKennel']['endpoint'] = $endpoint;
        //Setup additional data if needed
        $methodName = 'setupEndpoint__' . $endpoint;
        if (method_exists(__CLASS__,$methodName)) {
            $context = call_user_func (array(__CLASS__, $methodName), $context);
        }

        $all_roles = GBGBUserRoles::$gbgbCustomRoles;
        $context['myKennel']['roles'] = [];
        foreach ($all_roles as $key => $value) {
            if (GBGBUserRoles::currentUserHasRole($key)) {
                $context['myKennel']['roles'][] = $key;
            }
        }

        return $context;
    }

    /**
     * Setup welcome endpoint
     *
     * @param array $context
     * @return array
     */
    public static function setupEndpoint__welcome($context) {
        $group = get_field('welcome_kennel');
        $context['myKennel']['title'] = $group['heading'];
        $context['myKennel']['introduction'] = $group['introduction'];
        $context['myKennel']['registerCTA'] = [
            'link' => get_field('myKennelPage', 'option') . '?kennelEndpoint=register',
            'text' => $group['register_button'],
        ];
        $context['myKennel']['loginCTA'] = [
            'link' => get_field('myKennelPage', 'option') . '?kennelEndpoint=login',
            'text' => $group['login_text'],
        ];
        $context['myKennel']['content'] = $group['content'];
        return $context;
    }

    /**
     * Setup login endpoint
     *
     * @param array $context
     * @return array
     */
    public static function setupEndpoint__login($context) {
        $group = get_field('login_page');
        $context['myKennel']['title'] = $group['heading'];
        $context['myKennel']['nonce'] = wp_create_nonce('gbgbg-ajax-login');
        return $context;
    }

    /**
     * Setup change password endpoint
     *
     * @param array $context
     * @return array
     */
    public static function setupEndpoint__changepass($context) {
        $user = new WP_User(get_current_user_id());

        //Admin/editor should do this via backend.
        if (in_array ('administrator',$user->roles) || in_array ('editor',$user->roles)){
            wp_redirect(get_admin_url(null,'/profile.php'));
            return $context;
        }

        $context['myKennel']['title'] = "Change your password";
        $context['myKennel']['nonce'] = wp_create_nonce( 'gbgbg-ajax-changepass' );
        return $context;
    }

    /**
     * Setup register endpoint
     *
     * @param array $context
     * @return array
     */
    public static function setupEndpoint__register($context) {
        $group = get_field('register_page');
        $context['myKennel']['title'] = $group['heading'];
        $context['myKennel']['intro'] = $group['introduction'];
        $context['myKennel']['nonce'] = wp_create_nonce( 'gbgbg-ajax-register' );
        $context['myKennel']['nonceLogin'] = wp_create_nonce( 'gbgbg-ajax-login' );
        return $context;
    }

    /**
     * Setup landingpage endpoint
     *
     * @param array $context
     * @return array
     */
    public static function setupEndpoint__landingpage($context) {
        $userId = get_current_user_id();
        $user = new WP_User($userId);
        $context['myKennel']['title'] = "Hi " . $user->get('first_name');
        $context['myKennel']['socialMediaFeedTitle'] = get_field('socialMediaFeedTitle');
        $context['myKennel']['socialMediaFeedPosts'] = get_field('socialMediaFeedPosts');
        $context['myKennel']['itemTitles'] = get_field('landingPageItemTitles');

        return $context;
    }

    /**
     * Setup details endpoint
     *
     * @param array $context
     * @return array
     */
    public static function setupEndpoint__details($context) {
        $userId = get_current_user_id();
        $user = new WP_User($userId);
        $roles = implode(', ', $user->roles);
        $roles = str_replace('subscriber', 'fan', $roles);
        $context['myKennel']['details']['name'] = $user->get('first_name') . ' ' . $user->get('last_name');
        $context['myKennel']['details']['roles'] = $roles;
        $context['myKennel']['details']['email'] = $user->user_email;

        $context['myKennel']['nonce'] = wp_create_nonce( 'gbgbg-ajax-logout' );
        return $context;
    }

    /**
     * Setup my dogs endpoint
     *
     * @param array $context
     * @return array
     */
    public static function setupEndpoint__mydogs($context) {
        $context['myKennel']['title'] = "My Dogs";
        $userId = get_current_user_id();
        $user = new WP_User($userId);
        $context['myKennel']['name'] = $user->get('first_name') . ' ' . $user->get('last_name');
        return $context;
    }

    /**
     * Setup my courses endpoint
     *
     * @param array $context
     * @return array
     */
    public static function setupEndpoint__mycourses($context) {
        $context['myKennel']['title'] = "My Racecourses";
        $userId = get_current_user_id();
        $context['myKennel']['courses'] = wp_json_encode(KennelController::getCoursesArray($userId));
        return $context;
    }

    public static function getResourcesAccessibleForUser($resource) {
        if(empty($resource['roles'])) {
            return true;
        }
        foreach ($resource['roles'] as $role) {
            if (GBGBUserRoles::currentUserHasRole($role)){
                return true;
            }
        }
        return false;
    }

    /**
     * Setup resources endpoint
     *
     * @param array $context
     * @return array
     */
    public static function setupEndpoint__resources($context) {
        $group = get_field('notices_resources_page');
        $context['myKennel']['title'] = $group['heading'];

        if (!is_array($group['resources'])) {
            $group['resources'] = [];
        }
        $resources = array_filter($group['resources'], array(__CLASS__, 'getResourcesAccessibleForUser'));

        if (!is_array($group['notices'])) {
            $group['notices'] = [];
        }
        $context['myKennel']['notices'] = array_filter($group['notices'], array(__CLASS__, 'getResourcesAccessibleForUser'));

        $all_roles = GBGBUserRoles::$gbgbCustomRoles;
        $all_roles['everyone'] = 'Everyone';
        foreach ($all_roles as $key => $value) {
            $context['myKennel']['resources'][$value] = array_filter($resources, function($resource) use ($key) {
                if (!is_array($resource['roles'])) {
                    return $key === "everyone";
                }
                return in_array($key, $resource['roles']);
            });
        }

        $context['myKennel']['resources'] = array_filter($context['myKennel']['resources'], function($element){
            return !empty($element);
        });

        $context['myKennel']['noresults'] = $group['no_results_text'];
        return $context;
    }

    /**
     * Setup upload photos endpoint
     *
     * @param array $context
     * @return array
     */
    public static function setupEndpoint__uploadphotos($context) {
        $context['myKennel']['title'] = "Upload Photo";
        return $context;
    }

    /**
     * Setup lost pass endpoint
     *
     * @param array $context
     * @return array
     */
    public static function setupEndpoint__lostpass($context) {
        $context['myKennel']['title'] = "Reset your password";
        $context['myKennel']['nonce'] = wp_create_nonce('gbgbg-ajax-lostpass');
        return $context;
    }

    /**
     * Setup reset pass endpoint
     *
     * @param array $context
     * @return array
     */
    public static function setupEndpoint__resetpass($context) {
        $context['myKennel']['title'] = "Reset your password";
        $context['myKennel']['nonce'] = wp_create_nonce('gbgbg-ajax-resetpass');
        $context['myKennel']['nonceLogin'] = wp_create_nonce( 'gbgbg-ajax-login' );
        return $context;
    }

    /**
     * Return correct endpoint to render on My Kennel page.
     *
     * @return string
     */
    public static function getEndpoint() {
        $urlVar = get_query_var('kennelEndpoint');
        if(is_user_logged_in()){
            switch ($urlVar) {
                case 'details':
                case 'changepass':
                case 'mydogs':
                case 'mycourses':
                case 'resources':
                    return $urlVar;
                    break;
                case 'uploadphotos':
                    if (GBGBUserRoles::currentUserHasRole('owner')) {
                        return $urlVar;
                    }
                    break;
                default:
                    return 'landingpage';
                    break;
            }
        } else {
            switch ($urlVar) {
                case 'register':
                case 'login':
                case 'lostpass':
                case 'resetpass':
                    return $urlVar;
                    break;
                default:
                    return 'welcome';
                    break;
            }
        }
    }
}
