<?php

$dictionary["last_next_contact_queue"] = array(
    'true_relationship_type' => 'many-to-many',
    'from_studio' => true,
    'relationships' => array(
    ),
    'table' => 'last_next_contact_queue',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'varchar',
            'len' => 36,
        ),
        'date_entered' => array(
            'name' => 'date_entered',
            'type' => 'datetime',
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime',
        ),
        'type' => array(
            'name' => 'type',
            'type' => 'varchar',
            'len' => 50,
        ),
        'related_module' => array(
            'name' => 'related_module',
            'type' => 'varchar',
            'len' => 255,
        ),
        'related_id' => array(
            'name' => 'related_id',
            'type' => 'varchar',
            'len' => 36,
        ),
        'related_modules' => array(
            'name' => 'related_modules',
            'type' => 'text',
        ),
        'status' => array(
            'name' => 'status',
            'type' => 'int',
        ),
        'error_message' => array(
            'name' => 'error_message',
            'type' => 'text',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'last_next_contact_data_update_spk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
    ),
);
