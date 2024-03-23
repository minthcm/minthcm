<?php

$module_name = 'AOS_Products';
$ESListViewDefs['AOS_Products'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'part_number' => [
            'default' => true,
        ],
        'cost' => [
            'default' => true,
        ],
        'price' => [
            'default' => true,
        ],
        'aos_product_category_name' => [
            'link' => true,
            'default' => true,
        ],
        'created_by_name' => [
            'link' => true,
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [
        ],
        'part_number' => [
        ],
        'cost' => [
        ],
        'price' => [
        ],
        'created_by' => [
        ],
    ],
];
