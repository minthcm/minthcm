<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
if(!isset($hook_array)){ $hook_array = Array();} 
// position, file, function 
if(!isset($hook_array['after_relationship_add'])){ $hook_array['after_relationship_add'] = Array(); }
$hook_array['after_relationship_add'][] = Array(99, 'Add Unit to Employees', 'modules/Positions/logic_hooks/afterRelationshipAdd.php','afterRelationshipAdd', 'after_relationship_add'); 

if(!isset($hook_array['after_relationship_delete'])){ $hook_array['after_relationship_delete'] = Array(); }
$hook_array['after_relationship_delete'][] = Array(99, 'Delete Unit in Employees', 'modules/Positions/logic_hooks/afterRelationshipDelete.php','afterRelationshipDelete', 'after_relationship_delete'); 

?>