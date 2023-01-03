<?php

namespace Basecamp;
use Basecamp\Utils\Utils;


/**
 * Setup WP Automatic integration for s3
 */
class WPAutomatic
{
    /**
     * Init
     */
    public static function init()
    {
        add_action( 'added_post_meta', array(__CLASS__, 'add_featured_image'), 10, 4 );
    }

    /**
     * Add featured media to saved post
     *
     * @param int $postId
     */
    public static function add_featured_image( $metaId, $postId, $metaKey, $metaValue )
    {
        $imageUrl = false;
        if( $metaKey === 'socialMediaImage' ){
            $imageUrl = filter_var($metaValue, FILTER_VALIDATE_URL);
        }
        $skip = get_post_type($postId) !== 'socialmediapost' || has_post_thumbnail($postId);
        if($skip && !$imageUrl) {
            return;
        }
        if( $metaKey !== 'socialMediaImage' ) {
            $post = get_post($postId);
            $content = $post->post_content;
            preg_match('/<img src="(.*)"/mU', $content, $matches);
                if(!empty($matches)) {
                    $imageUrl = $matches[1];
                } else{
                    return;
                }
        }
        if($imageUrl){
            Utils::sideload_featured_media( $imageUrl, $postId );
        }
    }
}

