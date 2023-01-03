<?php

use Basecamp\Utils\Utils;
use Basecamp\Api\KennelController;

// Send notice to user if Timber Class cannot be found
if (!class_exists('Timber')) {
    // Notice on admin pages
    add_action('admin_notices', function () {
        echo '<div class="error">
                  <p>Timber not activated. Make sure you activate the plugin in
                      <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a>
                  </p>
              </div>';
    });

    // Notice on front pages
    add_filter('template_include', function () {
        return get_stylesheet_directory() . '/no-timber.html';
    });

    return 0;
}

/*
|--------------------------------------------------------------------------
| Pagination mid size
|--------------------------------------------------------------------------
|
| Here you can define that how many pagination items there are before and after current
| pagination item in pagination list. First and last item are always visible.
|
| For example:
| $pagination_mid_size = 1 => 1 ... 5 [6] 7 ... 11
| $pagination_mid_size = 2 => 1 ... 4 5 [6] 7 8 ... 11
| $pagination_mid_size = 3 => 1 ... 3 4 5 [6] 7 8 9 ... 11
| $pagination_mid_size = 3 => 1 2 3 4 [5] 6 7 8 ... 11
|
| Supported Mid size value: 1 - n
|
*/

$pagination_mid_size = 2;

$pagination_mid_size += 2; // DON'T TOUCH

/*
|--------------------------------------------------------------------------
| Template paths
|--------------------------------------------------------------------------
|
| Here you may specify an array of paths where to load templates.
|
| Default path: 'resources/views'
|
*/

Timber::$dirname = ['resources/views'];

/**
 * Adds data to Timber context.
 *
 * @param $data
 *
 * @return mixed
 */
function add_to_context($data)
{
    // Add Main Menu to Timber context object
    $data['menu'] = new TimberMenu('main_menu',array('depth' => 2));
    $data['footerMenu'] = new TimberMenu('footer_menu');
    $data['footerLeft'] = new TimberMenu('prefooter_left_menu');
    $data['footerRight'] = new TimberMenu('prefooter_right_menu');

    $data['mobileMenu'] = array();
    $data['mobileMenuSubsections'] = array();
    $data['mobileMenuCurrentSubsection']  ="";
    foreach ($data['menu']->items as $item) {
        if($item->url !== "#myKennel" && $item->url !== "#search" ) {
            $data['mobileMenu'][$item->url] = array(
                "link" => $item->url,
                "title" => html_entity_decode($item->title),
                "shortTitle" => explode(' ',trim($item->title))[0],
                "hasChildren" => (bool) count($item->children),
                "current" => $item->current || $item->current_item_parent,
            );
        }
        $children = array();
        $children[] = array(
            "link" => $item->url,
            "title" => html_entity_decode($item->title) . " home",
            "shortTitle" => explode(' ',trim($item->title))[0],
            "image" => get_field('menuItemIcon', $item->db_id),
            "current" => $item->current || $item->current_item_parent,
        );
        if((bool) count($item->children)) {
            foreach ($item->children as $child) {
                $children[] = array(
                    "link" => $child->url,
                    "title" => $child->title,
                    "shortTitle" => explode(' ',trim($child->title))[0],
                    "image" => get_field('menuItemIcon', $child->db_id),
                    "current" => $item->current || $item->current_item_parent,
                );
            }
            $data['mobileMenuSubsections'][$item->url] = $children;
            if( $item->current || $item->current_item_parent ) {
                $data['mobileMenuCurrentSubsection'] = $item->url;
            }
        }
    }


    // Add main-sidebar to Timber context object
    $data['main_sidebar'] = Timber::get_widgets('main-sidebar');

    // Add Locale strings to Timber context object
    $data['messages'] = get_template_messages();

    // Icons
    $data['logo'] = images_path("logo.png");
    $data['instagram'] = get_svg("instagram.svg");
    $data['twitter'] = get_svg("twitter.svg");
    $data['facebook'] = get_svg("facebook.svg");
    $data['youtube'] = get_svg("youtube.svg");
    $data['envelope'] = get_svg("envelope.svg");
    $data['user'] = get_svg("user.svg");
    $data['search'] = get_svg("search.svg");
    $data['cross'] = get_svg("cross.svg");
    $data['crossBig'] = get_svg("cross-thick.svg");
    $data['star'] = get_svg("star.svg");
    $data['chevron'] = get_svg("chevron.svg");
    $data['chevronBig'] = get_svg("chevron-big.svg");
    $data['triangle'] = get_svg("triangle.svg");
    $data['arrow'] = get_svg("arrow.svg");
    $data['minus'] = get_svg("minus.svg");
    $data['plus'] = get_svg("plus.svg");
    $data['star'] = get_svg("star.svg");
    $data['promotion'] = get_svg("promotion.svg");
    $data['reset'] = get_svg("reset.svg");
    $data['download'] = get_svg("download.svg");
    $data['calendar'] = get_svg("calendar.svg");
    $data['spinner'] = get_svg("spinner.svg");

    // Extend TimberSite object
    $data['site'] = new BaseCampSite();

    $frontpage_id = get_option( 'page_on_front' );

    $data['googleAnalyticsCode'] = get_field('googleAnalyticsCode', 'option');
    $data['googleMapsAPIKey'] = get_field('googleMapsAPIKey', 'option');
    $data['resultsApiUrl'] = get_field('liveResultsAPIUrl', 'option');
    $data['ajaxUrl'] =  admin_url( 'admin-ajax.php' );
    $data['trapBadges'] = get_field('trapBadges', 'option');
    $data['trackNamesFilters'] = get_field('trackNamesFilters', 'option');

    $data['resultsPage'] = get_field('resultsPage', 'option');
    $data['coursesPage'] = get_field('coursesPage', 'option');
    $data['meetingPage'] = get_field('meetingPage', 'option');
    $data['openRacesPage'] = get_field('openRacesPage', 'option');
    $data['newsPage'] = get_field('newsPage', 'option');
    $data['greyhoundPage'] = get_field('greyhoundPage', 'option');
    $data['myKennelPage'] = get_field('myKennelPage', 'option');
    $data['tcPage'] = get_field('tcPage', 'option');
    $data['ourDogsPage'] = get_field('ourDogsPage', 'option');
    $data['trainingDaysPage'] = get_field('trainingDaysPage', 'option');
    $data['daysOutPage'] = get_field('daysOutPage', 'option');

    $data['searchUrl'] = get_home_url();

    $data['stadiumPortalUrl'] = get_field('stadiumPortalUrl', 'option');

    //query vars: location
    $data['coursesLocateLat'] = urldecode (get_query_var( "lat", "" ));
    $data['coursesLocateLng'] = urldecode (get_query_var( "lng", "" ));
    $data['coursesLocateAddress'] = urldecode ( get_query_var( "address", "" ) );
    //query vars: race filters
    $data['resultsTrackName'] = urldecode ( get_query_var( "trackName", "" ) );
    $data['resultsGreyhoundName'] = urldecode ( get_query_var( "greyhoundName", "" ) );
    $data['resultsGreyhoundId'] = urldecode ( get_query_var( "greyhoundId", "" ) );
    $data['resultsRaceDate'] = urldecode (get_query_var( "raceDate", "" ));
    $data['resultsRaceType'] = urldecode (get_query_var( "raceType", "" ));
    $data['resultsRaceClass'] = urldecode (get_query_var( "raceClass", "" ));
    //query vars: other
    $data['resultsRaceId'] = urldecode (get_query_var( "raceId", "" ));
    $data['resultsMeetingId'] = urldecode (get_query_var( "meetingId", "" ));
    $data['defaultCategoryId'] = urldecode (get_query_var( "categoryID", "" ));
    $data['resetPassToken'] = urldecode (get_query_var( "resetToken", "" ));

    // Open Races
    $data['openRacesAPIUrl'] = get_field('openRacesAPIUrl', 'option');

    // Popups
    $myKennelPageSlug = parse_url($data['myKennelPage'])['path'];
    $myKennelPageId = get_page_by_path($myKennelPageSlug)->ID;
    $data['popupLogin'] = get_field('login_popup', $myKennelPageId);
    $data['popupLogout'] = get_field('logout_popup', $myKennelPageId);

    // CTA
    $data['findGrehoundFormCTA'] = get_field('findGrehoundFormCTA', 'option');

    // My Dogs
    $userId = get_current_user_id();
    //only load on My Kennel, Greyhound Profile and Meeting pages
    $isRequired = is_page(url_to_postid($data['meetingPage'])) ||
        is_page(url_to_postid($data['greyhoundPage'])) ||
        is_page(url_to_postid($data['myKennelPage'])) ||
        is_page(url_to_postid($data['coursesPage']));
    if($userId && $isRequired){
        $data['restNonce'] = wp_create_nonce( 'wp_rest' );
        $data['myDogs'] = KennelController::getDogsArray($userId);
        $data['loggedIn'] = "true";
    } else {
        $data['restNonce'] = "";
        $data['myDogs'] = array();
        $data['loggedIn'] = "false";
    }

    if (is_single()) {
        $postId = get_queried_object_id();
        $postCategories = wp_get_post_categories($postId);
        $data['postCategories'] = [];
        foreach ($postCategories as $category) {
            $data['postCategories'][] = [
                'name' => get_term($category)->name,
                'link' => get_term_link($category),
            ];
        }
        $data['postFormattedDate'] = Utils::formatDate(get_the_date('', $postId), 'D j M Y');

        $encoded_permalink = urlencode(get_the_permalink($postId));
        $data['fbShareLink'] = 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_permalink;
        $data['twitterShareLink'] = 'https://twitter.com/intent/tweet?text=' . $encoded_permalink;
    }

    return $data;
}

add_filter('timber_context', 'add_to_context');

/*
 * Support for svg function
*/
add_filter('timber/twig', function(\Twig_Environment $twig) {
    $twig->addFunction(new Timber\Twig_Function('get_svg', 'get_svg'));
    return $twig;
});
