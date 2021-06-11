<?php

$dictionary['Reactions'] = array(
    'table' => 'reactions',
    'audited' => false,
    'inline_edit' => false,
    'duplicate_merge' => false,
    'fields' => array(
        'reaction_type' => array(
            'name' => 'reaction_type',
            'vname' => 'LBL_REACTION_TYPE',
            'label' => 'LBL_REACTION_TYPE',
            'type' => 'enum',
            'required' => true,
            'importable' => 'required',
            'options' => 'reaction_type_list',
        ),
        'parent_type' => array(
            'name' => 'parent_type',
            'vname' => 'LBL_PARENT_TYPE',
            'type' => 'parent_type',
            'dbType' => 'varchar',
            'group' => 'parent_name',
            'options' => 'parent_type_display_reactions',
            'len' => 255,
        ),
        'parent_name' => array(
            'name' => 'parent_name',
            'parent_type' => 'record_type_display',
            'type_name' => 'parent_type',
            'id_name' => 'parent_id',
            'vname' => 'LBL_PARENT_NAME',
            'type' => 'parent',
            'group' => 'parent_name',
            'source' => 'non-db',
            'options' => 'parent_type_display_reactions',
            'required' => true,
        ),
        'parent_id' => array(
            'name' => 'parent_id',
            'vname' => 'LBL_PARENT_ID',
            'type' => 'id',
            'group' => 'parent_name',
            'reportable' => false,
        ),
        'news' => array(
            'name' => 'news',
            'type' => 'link',
            'relationship' => 'news_reactions',
            'module' => 'News',
            'bean_name' => 'News',
            'source' => 'non-db',
            'vname' => 'LBL_NEWS',
        ),
    ),
    'relationships' => array(),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('Reactions', 'Reactions', array('basic', 'assignable'));
