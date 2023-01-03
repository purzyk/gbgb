<?php

namespace Basecamp\Api;

use \WP_REST_Server;
use \WP_REST_Controller;
use \WP_REST_Response;
use Basecamp\Utils\Utils;

/**
 * Configuration for Big Race Winners Posts API
 */
class BigRaceWinnerController extends WP_REST_Controller
{

   /**
   * Get race courses
   *
   * @param WP_REST_Request
   *
   * @return WP_REST_Response
   */
  public static function getBigRaceWinners($request)
  {

    $page = $request->get_param("page");
    $perPage = $request->get_param("itemsPerPage");
    $offset = Utils::getPaginationOffset($page,$perPage);

    $args = [
        'post_type' => 'bigracewinner',
        'posts_per_page' => $perPage,
        'offset' => $offset,
        'meta_key' => 'bigRaceWinnerYear',
        'orderby' => 'meta_value',
        'order' => 'DESC'
    ];

    // Sort.
    $sortBy = $request->get_param("sort_by");
    $sortOrder = $request->get_param("sort_dir");

    if ($sortBy && $sortOrder) {
        $args['meta_key']	= $sortBy;
        $args['orderby']	= 'meta_value';
        $args['order']		= $sortOrder;
    }

    // Filters.
    $filters = array('bigRaceWinnerRace','bigRaceWinnerYear','bigRaceWinnerName','bigRaceWinnerSire','bigRaceWinnerDam','bigRaceWinnerTrainer');
    $metaQuery = [];
    foreach ($filters as $filter) {
        $filterValue = $request->get_param($filter);
        if($filterValue) {
            $metaQuery[] = [
                'key'	 	=> $filter,
                'value'	  	=> $filterValue,
                'compare' 	=> '=',
            ];
        }
    }
    if (!empty($metaQuery)){
        $metaQuery['relation'] = 'AND';
        $args['meta_query'] = $metaQuery;
    }

    $query = new \WP_Query($args);
    $posts = $query->get_posts();

    $total_posts = $query->found_posts;
    $pageCount = ceil ($total_posts / $perPage);

    $winners = array();
    if ($total_posts > 0) {
        foreach ($posts as $key => $value) {
            $post_id = $value->ID;
            $winner = array(
                'year' => get_field('bigRaceWinnerYear',$post_id),
                'race' => get_field('bigRaceWinnerRace',$post_id),
                'track' => get_field('bigRaceWinnerTrack',$post_id),
                'distance' => get_field('bigRaceWinnerDistance',$post_id),
                'name' => get_field('bigRaceWinnerName',$post_id),
                'sire' => get_field('bigRaceWinnerSire',$post_id),
                'dam' => get_field('bigRaceWinnerDam',$post_id),
                'trainer' => get_field('bigRaceWinnerTrainer',$post_id),
                'time' => get_field('bigRaceWinnerTime',$post_id),
                'SP' => get_field('bigRaceWinnerSP',$post_id),
            );

            $winners[] = $winner;
        }
    }
    $response = array(
        "items" => $winners,
        "meta" => array(
            "count" => $total_posts,
            "pageCount" => $pageCount
        )
    );
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
        'post_type'         => 'bigracewinner',
        'posts_per_page'    => -1,
        'orderby'           => 'title',
        'order'             => 'ASC'
      ];
    $query = new \WP_Query($args);
    $posts = $query->get_posts();

    $races          = array();
    $racesAdded     = array();
    $years          = array();
    $yearsAdded     = array();
    $names          = array();
    $sires          = array();
    $dams           = array();
    $trainers       = array();

    foreach ($posts as $key => $value) {
        $post_id    = $value->ID;
        $race       = get_field('bigRaceWinnerRace',$post_id);
        $year       = get_field('bigRaceWinnerYear',$post_id);
        $name       = get_field('bigRaceWinnerName',$post_id);
        $sire       = get_field('bigRaceWinnerSire',$post_id);
        $dam        = get_field('bigRaceWinnerDam',$post_id);
        $trainer    = get_field('bigRaceWinnerTrainer',$post_id);

        if(!in_array($race,$racesAdded)){
            $races[] = array("value" => $race, "label" => $race);
            $racesAdded[] = $race;
        }
        if(!in_array($year,$yearsAdded)){
            $years[] = array("value" => $year, "label" => $year);
            $yearsAdded[] = $year;
        }
        if(!in_array($name,$names)){
            $names[] = $name;
        }
        if(!in_array($sire,$sires)){
            $sires[] = $sire;
        }
        if(!in_array($dam,$dams)){
            $dams[] = $dam;
        }
        if(!in_array($trainer,$trainers)){
            $trainers[] = $trainer;
        }
    }
    $response = array(
        'races'     => $races,
        'years'     => $years,
        'names'     => $names,
        'sires'     => $sires,
        'dams'      => $dams,
        'trainers'  => $trainers,
    );

    $response = new WP_REST_Response($response);
    $response->set_status(200);
    return $response;
  }


}

