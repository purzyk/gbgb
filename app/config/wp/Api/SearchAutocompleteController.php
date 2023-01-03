<?php

namespace Basecamp\Api;

use \WP_REST_Server;
use \WP_REST_Controller;
use \WP_REST_Response;

/**
 * Configuration for Social Media Posts API
 */
class SearchAutcompleteController extends WP_REST_Controller
{


   /**
   * Get suggestions for search autcomplete
   *
   * @param WP_REST_Request
   *
   * @return WP_REST_Response
   */
  public static function getSuggestions($request)
  {
    // Get all the relevanssi words
    global $wpdb;
    $suggestions = $wpdb->get_col( 'SELECT DISTINCT term FROM '.$wpdb->prefix.'relevanssi' );

    $response = new WP_REST_Response($suggestions);
    $response->set_status(200);
    return $response;
  }
}
