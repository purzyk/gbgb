<?php

use Basecamp\PageBuilder\PageBuilder;

$context         = \Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

$context = PageBuilder::setupHeroPreviousLink($context);
$context = PageBuilder::setupHeroImage($context);
$context = PageBuilder::setupBuilderBlocks($context);

Timber::render(['builder-page.twig'], $context);
