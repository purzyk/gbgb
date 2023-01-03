<?php
namespace Basecamp\PageBuilder;
use Basecamp\Api\KennelController;
use Basecamp\Utils\Utils;
use \WP_User;

class PageBuilder {

    //Global page grid item count.
    public static $globalPageGridItemCount = 0;

    /**
     * Setup hero previous link context
     *
     * @param array $context
     * @return array
     */
    public static function setupHeroPreviousLink($context) {
        $context['heroPrevPage'] = get_field('heroPreviousPage');
        return $context;
    }

    /**
     * Setup hero image context
     *
     * @param  array $context
     * @return array
     */
    public static function setupHeroImage($context) {
        if (get_field('heroDisable')) {
            return $context;
        }
        $heroImages = get_field('heroImages');
        if ($heroImages){
            shuffle($heroImages);
            $context['heroImage'] = $heroImages[0];
            $context['heroImageNaturalHeight'] = $heroImages[0]['heroImageMain']['height'];
        }
        return $context;
    }

    /**
     * Setup blocks
     *
     * @param  array $context
     * @return array
     */
    public static function setupBuilderBlocks($context) {
        $blocks = get_field('pageBuilderBlocks');
        $blockBefore = null;
        if ($blocks) {
            foreach ($blocks as $index => $block) {
                $blockType = $block['acf_fc_layout'];
                $blockAfter = isset($blocks[$index+1]) ? $blocks[$index+1]['acf_fc_layout'] : null;
                $methodName = 'setupBlock_' . $blockType;
                $methodName = str_replace('-','_', $methodName);
                if ($methodName === "setupBlock_page_grid_2") {
                    $blocks[$index] = self::setupBlock_page_grid($block,'pageGridItems2');
                } else if($methodName === "setupBlock_page_grid_3") {
                    $blocks[$index] = self::setupBlock_page_grid($block,'pageGridItems3');
                } else if(method_exists(__CLASS__,$methodName)){
                    $blocks[$index] = call_user_func ( array(__CLASS__, $methodName),$block,$blockBefore,$blockAfter);
                }
                $blockBefore = $blockType;
                $blocks[$index]['blockType'] = $blockType;
            }
        }
        $context['pageBuilderBlocks'] = $blocks;
        $context['globalPageGridItemCount'] = self::$globalPageGridItemCount;
        return $context;
    }

    /**
     * Setup highlights block
     *
     * @param  array $block
     * @param  string $blockBefore
     * @param  string $blockAfter
     * @return array
     */
    public static function setupBlock_highlights($block,$blockBefore,$blockAfter) {
        $items = $block['highlightsItems'];
        foreach ($items as $key => $value) {
            $postId = $value['highlightsItemContent']->ID;
            if ('post' === get_post_type($postId)) {
                $items[$key]['highlightsItemType'] = 'News';
                $items[$key]['highlightsItemDate'] = get_the_date('D j M Y', $postId);
            }
            if ('page' === get_post_type($postId) && $value['highlightsItemContent']->post_parent) {
                $items[$key]['highlightsItemType'] = get_the_title($value['highlightsItemContent']->post_parent);
            }
            if (!empty($items[$key]['highlightsItemSubtitle'])) {
                $items[$key]['highlightsItemType'] = $items[$key]['highlightsItemSubtitle'];
            }
            if (empty($items[$key]['highlightsItemTitle'])) {
                $items[$key]['highlightsItemTitle'] = get_the_title($postId);
            }
            if (empty($items[$key]['highlightsItemDescription']) && $value['highlightsItemContent']->post_type === 'post') {
                $items[$key]['highlightsItemDescription'] = get_the_excerpt($postId);
            }
            if (empty($items[$key]['highlightsItemDescription']) && $value['highlightsItemContent']->post_type === 'page') {
                $heroDescExcerpt = get_field('heroText',$postId);
                if(!empty($heroDescExcerpt)){
                    if (preg_match('/^.{1,80}\b/s', $heroDescExcerpt, $match))
                    {
                        $heroDescExcerpt=$match[0] . '(...)';
                    }
                    $items[$key]['highlightsItemDescription'] = $heroDescExcerpt;
                }
            }
            if (empty($items[$key]['highlightsItemImage']) ) {
                $items[$key]['highlightsItemImage'] = get_the_post_thumbnail_url($postId);
            }
            $items[$key]['highlightsItemLink'] = get_the_permalink($postId);
        }
        $block['highlightsItems'] = $items;
        return $block;
    }

    /**
     * Setup highlights block
     *
     * @param  array $block
     * @param  string $blockBefore
     * @param  string $blockAfter
     * @return array
     */
    public static function setupBlock_race_courses($block,$blockBefore, $blockAfter) {
        $userId = get_current_user_id();
        if ($userId){
            $block['mycourses'] = wp_json_encode(KennelController::getCoursesArray($userId));
        } else {
            $block['mycourses'] = wp_json_encode(array());
        }
        return $block;
    }

    /**
     * Setup text with background block
     *
     * @param  array $block
     * @param  string $blockBefore
     * @param  string $blockAfter
     * @return array
     */
    public static function setupBlock_text_with_background($block,$blockBefore, $blockAfter) {
        $solidBlocks = array('social-media-feed');
        $block['topFade'] = in_array($blockBefore,$solidBlocks) ? false : true;
        $block['bottomFade'] = in_array($blockAfter,$solidBlocks) ? false : true;
        return $block;
    }

    /**
     * Setup page grid block
     *
     * @param  array $block
     * @param  string $fieldName
     * @return array
     */
    public static function setupBlock_page_grid($block, $fieldName) {
        $items = $block[$fieldName];
        foreach ($items as $key => $value) {
            $noTitleOrImage = empty($items[$key]['pageGridItemImage']) && empty($items[$key]['pageGridItemTitle']);
            $noContent = empty($items[$key]['pageGridItemContent']);
            if( $noContent & $noTitleOrImage) {
                unset($items[$key]);
                continue;
            }
            if (!$noContent ) {
                $postId = $value['pageGridItemContent']->ID;
            }
            if (empty($items[$key]['pageGridItemTitle']) && ! $noContent ) {
                $items[$key]['pageGridItemTitle'] = get_the_title($postId);
            }
            if (empty($items[$key]['pageGridItemDescription']) && ! $noContent ) {
                $items[$key]['pageGridItemDescription'] = get_the_excerpt($postId);
            }
            if (empty($items[$key]['pageGridItemImage']) && ! $noContent ) {
                $items[$key]['pageGridItemImage'] = get_the_post_thumbnail_url($postId);
            }
            if (empty($items[$key]['pageGridItemUrl']) && ! $noContent ) {
                $items[$key]['pageGridItemUrl'] = get_the_permalink($postId);
            }
            self::$globalPageGridItemCount++;
        }
        $block['pageGridItems'] = $items;
        return $block;
    }

    /**
     * Setup latest news block
     *
     * @param  array $block
     * @return array
     */
    public static function setupBlock_latest_news($block) {
        $categories = array();
        $categoriesObjects = get_categories();
        foreach ($categoriesObjects as $category) {
            if ($category->cat_ID !== 1) {
                $categories[] = array(
                    "value" => $category->cat_ID,
                    "label" => $category->name,
                );
            }
        }
        $contentTypes = array();
        $contentTypesObjects = get_terms(array(
            'taxonomy' => 'contenttype',
            'hide_empty' => false,
        ));
        foreach ($contentTypesObjects as $contentType) {
            if ($contentType->cat_ID !== 1) {
                $contentTypes[] = array(
                    "value" => $contentType->term_id,
                    "label" => $contentType->name,
                );
            }
        }
        $block['categories'] = json_encode($categories);
        $block['contentTypes'] = json_encode($contentTypes);

        $firstPostEver = get_posts( 'numberposts=1&order=ASC' )[0];
        $block['startingYear'] = date("Y",strtotime($firstPostEver->post_date));
        return $block;
    }

    /**
     * Setup careers block
     *
     * @param  array $block
     * @return array
     */
    public static function setupBlock_careers($block) {
        if (empty($block['careersVacancies'])) {
            $noVacancies = $block['careersNoVacancies'];
            $block['careersVacancies'] = array(
                array(
                    'careersVacanciesTitle' => $noVacancies['careersNoVacanciesTitle'],
                    'careersVacanciesLocation' => '',
                    'careersVacanciesLink' => '',
                    'careersVacanciesDescription' => $noVacancies['careersNoVacanciesDescription']
                )
            );
        }
        return $block;
    }

    public static function mapToSelectValues($item){
        return array(
            'value' => $item['value'],
            'label' => $item['value'],
        );
    }

    /**
     * Setup careers block
     *
     * @param  array $block
     * @return array
     */
    public static function setupBlock_open_races($block) {
        $filters = get_field('openRacesFilters', 'option');
        $filters['raceTypes']           = array_map(array(__CLASS__, 'mapToSelectValues'), $filters['raceTypes']);
        $filters['tracks']              = array_map(array(__CLASS__, 'mapToSelectValues'), $filters['tracks']);
        $filters['categories']          = array_map(array(__CLASS__, 'mapToSelectValues'), $filters['categories']);
        $filters['greyhoundCategories'] = array_map(array(__CLASS__, 'mapToSelectValues'), $filters['greyhoundCategories']);
        $block['filteroptions'] = json_encode($filters);
        return $block;
    }

    /**
     * Setup testimonials block
     *
     * @param  array $block
     * @return array
     */
    public static function setupBlock_testimonials($block) {
        $args = array(
            'post_type' => 'testimonials',
            'posts_per_page' => -1,
            'orderby' => array(
                'date' => 'DESC'
            )
        );

        $block['posts'] = get_posts( $args );
        return $block;
    }

    /**
     * Setup mini news block
     *
     * @param  array $block
     * @return array
     */
    public static function setupBlock_mini_news($block) {
        $mini_news = array();
        $args = array(
            'posts_per_page' => 3,
            'cat' => $block['post_taxonomy'],
            'orderby' => array(
                'date' => 'DESC'
            )
        );
        $block['posts'] = get_posts($args);
        $items = $block['posts'];
        foreach($items as $item) {
            $img_url = get_the_post_thumbnail_url($item->ID);
            $categoriesObjects = wp_get_post_categories($item->ID);

            foreach($categoriesObjects as $c) {
                $category = get_category($c);
                $cat_name = $category->name;
            }

            $postpermalink = get_permalink($item->ID);
            $mini_news[] = array(
                'postDate' => $item->post_date,
                'postTitle' => $item->post_title,
                'postID' => $item->ID,
                'postCategory' => $cat_name,
                'postPermalink' => $postpermalink,
                'postImg' => $img_url,
                'postExcerpt' => $item->post_excerpt,
            );
        }

        $block['posts'] = $items;
        $block['mini_news'] = $mini_news;
        return $block;
    }
}
