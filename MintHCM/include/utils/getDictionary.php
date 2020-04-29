<?php

function getDictionary($param, $name, $value, $view, $additional_params)
{
    global $db;
    $sql = "SELECT id, name FROM dictionaries WHERE list_type LIKE '{$additional_params}' AND is_active = 1 AND deleted = 0";
    $types = array();
    $types[''] = '';
    $result = $db->query($sql);
    while (($row = $db->fetchByAssoc($result)) != null) {
        $types[$row['id']] = $row['name'];
    }

    return $types;
}
