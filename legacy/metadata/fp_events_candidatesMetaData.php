<?php
$dictionary["fp_events_candidates"] = [
    'true_relationship_type' => 'many-to-many',
    'relationships' => [
        'fp_events_candidates' => [
            'lhs_module' => 'FP_events',
            'lhs_table' => 'fp_events',
            'lhs_key' => 'id',
            'rhs_module' => 'Candidates',
            'rhs_table' => 'candidates',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'fp_events_candidates',
            'join_key_lhs' => 'fp_events_id',
            'join_key_rhs' => 'candidates_id',
        ],
    ],
    'table' => 'fp_events_candidates',
    'fields' => [
        0 => [
            'name' => 'id',
            'type' => 'varchar',
            'len' => 36,
        ],
        1 => [
            'name' => 'date_modified',
            'type' => 'datetime',
        ],
        2 => [
            'name' => 'deleted',
            'type' => 'bool',
            'len' => '1',
            'default' => '0',
            'required' => true,
        ],
        3 => [
            'name' => 'fp_events_id',
            'type' => 'varchar',
            'len' => 36,
        ],
        4 => [
            'name' => 'candidates_id',
            'type' => 'varchar',
            'len' => 36,
        ],
        5 => [
            'name' => 'invite_status',
            'type' => 'varchar',
            'len' => '25',
            'default' => 'Not Invited',
        ],
        6 => [
            'name' => 'accept_status',
            'type' => 'varchar',
            'len' => '25',
            'default' => 'No Response',
        ],
        7 => [
            'name' => 'email_responded',
            'type' => 'int',
            'len' => '2',
            'default' => '0',
        ],
    ],
    'indices' => [
        0 => [
            'name' => 'fp_events_candidatesspk',
            'type' => 'primary',
            'fields' => [
                0 => 'id',
            ],
        ],
        1 => [
            'name' => 'fp_events_candidates_alt',
            'type' => 'alternate_key',
            'fields' => [
                0 => 'fp_events_id',
                1 => 'candidates_id',
            ],
        ],
    ],
];
