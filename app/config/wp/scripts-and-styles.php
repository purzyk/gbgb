<?php

/**
 * Register scripts and styles and enqueue them
 */
function base_camp_scripts_and_styles()
{
    // Register styles
    wp_register_style('base-camp-styles', assets('app.css'), [], '', 'all');
    wp_register_style('gbgb-adobe-fonts','https://use.typekit.net/trj3ncr.css');
    wp_register_style('gbgb-google-fonts','https://fonts.googleapis.com/css?family=Montserrat:300,400,600');

    // Register scripts
    wp_register_script('base-camp-vendor', assets('vendor.js'), [], '', true);
    if(is_front_page() && get_field('optimizedHomeCss','option')){
        wp_register_script('base-camp-scripts-home', assets('home.js'), ['base-camp-vendor'], '', true);
        wp_enqueue_script('base-camp-scripts-home');
    } else {
        wp_register_script('base-camp-scripts', assets('app.js'), ['base-camp-vendor'], '', true);
        wp_enqueue_script('base-camp-scripts');
    }

    // Enqueue scripts and styles
    wp_enqueue_script('base-camp-vendor');
    wp_enqueue_style('base-camp-styles');
    wp_enqueue_style('fa-css');
    wp_enqueue_style('gbgb-adobe-fonts');
    wp_enqueue_style('gbgb-google-fonts');

    // comment reply script for threaded comments
    if (is_singular() && comments_open() && (get_option('thread_comments') == 1)) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'base_camp_scripts_and_styles', 999);

/**
 * Register Login Page scripts and styles and enqueue them
 */
function base_camp_login_scripts_and_styles()
{
    // Register styles
    wp_register_style('base-camp-login-styles', assets('login.css'), [], '', 'all');

    // Enqueue scripts and styles
    wp_enqueue_style('base-camp-login-styles');
}

add_action('login_enqueue_scripts', 'base_camp_login_scripts_and_styles', 999);

/**
 * Register Admin Page scripts and styles and enqueue them
 */
function base_camp_admin_scripts_and_styles()
{
    // Register styles
    wp_register_style('base-camp-admin-styles', assets('admin.css'), [], '', 'all');

    // Enqueue scripts and styles
    wp_enqueue_style('base-camp-admin-styles');
}

add_action('admin_enqueue_scripts', 'base_camp_admin_scripts_and_styles', 999);

/**
 * Use the custom email template.
 *
 * @param array $atts
 * @return array
 */
function base_camp_email_template($atts) {
    $context['emailContent'] = $atts['message'];
    ob_start();
    Timber::render(['internal/email-template.twig'], $context);
    $atts['message'] = ob_get_clean();
    return $atts;
}

add_filter('wp_mail', 'base_camp_email_template',999);

function base_camp_add_attachement_to_email(&$phpmailer){
    $file = trailingslashit(get_template_directory())  . 'resources/assets/images/email.jpg';
    $uid = 'email.jpg@sendgrid';
    $name = 'email.jpg';

    $phpmailer->SMTPKeepAlive = true;
    $val = $phpmailer->AddEmbeddedImage($file, $uid, $name);
}

add_action( 'phpmailer_init', 'base_camp_add_attachement_to_email');

function base_camp_email_content_type(){
    return 'text/html';
}
add_filter( 'wp_mail_content_type','base_camp_email_content_type' );

function base_camp_fix_entities_in_titles($title) {
    return html_entity_decode($title);
}

add_filter('the_title', 'base_camp_fix_entities_in_titles',10,1);
