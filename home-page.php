<?php
/**
 * Template Name: Home Page
 * home-page.php
 *
 * The template for displaying home page
 */

use Basecamp\PageBuilder\PageBuilder;

$context         = \Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

$context = PageBuilder::setupHeroImage($context);
$context = PageBuilder::setupBuilderBlocks($context);

Timber::render(['home-page.twig'], $context);
