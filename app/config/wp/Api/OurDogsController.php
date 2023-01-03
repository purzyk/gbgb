<?php

namespace Basecamp\Api;

use \WP_REST_Server;
use \WP_REST_Controller;
use \WP_REST_Response;
use \WP_User;
use Basecamp\Utils\Utils;

/**
 * Configuration for Social Media Posts API
 */
class OurDogsController extends WP_REST_Controller
{


   /**
   * Get posts for a social media feed
   *
   * @param WP_REST_Request
   *
   * @return WP_REST_Response
   */
  public static function getEntries($request)
  {
    $pageId = (int) $request->get_param("pageId");
    $page = (int) $request->get_param("page");
    $perPage = (int) $request->get_param("perPage");

    $offset = Utils::getPaginationOffset($page,$perPage);

    $items = get_field('ourDogs', $pageId);
    $total_posts = count($items);
    $max_pages =ceil($total_posts/ $perPage);
    $items = array_slice( $items, $offset, $perPage);

    $posts = array();
    foreach ($items as $item) {
        $post = get_post($item['postId']);

        if($item['acf_fc_layout'] === 'socialmediapost') {
            $thumbId = get_post_thumbnail_id($item['postId']);
            if($item['layout'] === 'regular') {
                $item['thumbnail'] = wp_get_attachment_image_src($thumbId, 'ourDogs')[0];
                $item['thumbnailRetina'] = wp_get_attachment_image_src($thumbId, 'ourDogsRetina')[0];
            } else {
                $item['thumbnail'] = wp_get_attachment_image_src($thumbId, 'ourDogsTall')[0];
                $item['thumbnailRetina'] = wp_get_attachment_image_src($thumbId, 'ourDogsTallRetina')[0];
            }
        }
        if($item['acf_fc_layout'] === 'ownerphoto') {
            $thumbId = get_field('image', $item['postId'])['ID'];
            if($item['layout'] === 'regular') {
                $item['thumbnail'] = wp_get_attachment_image_src($thumbId, 'ourDogs')[0];
                $item['thumbnailRetina'] = wp_get_attachment_image_src($thumbId, 'ourDogsRetina')[0];
            } else {
                $item['thumbnail'] = wp_get_attachment_image_src($thumbId, 'ourDogsTall')[0];
                $item['thumbnailRetina'] = wp_get_attachment_image_src($thumbId, 'ourDogsTallRetina')[0];
            }
            if(empty($item['race_name'])) {
                $item['race_name'] = get_field('race_name', $item['postId']);
            }
            if(empty($item['pet_name'])) {
                $item['pet_name'] = get_field('pet_name', $item['postId']);
            }
        }
        $posts[] = $item;
    }

    $response = new WP_REST_Response($posts);
    // Use X-WP headers for pagination (as used in default WP-JSON behaviour)
    $response->header('X-WP-Total', (int)$total_posts);
    $response->header('X-WP-TotalPages', (int)$max_pages);
    $response->set_status(200);
    return $response;
  }
}
