<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$module_name = 'SecurityGroups';
$searchFields[$module_name] = 
	array (
		'name' => array( 'query_type'=>'default'),
		'current_user_only'=> array('query_type'=>'default','db_field'=>array('assigned_user_id'),'my_items'=>true),
		'assigned_user_id'=> array('query_type'=>'default'),
		'group_type' => array('query_type' => 'default'),
		'position_leader_name' => array('query_type' => 'default'),
		'organizationalunits_organizationalunits_left' => array('query_type' => 'default'),
		'range_date_entered' => array('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
		'start_range_date_entered' => array(
			'query_type' => 'default',
			'enable_range_search' => true,
			'is_date_field' => true
		),
		'end_range_date_entered' => array(
			'query_type' => 'default',
			'enable_range_search' => true,
			'is_date_field' => true
		),
		'range_date_modified' => array('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
		'start_range_date_modified' => array(
			'query_type' => 'default',
			'enable_range_search' => true,
			'is_date_field' => true
		),
		'end_range_date_modified' => array(
			'query_type' => 'default',
			'enable_range_search' => true,
			'is_date_field' => true
		),
	);
