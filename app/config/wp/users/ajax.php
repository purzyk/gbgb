<?php
namespace Basecamp;
use \WP_HTTP_Response;
use \WP_User;
use Basecamp\GBGBUserApi;

/**
 * AJAX actions for frontend user functionalities.
 */
class GBGBUserAjax {


    /**
     * Init
     */
    public static function init() {
        //AJAX actions
        add_action( 'wp_ajax_nopriv_gbgb_login',array(__CLASS__, 'ajaxLogin'));
        add_action( 'wp_ajax_gbgb_change_pass',array(__CLASS__, 'ajaxChangePass'));
        add_action( 'wp_ajax_nopriv_gbgb_lost_pass',array(__CLASS__, 'ajaxLostPass'));
        add_action( 'wp_ajax_nopriv_gbgb_reset_pass',array(__CLASS__, 'ajaxResetPass'));
        add_action( 'wp_ajax_nopriv_gbgb_register',array(__CLASS__, 'ajaxRegister'));
        add_action( 'wp_ajax_gbgb_logout',array(__CLASS__, 'ajaxLogout'));
    }

    /**
     * Create AJAX response.
     *
     * @return WP_HTTP_Response
     */
    public static function ajaxResponse($success, $message, $data = array()) {
        $message = wp_strip_all_tags($message);
        $response = array(
            'success' => $success,
            'message' => $message,
            'data' => $data,
        );
        wp_send_json_success($response);
    }

    /**
     * AJAX action for frontend login.
     *
     * @return WP_HTTP_Response
     */
    public static function ajaxLogin() {
        //Nonce check and input sanitization.
        check_ajax_referer( 'gbgbg-ajax-login', 'security', true );
        $userName = filter_input( INPUT_POST, 'gbgb_username', FILTER_SANITIZE_STRING );
        $password = filter_input( INPUT_POST, 'gbgb_password', FILTER_SANITIZE_STRING );

        if(is_user_logged_in()) {
            return self::ajaxResponse(false, "Already logged in");
        }

        //Attempt login with provided creds.
	    $user = wp_signon( array(
            'user_login' => $userName,
            'user_password' => $password,
            'remember' => false,
        ), is_ssl() );

        //Handle result.
        if ( is_wp_error( $user ) ) {
            return self::ajaxResponse(false, $user->get_error_message());
        } else {
            $data = array(
                'stadium_login' => false,
            );
            if(in_array ('stadium',$user->roles) || in_array ('owner',$user->roles)){
                $data = array(
                    'stadium_login' => true,
                    'stadium_login_url' => trailingslashit(get_field('stadiumPortalUrl','option')),
                );
            }
            return self::ajaxResponse(true,"", $data);
        }
        wp_die();
    }

    /**
     * AJAX action for account registration.
     *
     * @return WP_HTTP_Response
     */
    public static function ajaxRegister() {
        //Nonce check and input sanitization.
        check_ajax_referer( 'gbgbg-ajax-register', 'security', true );
        $mail               = filter_input( INPUT_POST, 'gbgb_username', FILTER_SANITIZE_STRING );
        $password           = filter_input( INPUT_POST, 'gbgb_password', FILTER_SANITIZE_STRING );
        $confirmPassword    = filter_input( INPUT_POST, 'gbgb_confirm_password', FILTER_SANITIZE_STRING );
        $firstName          = filter_input( INPUT_POST, 'gbgb_first_name', FILTER_SANITIZE_STRING );
        $lastName           = filter_input( INPUT_POST, 'gbgb_last_name', FILTER_SANITIZE_STRING );

        $user = get_user_by("email",$mail);
        if($user){
            return self::ajaxResponse(false, 'Email already used');
        }

        //Fire API request
        $result = GBGBUserApi::apiRequest('Account/Register', array(
            'Email' => $mail,
            'Password' => $password,
            'ConfirmPassword' => $confirmPassword,
            'FirstName' => $firstName,
            'LastName' => $lastName,
        ));

        //Handle response.
        if($result['_HTTPStatus'] === 200) {
            //Success.
            $user = GBGBUserApi::createUser($mail);
            return self::ajaxResponse(true, '');
        }  else if( isset($result['ModelState']) && is_array(($result['ModelState'])) ) {
            //Return error provided by API.
            return self::ajaxResponse(false, $result['ModelState'] );
        } else {
            //Return generic error if no correct error from API.
            return self::ajaxResponse(false, 'Server Error');
        }
        wp_die();
    }

    /**
     * AJAX action for sending the reset link.
     *
     * @return WP_HTTP_Response
     */
    public static function ajaxLostPass() {
        //Nonce check and input sanitization.
        check_ajax_referer( 'gbgbg-ajax-lostpass', 'security', true );
        $userName = filter_input( INPUT_POST, 'gbgb_username', FILTER_SANITIZE_STRING );

        //check if user exists.
        $user = get_user_by("email",$userName);
        if($user){
            GBGBUserApi::makeSureRemoteAccountExists($user);
            GBGBUserApi::sendPasswordReset($userName);
        }

        //we do not send errors to frontend to prevent using bruteforce to obtain user emails.
        return self::ajaxResponse(true, '');
        wp_die();
    }

    /**
     * AJAX action for handling pass reset.
     *
     * @return WP_HTTP_Response
     */
    public static function ajaxResetPass() {
        //Nonce check and input sanitization.
        check_ajax_referer( 'gbgbg-ajax-resetpass', 'security', true );
        $token = filter_input( INPUT_POST, 'gbgb_token', FILTER_SANITIZE_STRING );
        $password = filter_input( INPUT_POST, 'gbgb_password', FILTER_SANITIZE_STRING );

        $transientName = "GBGB Reset Token: " . $token;
        $email = get_transient($transientName);
        delete_transient($transientName);

        if($email === false) {
            return self::ajaxResponse(false, "Incorrect token" );
        }

        //No api request for admins/editors
        $user=get_user_by("email",$email);
        if($user) {
            if (in_array ('administrator',$user->roles) || in_array ('editor',$user->roles)){
                wp_set_password( $password, $user->ID );
                return self::ajaxResponse(true, $email);
            }
        }

        //Fire API request
        $result = GBGBUserApi::apiRequest('Account/ResetPasswordUsingEmail', array(
            'NewPassword' => $password,
            'ConfirmPassword' => $password,
            'Email' => $email,
        ), true, GBGBUserApi::getAuthToken());

        if($result['_HTTPStatus'] === 200) {
            //Success.
            self::ajaxResponse(true, $email);
        } else {
            //Return generic error if no correct error from API.
            return self::ajaxResponse(false, 'Server Error');
        }
        wp_die();
    }

    /**
     * AJAX action for setting new password.
     *
     * @return WP_HTTP_Response
     */
    public static function ajaxChangePass() {
        //Nonce check and input sanitization.
        check_ajax_referer( 'gbgbg-ajax-changepass', 'security', true );
        $oldPassword        = filter_input( INPUT_POST, 'gbgb_old_password', FILTER_SANITIZE_STRING );
        $password           = filter_input( INPUT_POST, 'gbgb_password', FILTER_SANITIZE_STRING );
        $confirmPassword    = filter_input( INPUT_POST, 'gbgb_confirm_password', FILTER_SANITIZE_STRING );

        //Fire API request
        $result = GBGBUserApi::apiRequest('Account/ChangePassword', array(
            'OldPassword' => $oldPassword,
            'NewPassword' => $password,
            'ConfirmPassword' => $confirmPassword,
        ), true);


        //Handle response.
        if($result['_HTTPStatus'] === 200) {
            //Success.
            return self::ajaxResponse(true, '');
        }  else if( isset($result['ModelState']) && is_array(($result['ModelState'])) ) {
            //Return error provided by API.
            return self::ajaxResponse(false, $result['ModelState'] );
        } else {
            //Return generic error if no correct error from API.
            return self::ajaxResponse(false, 'Server Error');
        }
        wp_die();
    }

    /**
     * AJAX action for termianting session.
     *
     * @return WP_HTTP_Response
     */
    public static function ajaxLogout() {
        //Nonce check
        check_ajax_referer( 'gbgbg-ajax-logout', 'security', true );

        wp_logout();
        return self::ajaxResponse(true, '');
        wp_die();
    }
}
