<?php
$hook_version = 1;
$hook_array = array();
$hook_array['before_relationship_delete'] = array();
$hook_array['before_relationship_delete'][] = array(
    100,
    'Remove previous dashboards and add default ones',
    'modules/DashboardManager/logic_hooks/AssignDefaultDashboards.php',
    'AssignDefaultDashboards',
    'before_relationship_delete',
);
