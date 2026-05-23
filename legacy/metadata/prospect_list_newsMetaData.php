<?php

$dictionary["prospect_list_news"] = array(
    'true_relationship_type' => 'many-to-many',
    'from_studio' => true,
    'relationships' => array(
        'prospect_list_news' => array(
            'lhs_module' => 'News',
            'lhs_table' => 'news',
            'lhs_key' => 'id',
            'rhs_module' => 'ProspectLists',
            'rhs_table' => 'prospect_lists',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'prospect_list_news',
            'join_key_lhs' => 'news_id',
            'join_key_rhs' => 'prospectlist_id',
        ),
    ),
    'table' => 'prospect_list_news',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'varchar',
            'len' => 36,
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime',
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'len' => '1',
            'default' => '0',
            'required' => true,
        ),
        'news_id' => array(
            'name' => 'news_id',
            'type' => 'varchar',
            'len' => 50,
        ),
        'prospectlist_id' => array(
            'name' => 'prospectlist_id',
            'type' => 'varchar',
            'len' => 50,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'news_prospectlists_spk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'news_lhs_alt',
            'type' => 'index',
            'fields' => array(
                'news_id',
            ),
        ),
        array(
            'name' => 'prospectlist_rhs_alt',
            'type' => 'index',
            'fields' => array(
                'prospectlist_id',
            ),
        ),
    ),
);
