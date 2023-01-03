<?php

$context                = Timber::get_context();
$post                   = new TimberPost();
$context['post']        = $post;
$context['cancel_link'] = get_cancel_comment_reply_link(__('Cancel reply', 'gbgb'));

$related = [
    'post_type'		    => $post->post_type,
    'posts_per_page' 	=> 1,
    'order' 			=> 'ASC',
    'orderby'			=> 'rand',
    // 'post__not_in'		=> $post->id
];
$context['related']     = Timber::get_posts($related)[0];

Timber::render(['single-' . $post->ID . '.twig', 'single-' . $post->post_type . '.twig', 'single.twig'], $context);
