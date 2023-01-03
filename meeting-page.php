<?php
/**
 * Template Name: Meeting
 * meeting-page.php
 *
 * The template for displaying meeting/race results
 */

use Basecamp\PageBuilder\PageBuilder;

$context         = \Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

Timber::render(['meeting-page.twig'], $context);
