<?php

// Register a new image size.
add_image_size('squaresquare', 400, 400, true);
add_image_size('latestNews', 370, 300, true);
add_image_size('latestNewsRetina', 740, 600, true);
add_image_size('boardMemebers', 300, 310, true);
add_image_size('boardMemebersRetina', 300 * 2, 310 * 2, true);
add_image_size('doubleImage', 380);
add_image_size('doubleImageRetina', 380 * 2);
add_image_size('hero', 1920);
add_image_size('heroRetina', 1920 * 2);
add_image_size('homepageHero', 1920, 580, true);
add_image_size('homepageHeroRetina', 1920 * 2, 580 * 2, true);
add_image_size('highlight', 600);
add_image_size('highlightRetina', 600 * 2);
add_image_size('singleImage', 690);
add_image_size('singleImageRetina', 690 * 2);
add_image_size('gridLarge', 570, 300, true);
add_image_size('gridLargeRetina', 570 * 2, 300 * 2, true);
add_image_size('gridSmall', 370, 300, true);
add_image_size('gridSmallRetina', 370 * 2, 300 * 2, true);
add_image_size('ourDogs', 370, 270, true);
add_image_size('ourDogsRetina', 370 * 2 , 270 * 2, true);
add_image_size('ourDogsTall', 370, 600, true);
add_image_size('ourDogsTallRetina', 370 * 2 , 600 * 2, true);


/**
 * Register a new image size options to the list of selectable sizes in the Media Library
 *
 * @param $sizes
 *
 * @return array
 */
function base_camp_custom_image_sizes($sizes)
{
    return array_merge($sizes, [
        'square' => __('Square', 'gbgb'),
    ]);
}

add_filter('image_size_names_choose', 'base_camp_custom_image_sizes');
