<?php
/**
 * Template Name: My Kennel
 * mykennel-page.php
 *
 * The template for displaying greyhound profile
 */

use Basecamp\GBGBUserEndpoints;

$context         = \Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;
$context         = GBGBUserEndpoints::setupEndpoint($context);

Timber::render(['my-kennel/main.twig'], $context);
