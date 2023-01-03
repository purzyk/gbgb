<?php
namespace Basecamp;
use GuzzleHttp\Client;
use \WP_User;
use \WP_Error;

/**
 * Integrate with Stadium Portal's user API.
 */
class GBGBUserApi {

    /**
     * Init
     */
    public static function init()
    {
        //Authentication
        add_filter( 'authenticate', array(__CLASS__, 'authenticate'), 10, 3 );
        //Destroy Stadium Portal cookie on logout
        add_action('wp_logout', array(__CLASS__, 'logout'));
        //Terminate session if Stadium Portal session expired
        add_action('init',array(__CLASS__, 'checkForExpiry'));
        //Force frontend lostpass and login
        add_action('lostpassword_url',array(__CLASS__, 'setLostPassUrl'));
        add_action('login_url',array(__CLASS__, 'setLoginUrl'));
        add_action('wp_logout',array(__CLASS__, 'redirectOnLogout'));
        //Block dashboard for regular users
        add_action('init',array(__CLASS__, 'blockDashboard'));
    }

    /**
     * Send request to user API
     *
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public static function apiRequest($endpoint,$data,$authorization = false, $authToken = '') {
        $url = trailingslashit(get_field('userAPIUrl','option'));
        $client = new \GuzzleHttp\Client(['base_uri' => $url, 'http_errors' => false]);
        $headers = array();
        if( $authorization ) {
            if(empty($authToken) && isset($_COOKIE['GBGBStadiumRedirect'])) {
                $authToken = $_COOKIE['GBGBStadiumRedirect'];
            }
            $headers['Authorization'] = 'Bearer ' . $authToken;
        }
        $response = $client->request('POST', $endpoint, array(
            'form_params' => $data,
            'headers' => $headers
        ));
        $result = json_decode($response->getBody(), true);
        if( json_last_error() !== JSON_ERROR_NONE ){
            $result = array();
            $result['_NoBody'] = true;
        }
        $result['_HTTPStatus'] = $response->getStatusCode();
        $result['Headers'] = $response->getHeaders();
        return $result;
    }

     /**
     * Get Oauth token
     * @return string
     */
    public static function getAuthToken() {
        $apiUrl = trailingslashit(get_field('userAPIUrl','option')) . 'Token';
        $id = get_field('userAPIUsername','option');
        $secret = get_field('userAPISecret','option');
        $config = array(
            'idFieldName' => 'username',
            'secretFieldName' => 'password',
            'forceRefresh' => true,
        );
        return OAuthClient::getToken($apiUrl, $id,$secret,'password',$config);
    }

    /**
     * Create user.
     *
     * @param string $username
     * @param string $password
     * @return WP_User
     */
    public static function createUser($username) {
        //username and mail are the same. Password will never be used so we use uniqid()
        $userId = wp_create_user( $username, uniqid(), $username );

        //Account exists on remote so we flag it.
        $apiUrl = trailingslashit(get_field('userAPIUrl','option'));
        $flags = [];
        $flags[$apiUrl] = true;
        update_user_meta($user->ID,'__gbgb_remote_accounts',$flags);

        return new WP_User($userId);
    }

    /**
     * Apply user name and roles to WP user.
     *
     * @param WP_User $user
     * @param string $firstName
     * @param string $lastName
     * @param array $roles
     * @return WP_User
     */
    public static function updateUser( $user, $firstName, $lastName, $roles ) {
        //Sync roles.
        $user->set_role('subscriber');
        $roles = str_replace("[","",$roles);
        $roles = str_replace("]","",$roles);
        $roles = explode(",", $roles);
        if(!empty($roles)) {
            foreach ($roles as $role) {
                $role = str_replace('"',"",$role);
                $user->add_role(strtolower($role));
            }
        }
        wp_update_user( $user );
        //Sync name.
        wp_update_user( array( 'ID' => $user->ID, 'first_name' => $firstName,
        'last_name' => $lastName ) );
        return $user;
    }

    /**
     * Make sure the remote copy of the account is created if necessary
     *
     * @param WP_User $user
     * @return bool
     */
    public static function makeSureRemoteAccountExists($user) {
        //We check if account is confirmed to exists on remote.
        //The flag is assigned to API url so switching API urls will force re-check
        $apiUrl = trailingslashit(get_field('userAPIUrl','option'));
        $flags = get_user_meta($user->ID,'__gbgb_remote_accounts', true);
        if(!is_array($flags)){
            $flags = [];
        }
        if(isset($flags[$apiUrl]) && $flags[$apiUrl]=== true){
            return true;
        }

        $firstName = $user->get('first_name');
        $lastName = $user->get('last_name');
        $mail = $user->user_email;
        //Generate correct placeholder PW
        $password = uniqid() . "aA1%";
        $result = GBGBUserApi::apiRequest('Account/Register', array(
            'Email' => $mail,
            'Password' => $password,
            'ConfirmPassword' => $password,
            'FirstName' => $firstName,
            'LastName' => $lastName,
        ));

        if($result['_HTTPStatus'] === 200) {
            //Account created, flag true
            $flags[$apiUrl] = true;
            update_user_meta($user->ID,'__gbgb_remote_accounts',$flags);
            return true;
        }  else if( isset($result['ModelState']) && is_array(($result['ModelState'])) ) {
            //Account already exists, flag true
            $flags[$apiUrl] = true;
            update_user_meta($user->ID,'__gbgb_remote_accounts',$flags);
            return true;
        } else {
            //Unexpected response, don't flag.
            return false;
        }
    }

    /**
     * Replace WP auth with remote API.
     *
     * @param mixed $user
     * @param string $username
     * @param string $password
     * @return void
     */
    public static function authenticate( $user, $username, $password ) {
        //Skip for for administrators and editor.
        $check_user=get_user_by("login",$username);
        if(!$check_user){
            $check_user=get_user_by("email",$username);
        }
        if($check_user) {
            if (in_array ('administrator',$check_user->roles) || in_array ('editor',$check_user->roles)){
                return $user;
            }
            self::makeSureRemoteAccountExists($check_user);
        }

        //Disable default authentication methods.
        remove_action('authenticate', 'wp_authenticate_username_password', 20);
        remove_action('authenticate', 'wp_authenticate_email_password', 20);

        //Fire API request
        $result = self::apiRequest('Token', array(
            'grant_type' => 'password',
            'username' => $username,
            'password' => $password
        ));
        //Handle correct response.
        if( isset($result['access_token']) ){
            $user = get_user_by("email",$result['userName']);

            //Create user on the fly if doesn't exists locally
            if( $user === false ){
                $user = self::createUser($result['userName']);
            }
            //Apply user name and roles from response
            $user = self::updateUser($user,$result['firstName'], $result['lastName'], $result['roles']);

            if(isset($result['Headers']['Set-Cookie'][0])){
                preg_match('/(.*)=(.*);/U', $result['Headers']['Set-Cookie'][0], $match);
                setcookie($match[1], $match[2], time() + $result['expires_in'], "/");
            }
            //Set cookie for Stadium Portal
            setcookie("GBGBStadiumRedirect", $result['access_token'], time() + $result['expires_in'], "/");
        } else if( isset($result['error_description']) ) {
            //Return error provided by API
            $user = new WP_Error( 'denied', $result['error_description'] );
        } else {
            //Return generic error if no correct response
            return new WP_Error( 'denied', 'Server error' );
        }

        return $user;
    }

    /**
     * Destroy Stadium Portal cookie on logout.
     */
    public static function logout() {
        unset($_COOKIE["GBGBStadiumRedirect"]);
        setcookie("GBGBStadiumRedirect", '', time() - 3600, '/');
    }

    /**
     * Terminate session if Stadium Portal session expired.
     */
    public static function checkForExpiry() {
        $userId = get_current_user_id();
        if($userId) {
            $user = new WP_User($userId);
            // Do not apply to admins and editors.
            if (in_array ('administrator',$user->roles) || in_array ('editor',$user->roles)){
                return;
            }
            //Log out if the session cookie expired.
            $hasCookie = isset($_COOKIE['GBGBStadiumRedirect']);
            if(!$hasCookie) {
                wp_logout();
            }
        }
    }
    /**
     * Send pass reset email to user.
     */
    public static function sendPasswordReset($email) {
        $token = uniqid();
        $resetUrl = trailingslashit(get_field('myKennelPage', 'option')) . "?kennelEndpoint=resetpass&resetToken=" . $token;
        $transientName = "GBGB Reset Token: " . $token;
        set_transient($transientName, $email, 3600 * 24);
        $message = "Hello,<br> <br> <br>
        It appears that you've requested password reset on https://gbgb.org.uk:<br> <br> <br>
        <a style=\"text-decoration:underline;\" href='". $resetUrl ."'>Click here to reset your password</a><br> <br> <br>
        If it wasn't you who requested the reset, please ignore this email.
        ";
        wp_mail( $email, 'GBGB password reset', $message);
    }

    /**
     * Make frontend lostpass form the only one
     */
    public static function setLostPassUrl() {
        return trailingslashit(get_field('myKennelPage', 'option')) . '?kennelEndpoint=lostpass';
    }

    /**
     * Make frontend login form the only one
     */
    public static function setLoginUrl() {
        return trailingslashit(get_field('myKennelPage', 'option')) . '?kennelEndpoint=login';
    }

    public static function redirectOnLogout() {
        $is_doing_ajax 			= defined( 'DOING_AJAX' ) && DOING_AJAX;
        if( ! $is_doing_ajax ){
            wp_redirect( trailingslashit(get_field('myKennelPage', 'option')) . '?kennelEndpoint=login' );
            exit;
        }
    }

    /**
     * Make frontend login form the only one
     */
    public static function blockDashboard () {
        $is_doing_ajax 			= defined( 'DOING_AJAX' ) && DOING_AJAX;
        $is_admin_page_request 	= is_admin();

        // If not admin trying to access WP Dashboard.
        if (
            is_user_logged_in() &&
            $is_admin_page_request &&
            ! current_user_can( 'administrator' ) &&
            ! current_user_can( 'editor' ) &&
            ! $is_doing_ajax
        ) {
            wp_redirect( home_url() );
            exit;
        }
    }
}
