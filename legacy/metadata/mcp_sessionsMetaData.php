<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$dictionary['mcp_sessions'] = array(
    'table' => 'mcp_sessions',
    'fields' => array(
        array(
            'name' => 'id',
            'type' => 'varchar',
            'len' => 36,
            'required' => true,
        ),
        array(
            'name' => 'session_data',
            'type' => 'longtext',
            'required' => true,
        ),
        array(
            'name' => 'updated_at',
            'type' => 'int',
            'len' => 11,
            'required' => true,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'mcp_sessions_pk',
            'type' => 'primary',
            'fields' => array('id'),
        ),
        array(
            'name' => 'idx_mcp_sessions_updated_at',
            'type' => 'index',
            'fields' => array('updated_at'),
        ),
    ),
);
