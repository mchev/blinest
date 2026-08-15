<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Homepage hidden category IDs
    |--------------------------------------------------------------------------
    |
    | Public rooms in these categories are omitted from the default official
    | rooms list (e.g. seasonal events). They remain accessible via the
    | category filter.
    |
    */

    'homepage_hidden_category_ids' => array_values(array_filter(array_map(
        'intval',
        explode(',', (string) env('BLINEST_HOMEPAGE_HIDDEN_CATEGORY_IDS', '5')),
    ))),

];
