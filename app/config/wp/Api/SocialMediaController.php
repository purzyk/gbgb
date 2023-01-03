<?php

namespace Basecamp\Api;

use \WP_REST_Server;
use \WP_REST_Controller;
use \WP_REST_Response;
use Basecamp\Models\Socialmediafeed;
use Basecamp\Models\Socialmediapost;
use Basecamp\Utils\Utils;

/**
 * Configuration for Social Media Posts API
 */
class SocialMediaController extends WP_REST_Controller
{


   /**
   * Get posts for a social media feed
   *
   * @param WP_REST_Request
   *
   * @return WP_REST_Response
   */
  public static function getFeed($request)
  {
    $feedSlug = $request->get_param("feedSlug");
    $postIds = $request->get_param("postIds");
    $perPage = (int)$request->get_param("perPage");

    $offset = Utils::getPaginationOffset((int)$request->get_param("page"),$perPage);

    $args = [
        'post_type' => 'socialmediapost',
        'posts_per_page' => $perPage,
        'offset' => $offset,
        'orderby' => [
          'date' => 'DESC',
        ],
      ];

      if (!empty($postIds)) {
        $args['post__in'] = $postIds;
        $args['orderby'] = 'post__in';
      }

      if (isset($feedSlug)) {
        $args['tax_query'] = [
          [
            'taxonomy' => 'socialmediafeed',
            'terms' => $feedSlug
          ]
        ];
      }


    $query = new \WP_Query($args);
    $posts = $query->get_posts();

    $total_posts = $query->found_posts;
    $max_pages = $query->max_num_pages;

    if ($total_posts > 0) {
        foreach ($posts as $key => $value) {
            if (!isset($posts[$key]->acf)) {
                $posts[$key]->acf = [];
            }
            $post_id = $value->ID;
            $network = get_field('socialMediaNetwork',$post_id);
            $thumbnail = get_the_post_thumbnail_url($post_id);
            $content = $posts[$key]->post_content;
            $posts[$key]->post_content = Utils::formatSocialMediaContent($content, $network);
            $posts[$key]->thumbnail = $thumbnail;
            $posts[$key]->acf['socialMediaSource'] = get_field('socialMediaSource',$post_id);
            $posts[$key]->acf['socialMediaUserName'] = get_field('socialMediaUserName',$post_id);
            $posts[$key]->acf['socialMediaUserNicename'] = get_field('socialMediaUserNicename',$post_id);
            $posts[$key]->acf['socialMediaDate'] = Utils::formatDate(get_field('socialMediaDate',$post_id));
            $posts[$key]->acf['socialMediaNetwork'] = $network;
        }
    }
    $response = new WP_REST_Response($posts);
    // Use X-WP headers for pagination (as used in default WP-JSON behaviour)
    $response->header('X-WP-Total', (int)$total_posts);
    $response->header('X-WP-TotalPages', (int)$max_pages);
    $response->set_status(200);
    return $response;
  }
}
