<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Application Constants
    |--------------------------------------------------------------------------
    |
    | Constants can be used by
    | Config::get('constants.CONSTANT_NAME');
    |
    */
    $TagArray = array('<strong>'=>'|strong|','</strong>'=>'|/strong|','<b>'=>'|b|','</b>'=>'|/b|',
    '<big>'=>'|big|','</big>','|/big|','<u>'=>'|u|','</u>'=>'|/u|'),
 
    'EXTRA_COST_OF_PAGE_VIEWS_PER' => 10000,
    'ADDITIONAL_CHARACTER_PER_PAGE' => 10000,


    'CUSTOM_TAGS'=>$TagArray,

    //Pagination Per Page 
    'PER_PAGE'=>20,
];