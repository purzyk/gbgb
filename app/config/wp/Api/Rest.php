<?php

namespace Basecamp\Api;
use Basecamp\Utils\Utils;


/**
 * Setup REST services
 */
class Rest
{
    const ROUTE_NAMESPACE = 'wp/v2';

    /**
     * Init
     */
    public static function init()
    {
        ////////////////////////////////////////////////////////////////////////
        // Register REST routes
        ////////////////////////////////////////////////////////////////////////
        add_action('rest_api_init', function () {

            // SocialMediaController::getFeed
            // /wp-json/wp/v2/social-media-feed
            register_rest_route(static::ROUTE_NAMESPACE, '/social-media-feed', array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => ['\\Basecamp\\Api\\SocialMediaController', 'getFeed'],
                )
            ));

            // SocialMediaController::getCourses
            // /wp-json/wp/v2/race-courses
            register_rest_route(static::ROUTE_NAMESPACE, '/race-courses', array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => ['\\Basecamp\\Api\\RaceCourseController', 'getCourses'],
                )
            ));

            // BigRaceWinnerController::getBigRaceWinners
            // /wp-json/wp/v2/big-race-winners
            register_rest_route(static::ROUTE_NAMESPACE, '/big-race-winners', array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => ['\\Basecamp\\Api\\BigRaceWinnerController', 'getBigRaceWinners'],
                )
            ));

            // BigRaceWinnerController::getAutocompletes
            // /wp-json/wp/v2/big-race-winners/autocompletes/
            register_rest_route(static::ROUTE_NAMESPACE, 'big-race-winners/autocompletes', array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => ['\\Basecamp\\Api\\BigRaceWinnerController', 'getAutocompletes'],
                )
            ));

            // TrainersListController::getTrainers
            // /wp-json/wp/v2/trainers-list
            register_rest_route(static::ROUTE_NAMESPACE, '/trainers-list', array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => ['\\Basecamp\\Api\\TrainersListController', 'getTrainers'],
                )
            ));

            // TrainersListController::getAutocompletes
            // /wp-json/wp/v2/trainers-list/autocompletes/
            register_rest_route(static::ROUTE_NAMESPACE, 'trainers-list/autocompletes', array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => ['\\Basecamp\\Api\\TrainersListController', 'getAutocompletes'],
                )
            ));

            // KennelController::getKennel
            // /wp-json/wp/v2/mydogs
            register_rest_route(static::ROUTE_NAMESPACE, 'mydogs', array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => ['\\Basecamp\\Api\\KennelController', 'getDogs'],
                )
            ));

            if(get_field('enableUserSyncingEndpoint','option')) {
                // KennelController::bindAllDogs
                // /wp-json/wp/v2/mydogs/sync
                register_rest_route(static::ROUTE_NAMESPACE, 'mydogs/sync', array(
                    array(
                        'methods' => \WP_REST_Server::READABLE,
                        'callback' => ['\\Basecamp\\Api\\KennelController', 'bindAllDogs'],
                    )
                ));
            }

            // KennelController::addDog
            // /wp-json/wp/v2/mydogs/add
            register_rest_route(static::ROUTE_NAMESPACE, 'mydogs/add', array(
                array(
                    'methods' => \WP_REST_Server::EDITABLE,
                    'callback' => ['\\Basecamp\\Api\\KennelController', 'addDog'],
                )
            ));

            // KennelController::removeDog
            // /wp-json/wp/v2/mydogs/remove
            register_rest_route(static::ROUTE_NAMESPACE, 'mydogs/remove', array(
                array(
                    'methods' => \WP_REST_Server::DELETABLE,
                    'callback' => ['\\Basecamp\\Api\\KennelController', 'removeDog'],
                )
            ));

            // KennelController::editDog
            // /wp-json/wp/v2/mydogs/edit
            register_rest_route(static::ROUTE_NAMESPACE, 'mydogs/edit', array(
                array(
                    'methods' => \WP_REST_Server::EDITABLE,
                    'callback' => ['\\Basecamp\\Api\\KennelController', 'editDog'],
                )
            ));

            // KennelController::getCourses
            // /wp-json/wp/v2/mycourses
            register_rest_route(static::ROUTE_NAMESPACE, 'mycourses', array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => ['\\Basecamp\\Api\\KennelController', 'getCourses'],
                )
            ));

            // KennelController::addCourse
            // /wp-json/wp/v2/mycourses/add
            register_rest_route(static::ROUTE_NAMESPACE, 'mycourses/add', array(
                array(
                    'methods' => \WP_REST_Server::EDITABLE,
                    'callback' => ['\\Basecamp\\Api\\KennelController', 'addCourse'],
                )
            ));

            // KennelController::removeCourse
            // /wp-json/wp/v2/mycourses/remove
            register_rest_route(static::ROUTE_NAMESPACE, 'mycourses/remove', array(
                array(
                    'methods' => \WP_REST_Server::DELETABLE,
                    'callback' => ['\\Basecamp\\Api\\KennelController', 'removeCourse'],
                )
            ));

            // OwnerPhotoController::uploadPhoto
            // /wp-json/wp/v2/ownerphotos/upload
            register_rest_route(static::ROUTE_NAMESPACE, 'ownerphotos/upload', array(
                array(
                    'methods' => \WP_REST_Server::EDITABLE,
                    'callback' => ['\\Basecamp\\Api\\OwnerPhotoController', 'uploadPhoto'],
                )
            ));

            // OwnerPhotoController::addPhoto
            // /wp-json/wp/v2/ownerphotos/add
            register_rest_route(static::ROUTE_NAMESPACE, 'ownerphotos/add', array(
                array(
                    'methods' => \WP_REST_Server::EDITABLE,
                    'callback' => ['\\Basecamp\\Api\\OwnerPhotoController', 'addPhoto'],
                )
            ));

            // OurDogsController::getEntries
            // /wp-json/wp/v2/ourdogs
            register_rest_route(static::ROUTE_NAMESPACE, 'ourdogs', array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => ['\\Basecamp\\Api\\OurDogsController', 'getEntries'],
                )
            ));

            // SearchAutcompleteController::getSuggestions
            // /wp-json/wp/v2/searchautocompletes
            register_rest_route(static::ROUTE_NAMESPACE, 'searchautocompletes', array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => ['\\Basecamp\\Api\\SearchAutcompleteController', 'getSuggestions'],
                )
            ));
        });

        ////////////////////////////////////////////////////////////////////////
        // Add custom rest response fields
        ////////////////////////////////////////////////////////////////////////
        add_action('rest_api_init', function() {
            register_rest_field(
                'post',
                'featuredImageSrc',
                array(
                    'get_callback'    => ['\\Basecamp\\Api\\Rest', 'getFeaturedImageSrc'],
                    'update_callback' => null,
                    'schema'          => null,
                )
            );
            register_rest_field(
                'post',
                'formattedDate',
                array(
                    'get_callback'    => ['\\Basecamp\\Api\\Rest', 'getFormattedDate'],
                    'update_callback' => null,
                    'schema'          => null,
                )
            );
        });
        ////////////////////////////////////////////////////////////////////////
        // Add custom rest arguments
        ////////////////////////////////////////////////////////////////////////
        add_filter( 'rest_post_query', function( $args ) {
            // get sanitized custom arguments from request
            $months = isset($_GET['months']) ? filter_var_array ( $_GET['months'], FILTER_SANITIZE_NUMBER_INT ) : false;
            $years = isset($_GET['years']) ? filter_var_array ( $_GET['years'], FILTER_SANITIZE_NUMBER_INT ) : false;
            $contentTypes = isset($_GET['content_types']) ? filter_var_array ( $_GET['content_types'], FILTER_SANITIZE_STRING ) : false;

            // return only posts with given contenttypes
            if($contentTypes) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'contenttype',
                        'field'    => 'term_id',
                        'terms'    => $contentTypes,
                    ),
                );
            }

            // return only posts from given months within given years
            // if no years in request arguments default to current year
            if($months){
                $years = $years ? $years : [date("Y")];
                $args['date_query'] = array(
                    'relation' => 'OR',
                );
                foreach ($years as $year) {
                    foreach ($months as $month) {
                        $args['date_query'] []= array(
                            'year' => $year,
                            'month' => $month
                    );
                }
            }
            // return only posts from given years if no months in request arguments
            } else if($years) {
                $args['date_query'] = array(
                    'relation' => 'OR',
                );
                foreach ($years as $year) {
                    $args['date_query'] []= array(
                        'year' => $year,
                    );
                }
            }
            return $args;
    } );
    }

    public static function getFeaturedImageSrc($object, $field_name, $request)
    {
        return array(
            'normal' => wp_get_attachment_image_src( $object['featured_media'], 'latestNews')[0],
            'retina' => wp_get_attachment_image_src( $object['featured_media'], 'latestNewsRetina')[0],
        );
    }

    public static function getFormattedDate($object, $field_name, $request)
    {
        return Utils::formatDate($object['date'],'D j M Y');
    }
}




