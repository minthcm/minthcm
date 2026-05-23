<?php

$hook_types_array = array(
    'before_save',
);

if (!isset($hook_array)) {
    $hook_array = array();
}
foreach ($hook_types_array as $type) {
    if (!isset($hook_array[$type])) {
        $hook_array[$type] = array();
    }
}
$hook_array['before_save'][] = array(
    20,
    'update l&n call - calls - as',
    'include/LastNextContacts/LastNextContactsHooks.php',
    'LastNextContactsHooks',
    'beforeEmailUpdate',
);
