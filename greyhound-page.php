<?php
/**
 * Template Name: Greyhound
 * greyhound-page.php
 *
 * The template for displaying greyhound profile
 */

use Basecamp\PageBuilder\PageBuilder;

$context         = \Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

Timber::render(['greyhound-page.twig'], $context);
