<?php

namespace Basecamp\Api;

use \WP_REST_Server;
use \WP_REST_Controller;
use \WP_REST_Response;
use \WP_User;
use \Basecamp\OAuthClient;

/**
 * Configuration for Kennel Posts API
 */
class KennelController extends WP_REST_Controller
{

    /**
     * Generate error response
     *
    * @return WP_REST_Response
     */
    public static function errorResponse() {
        $response = new WP_REST_Response('You must log in to access this feature');
        $response->set_status(400);
        return $response;
    }

    /**
     * Get kennel post Id
     *
     * @param int $userId
     * @return int
     */
    public static function getKennelId($userId) {
        //Match by user id
        $args = [
            'post_type' => 'kennel',
            'posts_per_page' => 1,
          ];
        $args['meta_query'] = array(array(
            'key'	 	=> 'userId',
            'value'	  	=> $userId,
            'compare' 	=> '=',
        ));
        $query = new \WP_Query($args);
        $posts = $query->get_posts();

        //Make sure there is no orphan in imported data with the corresponding email
        if(empty($posts)) {
            $user = new WP_User($userId);
            $args = [
                'post_type' => 'kennel',
                'posts_per_page' => 1,
                'title' => $user->user_email
              ];
            $args['meta_query'] = array(array(
                'key'	 	=> 'userId',
                'value'	  	=> 0,
                'compare' 	=> '=',
            ));
            $query = new \WP_Query($args);
            $posts = $query->get_posts();
            if(!empty($posts)){
                update_field('userId',$userId,$posts[0]->ID);
            }
        }

        //Create kennel if none found.
        if(empty($posts)) {
            $user = new WP_User($userId);
            $postId = wp_insert_post(array(
                'post_title' => $user->user_email,
                'post_type' => 'kennel',
                'post_status' => 'publish'
                )
            );
            update_field('userId',$userId,$postId);
            return $postId;
        }
        return $posts[0]->ID;
    }

    /**
     * Get kennel array key by dog id
     *
     * @param array $kennel
     * @param int $dogId
     * @return int
     */
    public static function getDogKeyById($kennel, $dogId) {
        $result = false;
        foreach ($kennel as $key => $value) {
            if($value['dogId'] === $dogId){
                $result = $key;
            }
        }
        return $result;
    }

   /**
   * Get kennel array
   *
   * @param int
   *
   * @return array
   */
  public static function getDogsArray($userId)
  {
    $kennelId = self::getKennelId($userId);
    $dogs = get_field('dogs',$kennelId);
    if(empty($dogs)) {
        $dogs = array();
    }
    return $dogs;
  }

   /**
   * Get kennel endpoint
   *
   * @param WP_REST_Request
   *
   * @return WP_REST_Response
   */
  public static function getDogs($request)
  {
    $userId = get_current_user_id();
    if(!$userId){
        return self::errorResponse();
    }

    $kennel = self::getDogsArray($userId);

    $response = new WP_REST_Response($kennel);
    $response->set_status(200);
    return $response;
  }

   /**
   * Add dog to kennel
   *
   * @param WP_REST_Request
   *
   * @return WP_REST_Response
   */
  public static function addDog($request)
  {
    $userId = get_current_user_id();
    if(!$userId){
        return self::errorResponse();
    }

    $dogId = $request->get_param("dogId");

    $kennelId = self::getKennelId($userId);
    $kennel = self::getDogsArray($userId);

    if(self::getDogKeyById($kennel, $dogId)===false){
        $kennel[] = array(
            'dogId' => $dogId,
            'dogNotes' => '',
            'dogNotification' => true,
        );
        $user = new WP_User($userId);
        self::bindDog($user,$dogId);
        update_field('dogs',$kennel,$kennelId);
    }

    $response = new WP_REST_Response($kennel);
    $response->set_status(200);
    return $response;
  }

   /**
   * Remove dog
   *
   * @param WP_REST_Request
   *
   * @return WP_REST_Response
   */
  public static function removeDog($request)
  {
    $userId = get_current_user_id();
    if(!$userId){
        return self::errorResponse();
    }

    $dogId = $request->get_param("dogId");

    $kennelId = self::getKennelId($userId);
    $kennel = self::getDogsArray($userId);

    $key = self::getDogKeyById($kennel, $dogId);
    if($key !== false) {
        unset($kennel[$key]);
        $user = new WP_User($userId);
        self::unbindDog($user,$dogId);
    }
    update_field('dogs',$kennel,$kennelId);

    $response = new WP_REST_Response($kennel);
    $response->set_status(200);
    return $response;
  }

   /**
   * Edit dog
   *
   * @param WP_REST_Request
   *
   * @return WP_REST_Response
   */
  public static function editDog($request)
  {
    $userId = get_current_user_id();
    if(!$userId){
        return self::errorResponse();
    }

    $dogId = $request->get_param("dogId");
    $notes = filter_var( $request->get_param("notes"), FILTER_SANITIZE_STRING );
    $notification = filter_var( $request->get_param("notification"), FILTER_VALIDATE_BOOLEAN );

    $kennelId = self::getKennelId($userId);
    $kennel = self::getDogsArray($userId);

    $key = self::getDogKeyById($kennel, $dogId);
    if($key !== false) {
        if($notes !== 'null'){
            $kennel[$key]['dogNotes'] = $notes;
        }
        $kennel[$key]['dogNotification'] = $notification;
        $user = new WP_User($userId);
        if($notification) {
            self::bindDog($user,$dogId);
        } else {
            self::unbindDog($user,$dogId);
        }
    }
    update_field('dogs',$kennel,$kennelId);


    $response = new WP_REST_Response($kennel);
    $response->set_status(200);
    return $response;
  }

   /**
   * Edit dog
   *
   * @param WP_REST_Request
   *
   * @return WP_REST_Response
   */
  public static function bindAllDogs($request) {
    $userId = $request->get_param("userId");
    $user = new WP_User($userId);
    if(empty($user->user_email)){
        $response = new WP_REST_Response('false');
        $response->set_status(200);
        return $response;
    }
    $kennel = self::getDogsArray($userId);
    $dogsArray = [];
    foreach ($kennel as $dog) {
        if($dog['dogNotification']) {
            $dogsArray[]= $dog['dogId'];
        }
    }
    $body = array(
        "email" => $user->user_email,
        "firstName" => $user->get('first_name'),
        "lastName" => $user->get('last_name'),
        "dogs" => $dogsArray
    );
   $result =  self::fireRequestToNotificationApi('PUT', $body);

    $response = new WP_REST_Response($result);
    $response->set_status(200);
    return $response;
  }

  /**
   * Bind dog notification in backend
   *
   * @param WP_User $user
   * @param int $dogId
   * @return bool
   */
  private static function bindDog($user, $dogId) {
    $body = array(
        "email" => $user->user_email,
        "firstName" => $user->get('first_name'),
        "lastName" => $user->get('last_name'),
        "dogs" => [$dogId]
    );
    return self::fireRequestToNotificationApi('PUT', $body);
  }
  /**
   * Unbind dog notification in backend
   *
   * @param WP_User $user
   * @param int $dogId
   * @return bool
   */
  private static function unbindDog($user, $dogId) {
    $body = array(
        "email" => $user->user_email,
        "dogs" => [$dogId]
    );
    return self::fireRequestToNotificationApi('DELETE', $body);
  }

  /**
   * Get Oauth token
   *
   * @param bool $forceRefresh
   * @return bool
   */
  private static function getNotificationAuthToken($forceRefresh) {
    $apiUrl = get_field('dogNotificationApiUrl','option');
    $id = get_field('dogNotificationApiId','option');
    $secret = get_field('dogNotificationApiSecret','option');
    $config = array(
        'forceRefresh' => $forceRefresh,
    );
    return OAuthClient::getToken($apiUrl, $id,$secret,'client_credentials',$config);
  }

  /**
   * Fire a request to notification API
   *
   * @param string $type  PUT or DELETE
   * @param array    $body  JSON
   * @return bool
   */
  private static function fireRequestToNotificationApi($type, $body) {
    $url = trailingslashit(get_field('liveResultsAPIUrl','option'));
    $client = new \GuzzleHttp\Client(['base_uri' => $url, 'http_errors' => false]);
    $headers = array();
    $headers['Authorization'] = 'Bearer ' .  self::getNotificationAuthToken(false);

    $response = $client->request($type, 'user/bind/dogs', array(
        'json' => $body,
        'headers' => $headers
    ));
    if($response->getStatusCode() === 401){
        //Retry with fresh token
        $headers['Authorization'] = 'Bearer ' .  self::getNotificationAuthToken(true);
        $response = $client->request($type, '/user/bind/dogs', array(
            'json' => $body,
            'headers' => $headers
        ));
    }
    return $response->getStatusCode() === 204;
  }

    /**
     * Get courses array key by course id
     *
     * @param array $courses
     * @param int $courseId
     * @return int
     */
    public static function getCourseKeyById($courses, $courseId) {
        $result = false;
        foreach ($courses as $key => $value) {
            if($value['courseId'] === $courseId){
                $result = $key;
            }
        }
        return $result;
    }

  /**
   * Get courses array array
   *
   * @param int
   *
   * @return array
   */
  public static function getCoursesArray($userId)
  {
    $kennelId = self::getKennelId($userId);
    $courses = get_field('courses',$kennelId);
    if(empty($courses)) {
        $courses = array();
    }
    return $courses;
  }


   /**
   * Get courses endpoint
   *
   * @param WP_REST_Request
   *
   * @return WP_REST_Response
   */
  public static function getCourses($request)
  {
    $userId = get_current_user_id();
    if(!$userId){
        return self::errorResponse();
    }

    $courses = self::getCoursesArray($userId);

    $response = new WP_REST_Response($courses);
    $response->set_status(200);
    return $response;
  }

   /**
   * Add course
   *
   * @param WP_REST_Request
   *
   * @return WP_REST_Response
   */
  public static function addCourse($request)
  {
    $userId = get_current_user_id();
    if(!$userId){
        return self::errorResponse();
    }

    $courseId = $request->get_param("courseId");

    $kennelId = self::getKennelId($userId);
    $courses = self::getCoursesArray($userId);

    if(self::getCourseKeyById($courses, $courseId)===false){
        $courses[] = array(
            'courseId' => $courseId,
        );

        update_field('courses',$courses,$kennelId);
    }

    $response = new WP_REST_Response($courses);
    $response->set_status(200);
    return $response;
  }

   /**
   * Remove course
   *
   * @param WP_REST_Request
   *
   * @return WP_REST_Response
   */
  public static function removeCourse($request)
  {
    $userId = get_current_user_id();
    if(!$userId){
        return self::errorResponse();
    }

    $courseId = $request->get_param("courseId");

    $kennelId = self::getKennelId($userId);
    $courses = self::getCoursesArray($userId);

    $key = self::getCourseKeyById($courses, $courseId);
    if($key !== false) {
        unset($courses[$key]);
    }
    update_field('courses',$courses,$kennelId);

    $response = new WP_REST_Response($courses);
    $response->set_status(200);
    return $response;
  }
}
