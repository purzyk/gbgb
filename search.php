<?php

use Basecamp\Relevanssi;

$templates             = ['search.twig', 'archive.twig', 'index.twig'];
$context               = Timber::get_context();
$context['title']      = get_search_query();
$context['total']      = Relevanssi::getCountsForLastSearch();
$context['posts']      = new Timber\PostQuery();
$context['pagination'] = Timber::get_pagination($pagination_mid_size);
Timber::render($templates, $context);
