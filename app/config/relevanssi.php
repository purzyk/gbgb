<?php

namespace Basecamp;
use \WP_Query;


/**
 * Setup Relevanssi integration
 */
class Relevanssi
{

    public static $postCount = 0;
    public static $pagesCount = 0;

    /**
     * Init
     */
    public static function init()
    {
        add_action( 'relevanssi_modify_wp_query', array(__CLASS__, 'get_relevanssi_count'), 10, 1 );
    }

    /**
     * Get relevanssi counts
     *
     * @param WP_Query $query
     */
    public static function get_relevanssi_count( $query )
    {
        $clonedQuery = new WP_Query($query->query_vars);
        $clonedQuery->set('posts_per_page', -1);
        $clonedQuery->parse_query_vars();
        $allPosts = relevanssi_do_query($clonedQuery);

        $totalPosts = count($allPosts);
        $totalPages = $query->get("posts_per_page");

        self::$postCount = $totalPosts;
        self::$pagesCount = ceil($totalPosts / $totalPages) ;

        return $query;
    }

    /**
     * Gets counts for last search
     *
     * @return array
     */
    public static function getCountsForLastSearch() {
        return [
            "totalCount" => self::$postCount,
            "totalPages" => self::$pagesCount
        ];
    }
}

