<?php
namespace Basecamp\Utils;

class Utils {

  public static function goTo404() {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    get_template_part( 404 );
    exit();
  }

  public static function getNextPost($id, $posts) {
    $currentPostIndex = null;
    foreach ($posts as $key => $post) {
      if ($post->ID === $id) {
        $currentPostIndex = $key;
      }
    }

    if (isset($posts[$currentPostIndex + 1])) {
      // Get next one in the posts array
      return $posts[$currentPostIndex + 1];
    } else {
      // Must be at the end so return first element
      return $posts[0];
    }
  }

  public static function getPrevPost($id, $posts) {
    $currentPostIndex = null;
    foreach ($posts as $key => $post) {
      if ($post->ID === $id) {
        $currentPostIndex = $key;
      }
    }

    if (isset($posts[$currentPostIndex - 1])) {
      // Get previous one in the posts array
      return $posts[$currentPostIndex - 1];
    } else {
      // Must be at the start so return first element
      return $posts[count($posts) - 1];
    }
  }

   /**
   * Format date to our preffered one
   *
   * @param string $date    date to format
   * @param string $format  format to use
   *
   * @return string
   */
  public static function formatDate($date, $format = 'j/m/Y')
  {
    $defaultTimezone = date_default_timezone_get();
    date_default_timezone_set( 'UTC' );

    $timestamp = strtotime($date);
    $today = strtotime('today midnight');
    $yesterday = strtotime('yesterday midnight');

    if($timestamp > $today) {
        $date = "Today";
    } elseif($timestamp > $yesterday){
        $date = "Yesterday";
    } else {
        $date = date($format,$timestamp);
    }

    date_default_timezone_set( $defaultTimezone );
    return $date;
  }

    /**
     * Get image from url and set is as featured
     *
     * @param string $imgUrl       Image url.
     * @param int    $postId       Post id.
     *
     * @return bool
     */
    static public function sideload_featured_media( $imgUrl, $postId ) {
        // we need this in case we're in AJAX request
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        add_action( 'add_attachment', array(__CLASS__, 'set_sideloaded_thumb_as_featured') );
        $sideloadResult = media_sideload_image( $imgUrl, $postId, 'The social media image', 'src' );
        remove_action( 'add_attachment', array(__CLASS__, 'set_sideloaded_thumb_as_featured') );
        return $sideloadResult;
    }

    /**
     * When attachement is added, set it as featured media for parent
     *
     * @param int $attId Attachment id.
     */
    static public function set_sideloaded_thumb_as_featured( $attId ) {
        $att     = get_post( $attId );
        $postId = $att->post_parent;
        if ( has_post_thumbnail( $postId ) ) {
            return;
        }
        set_post_thumbnail( $postId, $attId );
    }

    /**
     * Transform page number to wpQuery friendly 'offset'
     *
     * @param int $page    the page number to get
     * @param int $perPage the amount per page
     *
     * @return int
     */
    public static function getPaginationOffset($page, $perPage = 6)
    {
        if ($page < 2) {
        return 0;
        } else {
        $multiplier = $page - 1;
        return $multiplier * $perPage;
        }
    }


    /**
     * Sanitize post content for display and highlight hashtags
     *
     * @param string $content  post content
     * @param string $network  social media network
     *
     * @return string
     */
    public static function formatSocialMediaContent($content, $network)
    {
        $content = strip_tags($content);

        //highlight hashtags and make them link
        switch ($network) {
            case 'instagram':
                $template = '<br><a href="https://www.instagram.com/explore/tags/$2/" target="_blank" class="SocialMediaHashtag">#$2</a>';
                break;
            case 'twitter':
                $template = '<br><a href="https://twitter.com/hashtag/$2" target="_blank" class="SocialMediaHashtag">#$2</a>';
                break;
            default:
                $template = '<br><span class="SocialMediaHashtag">#$2</span>';
                break;
        }
        $content = preg_replace('/(#)([^#\W]+)/', $template, $content);

        return $content;
    }
}
