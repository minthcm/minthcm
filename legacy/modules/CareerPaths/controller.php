<?php

require_once 'include/MVC/Controller/SugarController.php';

class CareerPathsController extends SugarController
{
    public static function getRelatedPositionIds(string $position_id, $include_source_position_id = false)
    {
        $sql = "SELECT position_from_id, position_to_id FROM careerpaths where deleted=0 AND (position_from_id='{$position_id}' OR position_to_id = '{$position_id}')";
        global $db;
        $result = $db->query($sql);
        $related_positions = [];
        while ($row = $db->fetchByAssoc($result)) {
            $related_positions[] = $row['position_from_id'] == $position_id ? $row['position_to_id'] : $row['position_from_id'];
        }
        if($include_source_position_id){
            $related_positions[] = $position_id;
        }
        return array_unique($related_positions);
    }
}
