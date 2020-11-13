<?php

function getDictionary($param, $name, $value, $view, $additional_params = '')
{
    global $db;
    if(isset($additional_params['additional_params'])){
        $additional_params = $additional_params['additional_params']; //KReporter Fix
    }
    $sql = "SELECT id, name FROM dictionaries WHERE is_active = 1 AND deleted = 0";
    if(!empty($additional_params) && is_string($additional_params)){
        $sql .= " AND list_type LIKE '{$additional_params}'";
    }
    $types = array();
    $types[''] = '';
    $result = $db->query($sql);
    while (($row = $db->fetchByAssoc($result)) != null) {
        $types[$row['id']] = $row['name'];
    }

    return $types;
}
