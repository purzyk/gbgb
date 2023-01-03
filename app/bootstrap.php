<?php

// Load shortcodes
require_once __DIR__ . '/shortcodes.php';

// Load all composer packages
require_once __DIR__ . '/../vendor/autoload.php';

// Init Timber
$timber = new \Timber\Timber();

// Init Dotenv
$dotenv = new Dotenv\Dotenv(__DIR__ . '/..');
if (file_exists(".env")) {
    $dotenv->load();
}

// Load WordPress config files
require_once __DIR__ . '/../app/config/autoload.php';

// Init Rest
\Basecamp\Api\Rest::init();

// Init update ACF
\Basecamp\Acf::init();

// Init WP Automatic integration
\Basecamp\WPAutomatic::init();

// Init Relevanssi integration
\Basecamp\Relevanssi::init();

// Init custom user functionalities
\Basecamp\GBGBUserRoles::init();
\Basecamp\GBGBUserAjax::init();
\Basecamp\GBGBUserApi::init();

/**
 * Disable gutenburg
 */
add_filter('use_block_editor_for_post', '__return_false', 10);
add_filter('use_block_editor_for_post_type', '__return_false', 10);

/**
 * Loads the theme's translated strings.
 */
function localize() {
    load_theme_textdomain('gbgb', get_template_directory() . '/resources/languages');
}
add_action('after_setup_theme', 'localize');

/**
 * Register query var
 */
function add_query_vars_filter($vars) {
    // Nearest track
    $vars[] = "lat";
    $vars[] = "lng";
    $vars[] = "address";
    // Results
    $vars[] = "trackName";
    $vars[] = "greyhoundName";
    $vars[] = "greyhoundId";
    $vars[] = "raceDate";
    $vars[] = "raceType";
    $vars[] = "raceClass";
    // Other
    $vars[] = "raceId";
    $vars[] = "meetingId";
    $vars[] = "postsPerPage";
    // My Kennel
    $vars[] = "kennelEndpoint";
    $vars[] = "categoryID";
    $vars[] = "resetToken";
    return $vars;
}
add_filter('query_vars', 'add_query_vars_filter');

/**
 * More excerpt '...'
 */
function set_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'set_excerpt_more');

/**
 * Disable wpcf7_autop
 */
add_filter('wpcf7_autop_or_not', '__return_false');

/**
 * Change limit of searching
 */
function change_wp_search_size($query) {
    $postsNumber = get_query_var('postsPerPage');
     // Make sure it is a search page
    if ($query->is_search) {
        $query->query_vars['posts_per_page'] = $postsNumber;
    }

    return $query; // Return our modified query variables
}
add_filter('pre_get_posts', 'change_wp_search_size'); // Hook our custom function onto the request filter

/**
 * WYSIWYG toolbars
 */
function gbgb_my_toolbars($toolbars) {
    // Add a new toolbar called "Very Simple"
    // - this toolbar has only 1 row of buttons
    $toolbars['Link only'] = [];
    $toolbars['Link only'][1] = ['link'];

    return $toolbars;
}
add_filter('acf/fields/wysiwyg/toolbars', 'gbgb_my_toolbars');

add_filter('wp_mail_smtp_providers_loader_get_entity', 'base_camp_mail_fix', 10, 3);

/**
 * Replace plugin's mailer class with our own to fix the bug (fix is in class on line 228)
 *
 * @param Mailer $entity
 * @param string $provider
 * @param string $request
 * @return mixed
 */
function base_camp_mail_fix($entity, $provider, $request) {
	if($provider === 'sendgrid' && $request === "Mailer") {
        //We need to extract protected value from the provided object as the plugin author didn't think of that.
		$reflection = new ReflectionClass($entity);
		$property = $reflection->getProperty('phpmailer');
		$property->setAccessible(true);
        $phpmailer =  $property->getValue($entity);

        //Load the file with the class exactly here to be 100% sure plugin's classes are loaded.
        include_once('wp-smtp-fix.php');
		return new BaseCampSendgridMailer($phpmailer);
	}
	return $entity;
}
