<?php

namespace Basecamp\Api;

use \WP_REST_Server;
use \WP_REST_Controller;
use \WP_REST_Response;

/**
 * Configuration for Race Course Posts API
 */
class RaceCourseController extends WP_REST_Controller
{

   /**
   * Get race courses
   *
   * @param WP_REST_Request
   *
   * @return WP_REST_Response
   */
    public static function getCourses($request)
    {
        $args = [
            'post_type' => 'racecourse',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ];

        $query = new \WP_Query($args);
        $posts = $query->get_posts();

        $total_posts = $query->found_posts;
        $courses = [];
        if ($total_posts > 0) {
            foreach ($posts as $key => $value) {
                $post_id = $value->ID;

                $promo = get_field('raceCoursePromotions', $post_id);
                $promo_items = $promo['items'];
                $promo_order = [];
                if(is_array($promo_items)){
                    foreach ($promo_items as $i => $row) {
                        $promo_order[$i] = strtotime($row['expiry_date']);
                    }
                    array_multisort($promo_order, SORT_ASC, $promo_items);
                }


                $course = [
                    'courseId' => $post_id,
                    'name' => get_the_title($post_id),
                    'description' => get_field('raceCourseDescription', $post_id),
                    'email' => get_field('raceCourseEmail', $post_id),
                    'link' => get_field('raceCourseLink', $post_id),
                    'phone' => get_field('raceCoursePhone', $post_id),
                    'address' => get_field('raceCourseAddress', $post_id),
                    'location' => get_field('raceCourseLocation', $post_id),
                    'schedule' => get_field('raceCourseSchedule', $post_id),
                    'socialMedia' => get_field('raceCourseSocialMedia', $post_id),
                    'distance' => "0",
                    'promoHeading' => $promo['heading'],
                    'promotions' => $promo_items,
                ];

                $courses[] = $course;
            }
        }
        $response = new WP_REST_Response($courses);
        $response->set_status(200);
        return $response;
    }
}
