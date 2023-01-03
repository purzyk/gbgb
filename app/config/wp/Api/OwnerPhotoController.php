<?php

namespace Basecamp\Api;

use \WP_REST_Server;
use \WP_REST_Controller;
use \WP_REST_Response;
use \WP_User;
use Basecamp\GBGBUserRoles;

/**
 * Configuration for OnwerPhoto Posts API
 */
class OwnerPhotoController extends WP_REST_Controller
{

    /**
     * Generate error response
     *
    * @return WP_REST_Response
     */
    public static function errorResponse($msg = 'Log in as owner to access this') {
        $response = new WP_REST_Response($msg);
        $response->set_status(400);
        return $response;
    }

    /**
   * Upload handle for the dropzone
   *
   * @param WP_REST_Request
   *
   * @return WP_REST_Response
   */
  public static function uploadPhoto($request)
  {
    $userId = get_current_user_id();
    $isOwner = GBGBUserRoles::currentUserHasRole('owner');
    if(!$userId || !$isOwner){
        return self::errorResponse();
    }

    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $postId = media_handle_upload('file', 0);

    $response = array(
        'imageSrc' => wp_get_attachment_image_src($postId, 'doubleImage'),
        'imageId' => $postId,
    );

    $response = new WP_REST_Response($response);
    $response->set_status(200);
    return $response;
  }

    /**
   * Add photo post with caption
   *
   * @param WP_REST_Request
   *
   * @return WP_REST_Response
   */
  public static function addPhoto($request)
  {
    $userId = get_current_user_id();
    $isOwner = GBGBUserRoles::currentUserHasRole('owner');
    if(!$userId || !$isOwner){
        return self::errorResponse();
    }

    $mediaId = filter_var( $request->get_param("mediaId"), FILTER_VALIDATE_INT );
    $raceName = filter_var( $request->get_param("raceName"), FILTER_SANITIZE_STRING );
    $petName = filter_var( $request->get_param("petName"), FILTER_SANITIZE_STRING );

    $user = new WP_User($userId);
    $title = $user->user_email . ' : '. $raceName . ' - ' . $petName;

    $postId = wp_insert_post(array(
        'post_title' => $title,
        'post_type' => 'ownerphoto',
        'post_status' => 'publish'
        )
    );
    update_field('image',$mediaId,$postId);
    update_field('race_name',$raceName,$postId);
    update_field('pet_name',$petName,$postId);

    $response = new WP_REST_Response($postId);
    $response->set_status(200);
    return $response;
  }

}
