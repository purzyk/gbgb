<?php
/**
 * Template Name: Our dogs
 * ourdogs-page.php
 *
 * The template for displaying our dogs content
 */

use Basecamp\PageBuilder\PageBuilder;

$context         = \Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

$context = PageBuilder::setupHeroImage($context);

Timber::render(['ourdogs-page.twig'], $context);
