<?php
/**
 * Template Name: Page Builder
 * builder-page.php
 *
 * The template for displaying page builder content
 */

use Basecamp\PageBuilder\PageBuilder;

$context         = \Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

$context = PageBuilder::setupHeroPreviousLink($context);
$context = PageBuilder::setupHeroImage($context);
$context = PageBuilder::setupBuilderBlocks($context);

Timber::render(['builder-page.twig'], $context);
