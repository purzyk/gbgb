<?php

namespace Basecamp\Api;

use \WP_REST_Server;
use \WP_REST_Controller;
use \WP_REST_Response;
use Basecamp\Utils\Utils;

/**
 * Configuration for Trainers List Posts API
 */
class TrainersListController extends WP_REST_Controller
{

    /**
    * Get trainers
    *
    * @param WP_REST_Request
    *
    * @return WP_REST_Response
    */
    public static function getTrainers($request)
    {

        $page = $request->get_param("page");
        $perPage = $request->get_param("itemsPerPage");
        $offset = Utils::getPaginationOffset($page, $perPage);

        $args = [
            'post_type' => 'trainer',
            'posts_per_page' => $perPage,
            'offset' => $offset,
            'orderby' => 'title',
            'order' => 'ASC'
        ];

        // Sort
        $sortBy = $request->get_param("sort_by");
        $sortOrder = $request->get_param("sort_dir");

        if ($sortBy && $sortOrder) {
            $args['order'] = $sortOrder;

            if ($sortBy == 'trainerFullname') {
                $args['orderby'] = 'title';
            } else {
                $args['meta_key'] = $sortBy;
                $args['orderby'] = 'meta_value';
            }
        }

        // Filters
        $filterValue = $request->get_param('trainerFullname');
        if($filterValue) {
            $args['s'] = $filterValue;
        }
        $filters = ['trainerTrackName', 'trainerTown'];
        $metaQuery = [];
        foreach ($filters as $filter) {
            $filterValue = $request->get_param($filter);
            if ($filterValue) {
                $metaQuery[] = [
                    'key' => $filter,
                    'value' => $filterValue,
                    'compare' => '=',
                ];
            }
        }
        if (!empty($metaQuery)) {
            $metaQuery['relation'] = 'AND';
            $args['meta_query'] = $metaQuery;
        }

        $query = new \WP_Query($args);
        $posts = $query->get_posts();

        $total_posts = $query->found_posts;
        $pageCount = ceil ($total_posts / $perPage);

        $trainers = [];
        if ($total_posts > 0) {
            foreach ($posts as $key => $value) {
                $post_id = $value->ID;
                $trainers[] = [
                    'fullname' => get_the_title($post_id),
                    'title' => get_field('trainerTitle', $post_id),
                    'license' => get_field('trainerLicense', $post_id),
                    'trackname' => get_field('trainerTrackName', $post_id),
                    'town' => get_field('trainerTown', $post_id),
                    'tel_number' => get_field('trainerTelNumber', $post_id) ? get_field('trainerTelNumber', $post_id) : '-',
                    'mobile_number' => get_field('trainerMobileNumber', $post_id) ? get_field('trainerMobileNumber', $post_id) : '-',
                ];
            }
        }
        $response = [
            "items" => $trainers,
            "meta" => [
                "count" => $total_posts,
                "pageCount" => $pageCount
            ]
        ];
        $response = new WP_REST_Response($response);
        $response->set_status(200);
        return $response;
    }

    /**
    * Get filter autocompletes
    *
    * @param WP_REST_Request
    *
    * @return WP_REST_Response
    */
    public static function getAutocompletes($request)
    {
        $args = [
            'post_type' => 'trainer',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ];
        $query = new \WP_Query($args);
        $posts = $query->get_posts();

        $tracknames = [];
        $tracknamesAdded = [];
        $towns = [];
        $townsAdded = [];
        $fullnames = [];

        foreach ($posts as $key => $value) {
            $post_id = $value->ID;
            $town = get_field('trainerTown', $post_id);
            $trackname = get_field('trainerTrackName', $post_id);
            $fullname = get_the_title($post_id);

            if (!in_array($town, $townsAdded)) {
                $towns[] = [
                    "value" => $town,
                    "label" => $town
                ];
                $townsAdded[] = $town;
            }
            if (!in_array($trackname, $tracknamesAdded)) {
                $tracknames[] = [
                    "value" => $trackname,
                    "label" => $trackname
                ];
                $tracknamesAdded[] = $trackname;
            }
            if (!in_array($fullname, $fullnames)) {
                $fullnames[] = $fullname;
            }
        }
        $response = [
            'towns' => $towns,
            'tracknames' => $tracknames,
            'fullnames' => $fullnames,
        ];

        $response = new WP_REST_Response($response);
        $response->set_status(200);
        return $response;
    }
}
