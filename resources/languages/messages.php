<?php

function get_template_messages()
{
    return [
        // General
        'general' => [
            'no_content' => __('Sorry, no content', 'gbgb'),
            'back_to_previous_page' => __('Back to previous page', 'gbgb'),
            'back_to_top' => __('Back to top', 'gbgb'),
        ],

        // 404 Page
        '404' => [
            'title'     => __('404 - Page not found.', 'gbgb'),
            'body'      => __('The page you have looked for does not exist.', 'gbgb'),
            'link_text' => __('Back to home page', 'gbgb'),
        ],

        // Article Component
        'article' => [
            'edit' => __('Edit', 'gbgb'),
        ],

        // Comment Form Page
        'comment_form' => [
            'name'                => __('Name', 'gbgb'),
            'email'               => __('Email', 'gbgb'),
            'website'             => __('Website', 'gbgb'),
            'comment'             => __('Comment', 'gbgb'),
            'comment_placeholder' => __('Enter your comment here...', 'gbgb'),
            'post_comment'        => __('Post Comment', 'gbgb'),
            'reset'               => __('Reset', 'gbgb'),
        ],

        // Comment Page
        'comment' => [
            'reply' => __('Reply', 'gbgb'),
        ],

        // Search Page
        'search' => [
            'no_results' => __('Sorry, No Results. Try your search again.', 'gbgb'),
        ],

        // Search Form Page
        'search_form' => [
            'search' => __('Search', 'gbgb'),
            'placeholder' => __('Start typing…', 'gbgb'),
        ],

        // Single Password Page
        'single_password' => [
            'password' => __('Password', 'gbgb'),
            'submit'   => __('Submit', 'gbgb'),
        ],

        // Single Page
        'single' => [
            'edit'     => __('Edit', 'gbgb'),
            'comments' => __('Comments', 'gbgb'),
        ],
    ];
}
