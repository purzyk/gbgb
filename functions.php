<?php

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if(!class_exists('acf')){
    return;
};

 // Bootstrapping theme
require_once __DIR__ . '/app/bootstrap.php';

function add_timber_context_options( $context ) {
    $context['options'] = get_fields('options');
    return $context;
}
add_filter( 'timber_context', 'add_timber_context_options'  );
