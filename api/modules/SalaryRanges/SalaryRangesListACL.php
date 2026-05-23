<?php

namespace MintHCM\Modules\SalaryRanges;

use BeanFactory;
use MintHCM\Lib\Search\ElasticSearch\BaseListACL;
use DBManagerFactory;
use MintHCM\Utils\LegacyConnector;

class SalaryRangesListACL extends BaseListACL
{
    protected function getFiltersByOwner(string $user_id): array
    {
        global $current_user;
        $filters = parent::getFiltersByOwner($user_id);
        chdir('../legacy/');
        $bean = BeanFactory::newBean($this->module);
        $acl_controller = new LegacyConnector('ACLController');
        if (!$bean->bean_implements('ACL') || (
            !$acl_controller::requireOwner($bean->module_dir, 'list')
            && !$acl_controller::requireSecurityGroup($bean->module_dir, 'list')
        )) {
            return [];
        }
        $positions_ids = $this->getRelatedPositionIds($current_user->position_id, true);
        chdir('../api/');
        $filters[] = [
            'terms' => [$this->prefixer->modify('position_id') . ".keyword" => $positions_ids],
        ];
        return $filters;
    }

    protected function getRelatedPositionIds(string $position_id, $include_source_position_id = false)
    {
        $sql = "SELECT 
                    position_from_id, position_to_id 
                FROM 
                    careerpaths
                where 
                    deleted=0 
                    AND (
                        position_from_id='{$position_id}' 
                        OR position_to_id = '{$position_id}'
                    )
        ";
        $db = DBManagerFactory::getInstance();
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
